<?php

namespace App\Http\Controllers\Admin;

// <-- MUST BE IMPORTED
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\PackagePayment;
use App\Models\Withdrawal;
use App\Models\TaskLog;
use App\Models\UserPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * List all users with status tabs & search support
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = User::with(['wallet', 'activePackage.package', 'referrer', 'packagePayments'])
            ->where('role', 'user');

        if ($status === 'approved') {
            $query->whereHas('packages', function ($q) {
                $q->whereIn('status', ['active', 'upgraded']);
            });
        } elseif ($status === 'pending') {
            $query->whereHas('packagePayments', function ($q) {
                $q->where('status', 'pending');
            });
        } elseif ($status === 'rejected') {
            $query->whereHas('packagePayments', function ($q) {
                $q->where('status', 'rejected');
            })->whereDoesntHave('packages', function ($q) {
                $q->where('status', 'active');
            });
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'status'));
    }

    /**
     * Complete User Details Page (Sections 1 to 10)
     */
    public function show(User $user)
    {
        // 1. Ensure Wallet exists
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'main_balance' => 0.00,
                'rewards_balance' => 0.00,
                'task_earnings' => 0.00,
                'referral_earnings' => 0.00,
                'staff_earnings' => 0.00,
                'withdrawable_balance' => 0.00,
            ]
        );

        // 2. Load Relations
        $user->load(['referrer', 'activePackage.package']);

        // 3. Financial Metrics from Database
        $totalDeposited = PackagePayment::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawn = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        $totalEarnings = ($wallet->task_earnings ?? 0) + ($wallet->referral_earnings ?? 0);

        // 4. Payment Records & Latest Submission
        $payments = PackagePayment::with(['package', 'reviewer'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        $latestPayment = $payments->first();

        // Account status evaluation
        if ($user->activePackage) {
            $accountStatus = 'Approved';
        } elseif ($latestPayment && $latestPayment->status === 'pending') {
            $accountStatus = 'Pending';
        } elseif ($latestPayment && $latestPayment->status === 'rejected') {
            $accountStatus = 'Rejected';
        } else {
            $accountStatus = 'No Plan';
        }

        // 5. Referral & Commission Ledger
        $referralCommissions = Transaction::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('type', 'referral_bonus');
        })
            ->orWhere(function ($q) use ($user) {
                $q->where('description', 'like', "%{$user->name}%")
                    ->where('type', 'referral_bonus');
            })
            ->latest()
            ->get();

        // 6. Direct Referral Team with Commissions Generated
        $teamMembers = User::with(['activePackage.package', 'wallet', 'packagePayments'])
            ->where('referred_by', $user->id)
            ->latest()
            ->get()
            ->map(function ($member) use ($user) {
                $commEarnedFromMember = Transaction::where('user_id', $user->id)
                    ->where('type', 'referral_bonus')
                    ->where('description', 'like', "%{$member->name}%")
                    ->sum('amount');

                $member->commission_generated = $commEarnedFromMember;
                return $member;
            });

        // 7. Complete Transaction History
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(100)
            ->get();

        // 8. Task History & Eligibility Engine
        $taskLogs = TaskLog::with('task')
            ->where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get();

        $tasksCompletedToday = TaskLog::where('user_id', $user->id)
            ->where('completed_date', now()->toDateString())
            ->count();

        $totalTasksCompleted = TaskLog::where('user_id', $user->id)->count();

        $eligibility = $user->getTaskEligibilityStatus();

        return view('admin.users.show', compact(
            'user',
            'wallet',
            'accountStatus',
            'totalDeposited',
            'totalWithdrawn',
            'totalEarnings',
            'payments',
            'latestPayment',
            'referralCommissions',
            'teamMembers',
            'transactions',
            'taskLogs',
            'tasksCompletedToday',
            'totalTasksCompleted',
            'eligibility'
        ));
    }

    /**
     * Show Edit User Form
     */
    public function edit(User $user)
    {
        $packages = Package::all();
        $potentialUplines = User::where('role', 'user')->where('id', '!=', $user->id)->get();
        $activePackage = $user->activePackage;

        return view('admin.users.edit', compact('user', 'packages', 'potentialUplines', 'activePackage'));
    }

    /**
     * Update User Details, Status, Package & Referral Hierarchy
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'            => ['nullable', 'string', 'max:50'],
            'referral_code'    => ['required', 'string', 'max:50', 'unique:users,referral_code,' . $user->id],
            'referred_by'      => ['nullable', 'exists:users,id'],
            'status'           => ['required', 'in:active,pending,banned'],
            'custom_level'     => ['nullable', 'integer', 'min:0', 'max:8'],
            'package_id'       => ['nullable', 'exists:packages,id'],
            'package_days'     => ['nullable', 'integer', 'min:1', 'max:365'],
            'main_balance'     => ['nullable', 'numeric', 'min:0'],
            'rewards_balance'  => ['nullable', 'numeric', 'min:0'],
            'task_earnings'    => ['nullable', 'numeric', 'min:0'],
            'referral_earnings' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $now = now();

            // 1. Update basic profile info & custom level
            $user->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'referral_code' => strtoupper($request->referral_code),
                'referred_by'   => $request->referred_by ?: null,
                'status'        => $request->status,
                'custom_level'  => $request->filled('custom_level') ? (int) $request->custom_level : null,
            ]);

            // 2. Handle Wallet Balances Update Directly
            if ($request->has('main_balance') || $request->has('rewards_balance')) {
                $wallet = $user->wallet ?: $user->wallet()->create([
                    'main_balance'         => 0.00,
                    'rewards_balance'      => 0.00,
                    'task_earnings'        => 0.00,
                    'referral_earnings'    => 0.00,
                    'staff_earnings'       => 0.00,
                    'withdrawable_balance' => 0.00,
                ]);

                $newMain = (float) $request->input('main_balance', $wallet->main_balance);

                $wallet->update([
                    'main_balance'         => $newMain,
                    'withdrawable_balance' => $newMain,
                    'rewards_balance'      => (float) $request->input('rewards_balance', $wallet->rewards_balance),
                    'task_earnings'        => (float) $request->input('task_earnings', $wallet->task_earnings),
                    'referral_earnings'    => (float) $request->input('referral_earnings', $wallet->referral_earnings),
                ]);
            }

            // 3. Handle Package Change / Manual Upgrade
            if ($request->filled('package_id')) {
                $newPackage = Package::findOrFail($request->package_id);
                $durationDays = (int) ($request->input('package_days', 30) ?: 30);

                // Expire existing packages
                UserPackage::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->update(['status' => 'upgraded']);

                // Create and activate new package
                UserPackage::create([
                    'user_id'    => $user->id,
                    'package_id' => $newPackage->id,
                    'started_at' => $now,
                    'expires_at' => $now->copy()->addDays($durationDays),
                    'status'     => 'active',
                ]);
            }
        });

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User details and settings for {$user->name} updated successfully!");
    }

    /**
     * Manual Balance Adjustment (+ / -)
     */
    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type'   => 'required|in:add,subtract', // or whatever form inputs you have
            'remark' => 'nullable|string|max:255',
        ]);

        $wallet = $user->wallet ?: $user->wallet()->create([
            'main_balance'         => 0.00,
            'rewards_balance'      => 0.00,
            'task_earnings'        => 0.00,
            'referral_earnings'    => 0.00,
            'staff_earnings'       => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        $amount = (float) $request->amount;
        $isAdd = $request->type === 'add';

        if ($isAdd) {
            $wallet->increment('main_balance', $amount);
            $wallet->increment('withdrawable_balance', $amount);
        } else {
            $wallet->decrement('main_balance', min($wallet->main_balance, $amount));
            $wallet->decrement('withdrawable_balance', min($wallet->withdrawable_balance, $amount));
        }

        // Use 'referral_bonus' (valid ENUM value) and explain the adjustment in description:
        Transaction::create([
            'user_id'      => $user->id,
            'amount'       => $amount,
            'type'         => 'referral_bonus', // <--- Uses valid ENUM
            'balance_type' => $isAdd ? 'credit' : 'debit',
            'description'  => "Admin balance adjustment: " . ($request->remark ?: ($isAdd ? 'Added by admin' : 'Deducted by admin')),
        ]);

        return back()->with('success', 'User balance adjusted successfully!');
    }

    public function impersonate(User $user)
    {
        // 1. Prevent admin from impersonating themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot impersonate your own admin account.');
        }

        // 2. Preserve original Admin ID in the session
        session(['admin_impersonator_id' => Auth::id()]);

        // 3. Log in as target user/applicant
        Auth::login($user);
        request()->session()->regenerate();

        // 4. Safe Redirect Check:
        // If the user has an active package, send to dashboard.
        // If they are a new signup / pending applicant, send to packages or pending view
        // to prevent the `has_active_package` middleware from throwing an error.
        $hasActivePackage = method_exists($user, 'activePackage')
            ? $user->activePackage()->exists()
            : ($user->package_id !== null);

        if ($hasActivePackage) {
            return redirect()->route('dashboard')->with('status', "Logged in as {$user->name}");
        }

        // For applicants / unverified users without active package
        return redirect()->route('packages.index')->with('status', "Viewing as Applicant: {$user->name} (No active package yet)");
    }

    /**
     * Return back to Admin account
     */
    public function leaveImpersonation()
    {
        $adminId = session('admin_impersonator_id');

        if (!$adminId) {
            return redirect()->route('dashboard');
        }

        $admin = User::find($adminId);

        if ($admin && $admin->role === 'admin') {
            session()->forget('admin_impersonator_id');
            Auth::login($admin);
            request()->session()->regenerate();

            return redirect()->route('admin.users.index')->with('status', 'Returned safely to Admin Dashboard.');
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
