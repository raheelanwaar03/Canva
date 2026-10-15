<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackagePayment;
use App\Models\UserPackage;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentApprovalController extends Controller
{
    // View pending and recent payments
    public function index()
    {
        $payments = PackagePayment::with(['user', 'package'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    // Approve payment
    public function approve(PackagePayment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment has already been processed.');
        }

        DB::transaction(function () use ($payment) {
            $now = now();
            $package = $payment->package;
            $user = $payment->user;

            // 1. Mark payment as approved
            $payment->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => $now,
            ]);

            // 2. Find previous active package (to calculate upgrade commission difference if any)
            $previousSub = UserPackage::with('package')
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            // Expire previous subscription
            if ($previousSub) {
                $previousSub->update(['status' => 'upgraded']);
            }

            // 3. Activate new package for 30 days
            UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'started_at' => $now,
                'expires_at' => $now->copy()->addDays($package->duration_days ?? 30),
                'status' => 'active',
            ]);

            // 4. Distribute Referral Commission to Direct Referrer
            if ($user->referred_by) {
                $commission = $package->referral_commission;

                // If it's an upgrade, award only the commission difference
                if ($payment->type === 'upgrade' && $previousSub && $previousSub->package) {
                    $commission = max(0, $package->referral_commission - $previousSub->package->referral_commission);
                }

                if ($commission > 0) {
                    $referrerWallet = Wallet::where('user_id', $user->referred_by)->lockForUpdate()->first();
                    if ($referrerWallet) {
                        $referrerWallet->increment('referral_earnings', $commission);
                        $referrerWallet->increment('withdrawable_balance', $commission);

                        Transaction::create([
                            'user_id' => $user->referred_by,
                            'amount' => $commission,
                            'type' => 'referral_bonus',
                            'balance_type' => 'credit',
                            'description' => $payment->type === 'upgrade'
                                ? "Upgrade referral commission from {$user->name} ({$package->name})"
                                : "Referral bonus from {$user->name} ({$package->name} plan)",
                            'reference_id' => $payment->id,
                        ]);
                    }
                }
            }
        });

        return back()->with('success', "Payment approved and {$payment->package->name} plan activated for {$payment->user->name}!");
    }

    // Reject payment
    public function reject(Request $request, PackagePayment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment has already been processed.');
        }

        $payment->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note', 'Invalid payment details or screenshot'),
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Payment rejected.');
    }
}
