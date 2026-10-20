<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::with(['wallet', 'activePackage.package', 'referrer'])
            ->where('role', 'user')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Individual User Profile in Admin Dashboard (Matches Client Image 1)
    public function show(User $user)
    {
        $wallet = $user->wallet ?: $user->wallet()->create();
        $user->load(['packages.package', 'referrer.referrals', 'referrals']);

        $totalDeposited = $user->packagePayments()->where('status', 'approved')->sum('amount');
        $totalWithdrawn = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $transactionsCount = Transaction::where('user_id', $user->id)->count();

        return view('admin.users.show', compact('user', 'wallet', 'totalDeposited', 'totalWithdrawn', 'transactionsCount'));
    }

    // Manual Balance Add / Deduct
    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'type' => ['required', 'in:add,subtract'],
            'wallet_type' => ['required', 'in:main,rewards'],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $wallet = $user->wallet ?: $user->wallet()->create();
        $amount = (float) $request->amount;
        $column = $request->wallet_type === 'rewards' ? 'rewards_balance' : 'main_balance';

        if ($request->type === 'add') {
            $wallet->increment($column, $amount);
            if ($request->wallet_type === 'main') {
                $wallet->increment('withdrawable_balance', $amount);
            }
            $txType = 'manual_add';
            $balanceType = 'credit';
            $msg = "Added Rs {$amount} to {$user->name}'s {$request->wallet_type} balance.";
        } else {
            if ($wallet->$column < $amount) {
                return back()->with('error', 'User does not have sufficient balance for deduction.');
            }
            $wallet->decrement($column, $amount);
            if ($request->wallet_type === 'main') {
                $wallet->decrement('withdrawable_balance', $amount);
            }
            $txType = 'manual_deduct';
            $balanceType = 'debit';
            $msg = "Deducted Rs {$amount} from {$user->name}'s {$request->wallet_type} balance.";
        }

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $txType,
            'balance_type' => $balanceType,
            'description' => $request->remark ?: "Admin Manual {$request->type} ({$request->wallet_type})",
        ]);

        return back()->with('success', $msg);
    }
}
