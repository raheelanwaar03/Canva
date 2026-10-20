<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackagePayment;
use App\Models\UserPackage;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentApprovalController extends Controller
{
    // View pending and recent payments
    public function index()
    {
        $payments = PackagePayment::with(['user', 'package'])
            ->latest()
            ->get();

        return view('admin.payments.index', compact('payments'));
    }

    // Approve payment & distribute commissions
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

            // 2. Expire any previous subscription
            $previousSub = UserPackage::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($previousSub) {
                $previousSub->update(['status' => 'upgraded']);
            }

            // 3. Activate new package for 30 days
            UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'started_at' => $now,
                'expires_at' => $now->copy()->addDays(30),
                'status' => 'active',
            ]);

            // 4. Commission Distribution
            if ($payment->type === 'upgrade') {
                // Direct inviter upgrade commission only (20% on price difference)
                if ($user->referred_by) {
                    $upgradeComm = round($payment->amount * 0.20, 2);
                    if ($upgradeComm > 0) {
                        $uplineWallet = Wallet::firstOrCreate(
                            ['user_id' => $user->referred_by],
                            [
                                'main_balance' => 0.00,
                                'rewards_balance' => 0.00,
                                'task_earnings' => 0.00,
                                'referral_earnings' => 0.00,
                                'staff_earnings' => 0.00,
                                'withdrawable_balance' => 0.00,
                            ]
                        );

                        $uplineWallet->increment('main_balance', $upgradeComm);
                        $uplineWallet->increment('withdrawable_balance', $upgradeComm);
                        $uplineWallet->increment('referral_earnings', $upgradeComm);

                        Transaction::create([
                            'user_id' => $user->referred_by,
                            'amount' => $upgradeComm,
                            'type' => 'referral_bonus',
                            'balance_type' => 'credit',
                            'description' => "Direct Upgrade Commission from {$user->name} ({$package->name})",
                            'reference_id' => $payment->id,
                        ]);
                    }
                }
            } else {
                // New Package Purchase: 3-Tier Commission
                // Basic, Standard, Premium pay Level 1 only (25%). Gold pays Level 1 (25%), Level 2 (3%), Level 3 (1%).
                $pkgPrice = (float) $payment->amount;
                $isGold = strtolower($package->name) === 'gold';

                $commissionTiers = [
                    1 => ['rate' => 0.25, 'desc' => 'Direct Inviter (Level 1 - 25%)'],
                    2 => ['rate' => 0.03, 'desc' => 'Level 2 Upline (3%)'],
                    3 => ['rate' => 0.01, 'desc' => 'Level 3 Upline (1%)'],
                ];

                $currentUplineId = $user->referred_by;
                $tier = 1;

                while ($currentUplineId && $tier <= 3) {
                    // Non-Gold packages stop after Level 1
                    if (!$isGold && $tier > 1) {
                        break;
                    }

                    $upline = User::with('activePackage')->find($currentUplineId);

                    // Check if upline exists and has an active plan
                    if ($upline && $upline->activePackage) {
                        $commAmount = round($pkgPrice * $commissionTiers[$tier]['rate'], 2);

                        if ($commAmount > 0) {
                            $uplineWallet = Wallet::firstOrCreate(
                                ['user_id' => $upline->id],
                                [
                                    'main_balance' => 0.00,
                                    'rewards_balance' => 0.00,
                                    'task_earnings' => 0.00,
                                    'referral_earnings' => 0.00,
                                    'staff_earnings' => 0.00,
                                    'withdrawable_balance' => 0.00,
                                ]
                            );

                            $uplineWallet->increment('main_balance', $commAmount);
                            $uplineWallet->increment('withdrawable_balance', $commAmount);
                            $uplineWallet->increment('referral_earnings', $commAmount);

                            Transaction::create([
                                'user_id' => $upline->id,
                                'amount' => $commAmount,
                                'type' => 'referral_bonus',
                                'balance_type' => 'credit',
                                'description' => "{$commissionTiers[$tier]['desc']} from {$user->name} ({$package->name})",
                                'reference_id' => $payment->id,
                            ]);
                        }
                    }

                    $currentUplineId = $upline?->referred_by;
                    $tier++;
                }
            }
        });

        return back()->with('success', "Payment approved and {$payment->package->name} plan activated!");
    }

    // Reject payment
    public function reject(Request $request, PackagePayment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment has already been processed.');
        }

        $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Payment for {$payment->user->name} has been rejected with the provided reason.");
    }
}
