<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        $wallet = auth()->user()->wallet;
        $transfers = DB::table('transfers')
            ->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('transfers', compact('wallet', 'transfers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_email' => ['required', 'email', 'exists:users,email'],
            'amount' => ['required', 'numeric', 'min:10'],
        ]);

        $sender = auth()->user();
        if ($sender->email === $request->recipient_email) {
            return back()->withErrors(['recipient_email' => 'You cannot transfer balance to yourself.']);
        }

        $recipient = User::where('email', $request->recipient_email)->first();
        $amount = (float) $request->amount;

        // Section 9 Rule: Fee = Rs 2 + 2% of amount
        $fee = round(2.00 + ($amount * 0.02), 2);
        $totalDeducted = $amount + $fee;

        return DB::transaction(function () use ($sender, $recipient, $amount, $fee, $totalDeducted) {
            $senderWallet = Wallet::where('user_id', $sender->id)->lockForUpdate()->first();

            if ($senderWallet->withdrawable_balance < $totalDeducted) {
                return back()->withErrors([
                    'amount' => "Insufficient Main Balance. Total required (including Rs {$fee} fee) is Rs {$totalDeducted}."
                ]);
            }

            // Deduct sender
            $senderWallet->decrement('main_balance', $totalDeducted);
            $senderWallet->decrement('withdrawable_balance', $totalDeducted);

            // Credit receiver
            $receiverWallet = Wallet::where('user_id', $recipient->id)->lockForUpdate()->first();
            $receiverWallet->increment('main_balance', $amount);
            $receiverWallet->increment('withdrawable_balance', $amount);

            // Record Transfer
            $transferId = DB::table('transfers')->insertGetId([
                'sender_id' => $sender->id,
                'receiver_id' => $recipient->id,
                'amount' => $amount,
                'fee' => $fee,
                'total_deducted' => $totalDeducted,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Ledger records
            Transaction::create([
                'user_id' => $sender->id,
                'amount' => $totalDeducted,
                'type' => 'transfer_out',
                'balance_type' => 'debit',
                'description' => "Balance Transfer to {$recipient->name} (Fee: Rs {$fee})",
                'reference_id' => $transferId,
            ]);

            Transaction::create([
                'user_id' => $recipient->id,
                'amount' => $amount,
                'type' => 'transfer_in',
                'balance_type' => 'credit',
                'description' => "Balance Received from {$sender->name}",
                'reference_id' => $transferId,
            ]);

            return back()->with('success', "Rs {$amount} transferred to {$recipient->name} successfully! (Fee charged: Rs {$fee})");
        });
    }
}
