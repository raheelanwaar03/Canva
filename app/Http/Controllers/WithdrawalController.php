<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $wallet = $user->wallet ?: $user->wallet()->create([
            'main_balance' => 0.00,
            'rewards_balance' => 0.00,
            'task_earnings' => 0.00,
            'referral_earnings' => 0.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        // Count previous successful/pending withdrawals
        $withdrawalCount = Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->count();

        // 1st & 2nd withdrawals: Min Rs 30; 3rd onwards: Min Rs 500
        $minWithdrawal = ($withdrawalCount < 2) ? 30 : 500;

        return view('withdraw', compact('wallet', 'minWithdrawal', 'withdrawalCount'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Determine applicable dynamic minimum limit
        $withdrawalCount = Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->count();

        $minWithdrawal = ($withdrawalCount < 2) ? 30 : 500;

        $request->validate([
            'method' => ['required', 'in:Easypaisa,JazzCash'],
            'amount' => ['required', 'numeric', 'min:' . $minWithdrawal],
            'account_details' => ['required', 'string', 'max:255'],
        ], [
            'amount.min' => "The minimum withdrawal amount for request #" . ($withdrawalCount + 1) . " is Rs {$minWithdrawal}."
        ]);

        $amount = (float) $request->amount;

        return DB::transaction(function () use ($user, $amount, $request) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if ($wallet->withdrawable_balance < $amount) {
                return back()->withErrors(['amount' => 'Insufficient Main Balance.']);
            }

            // Deduct immediately from Main Balance & Withdrawable Balance
            $wallet->decrement('main_balance', $amount);
            $wallet->decrement('withdrawable_balance', $amount);

            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $request->method,
                'account_details' => $request->account_details,
                'status' => 'pending',
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'withdraw',
                'balance_type' => 'debit',
                'description' => "Withdrawal request ({$request->method})",
                'reference_id' => $withdrawal->id,
            ]);

            return redirect()->route('wallet')->with('success', 'Withdrawal request submitted successfully!');
        });
    }
}
