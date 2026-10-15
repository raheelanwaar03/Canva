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
            'task_earnings' => 0.00,
            'referral_earnings' => 0.00,
            'staff_earnings' => 0.00,
            'withdrawable_balance' => 0.00,
        ]);

        return view('withdraw', compact('wallet'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'method' => ['required', 'in:Easypaisa,JazzCash'],
            'amount' => ['required', 'numeric', 'min:50'],
            'account_details' => ['required', 'string', 'max:255'],
        ]);

        $user = auth()->user();
        $amount = (float) $request->amount;

        return DB::transaction(function () use ($user, $amount, $request) {
            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if ($wallet->withdrawable_balance < $amount) {
                return back()->withErrors(['amount' => 'Insufficient withdrawable balance.']);
            }

            // Deduct balance immediately
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
