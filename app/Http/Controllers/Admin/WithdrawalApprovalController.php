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
    // All Withdrawals (Master List)
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->latest()->get();
        $pageTitle = 'All Withdrawal Requests';
        $currentTab = 'all';

        return view('admin.withdrawals.index', compact('withdrawals', 'pageTitle', 'currentTab'));
    }

    // Pending Withdrawals
    public function pending()
    {
        $withdrawals = Withdrawal::with('user')
            ->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('status', 0)
                    ->orWhere('status', '0');
            })
            ->latest()
            ->get();

        $pageTitle = 'Pending Withdrawals';
        $currentTab = 'pending';

        return view('admin.withdrawals.index', compact('withdrawals', 'pageTitle', 'currentTab'));
    }

    // Approved / Paid Withdrawals
    public function approved()
    {
        $withdrawals = Withdrawal::with('user')
            ->where(function ($q) {
                $q->where('status', 'approved')
                    ->orWhere('status', 1)
                    ->orWhere('status', '1');
            })
            ->latest()
            ->get();

        $pageTitle = 'Approved Withdrawals';
        $currentTab = 'approved';

        return view('admin.withdrawals.index', compact('withdrawals', 'pageTitle', 'currentTab'));
    }

    // Rejected / Refunded Withdrawals
    public function rejected()
    {
        $withdrawals = Withdrawal::with('user')
            ->where(function ($q) {
                $q->where('status', 'rejected')
                    ->orWhere('status', 2)
                    ->orWhere('status', '2');
            })
            ->latest()
            ->get();

        $pageTitle = 'Rejected Withdrawals';
        $currentTab = 'rejected';

        return view('admin.withdrawals.index', compact('withdrawals', 'pageTitle', 'currentTab'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending' && $withdrawal->status != 0) {
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
        if ($withdrawal->status !== 'pending' && $withdrawal->status != 0) {
            return back()->with('error', 'Withdrawal already processed.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $request->input('admin_note', 'Rejected by admin'),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $wallet = Wallet::where('user_id', $withdrawal->user_id)->lockForUpdate()->first();
            if ($wallet) {
                $wallet->increment('withdrawable_balance', $withdrawal->amount);
            }

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
