<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalApprovalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->latest()->get();
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal already processed.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal approved.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal already processed.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $request->input('admin_note', 'Rejected by admin'),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Refund balance back to user wallet
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->lockForUpdate()->first();
            $wallet->increment('withdrawable_balance', $withdrawal->amount);

            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'amount' => $withdrawal->amount,
                'type' => 'refund',
                'balance_type' => 'credit',
                'description' => "Refund for rejected withdrawal #{$withdrawal->id}",
                'reference_id' => $withdrawal->id,
            ]);
        });

        return back()->with('success', 'Withdrawal rejected and funds refunded to user.');
    }
}
