<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Exact 7-day refund guarantee window (created_at + 7 days)
        $refundExpiry = $user->created_at->copy()->addDays(7);
        $isEligible = now()->lessThanOrEqualTo($refundExpiry);

        // Days/hours left to apply
        $daysRemaining = max(0, (int) now()->diffInDays($refundExpiry, false));
        $hoursRemaining = max(0, (int) now()->diffInHours($refundExpiry, false));

        $latestRefund = $user->latestRefundRequest;
        $activePackage = $user->activePackage ? $user->activePackage->package : null;

        return view('user.refund', compact(
            'user',
            'isEligible',
            'daysRemaining',
            'hoursRemaining',
            'refundExpiry',
            'latestRefund',
            'activePackage'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Check: Must be WITHIN 7 days of joining
        $refundExpiry = $user->created_at->copy()->addDays(7);
        if (now()->greaterThan($refundExpiry)) {
            return back()->with('error', 'The 7-day refund policy window for your account has expired.');
        }

        // 2. Prevent duplicate pending requests
        if ($user->refundRequests()->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending refund request awaiting admin review.');
        }

        $validated = $request->validate([
            'gateway_name'   => 'required|string|max:50',
            'account_name'   => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'reason'         => 'required|string|min:10|max:1000',
        ]);

        $activePackage = $user->activePackage ? $user->activePackage->package : null;
        $refundAmount = $activePackage ? (float) $activePackage->price : 0.00;

        $now = now();
        RefundRequest::create([
            'user_id'           => $user->id,
            'amount'            => $refundAmount,
            'gateway_name'      => $validated['gateway_name'],
            'account_name'      => $validated['account_name'],
            'account_number'    => $validated['account_number'],
            'reason'            => $validated['reason'],
            'status'            => 'pending',
            'decision_deadline' => $now->copy()->addDays(30), // Admin has 30 days to process
        ]);

        return back()->with('success', 'Your refund request has been submitted. Admin will process it within 30 days.');
    }
}
