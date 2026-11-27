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
            'method'         => ['required', 'in:Easypaisa,JazzCash'],
            'amount'         => ['required', 'numeric', 'min:' . $minWithdrawal],
            'username'       => ['required', 'string', 'max:100'],
            'account_title'  => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:50'],
        ], [
            'amount.min'              => "The minimum withdrawal amount for request #" . ($withdrawalCount + 1) . " is Rs {$minWithdrawal}.",
            'username.required'       => 'Please enter your platform username.',
            'account_title.required'  => 'Please enter the account holder’s name.',
            'account_number.required' => 'Please enter your Easypaisa/JazzCash account number.',
        ]);

        $amount = (float) $request->amount;
        $username = trim($request->username);
        $accountTitle = trim($request->account_title);
        $accountNumber = trim($request->account_number);

        return DB::transaction(function () use ($user, $amount, $request, $username, $accountTitle, $accountNumber) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$wallet || $wallet->withdrawable_balance < $amount) {
                return back()->withInput()->withErrors(['amount' => 'Insufficient Main Balance.']);
            }

            // Deduct immediately from Main Balance & Withdrawable Balance
            $wallet->decrement('main_balance', $amount);
            $wallet->decrement('withdrawable_balance', $amount);

            $withdrawal = Withdrawal::create([
                'user_id'         => $user->id,
                'amount'          => $amount,
                'method'          => $request->method,
                'username'        => $username,
                'account_title'   => $accountTitle,
                'account_number'  => $accountNumber,
                'account_details' => "{$accountTitle} - {$accountNumber}", // Legacy column fallback
                'status'          => 'pending',
            ]);

            Transaction::create([
                'user_id'      => $user->id,
                'amount'       => $amount,
                'type'         => 'withdraw',
                'balance_type' => 'debit',
                'description'  => "Withdrawal request ({$request->method} - {$accountTitle} / {$accountNumber})",
                'reference_id' => $withdrawal->id,
            ]);

            return redirect()->route('wallet')->with('success', 'Withdrawal request submitted successfully!');
        });
    }
}
