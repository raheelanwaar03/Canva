<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = RefundRequest::with(['user.activePackage.package'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $refunds = $query->paginate(20)->withQueryString();

        return view('admin.refunds.index', compact('refunds', 'status'));
    }

    /**
     * APPROVE REFUND:
     * - Marks refund as 'approved'
     * - Deactivates package
     * - Changes user account status to 'rejected' (Account closed/suspended)
     */
    public function approve(Request $request, RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'This refund request has already been processed.');
        }

        DB::transaction(function () use ($refund, $request) {
            $remark = $request->input('admin_remark', 'Refund approved and payment refunded.');

            $refund->update([
                'status'       => 'approved',
                'admin_remark' => $remark,
                'decided_at'   => now(),
            ]);

            // Deactivate member's active package
            UserPackage::where('user_id', $refund->user_id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            // Set user account status to 'rejected'
            $refund->user->update([
                'status' => 'rejected'
            ]);
        });

        return back()->with('success', "Refund of Rs {$refund->amount} approved. {$refund->user->name}'s account has been closed.");
    }

    /**
     * REJECT REFUND:
     * - Admin adds reason for rejection
     * - Marks refund request as 'rejected'
     * - User account remains completely normal & active
     */
    public function reject(Request $request, RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'This refund request has already been processed.');
        }

        $request->validate([
            'admin_remark' => 'required|string|min:3|max:1000',
        ], [
            'admin_remark.required' => 'Please provide a reason for rejecting this refund request.',
        ]);

        $refund->update([
            'status'       => 'rejected',
            'admin_remark' => $request->admin_remark,
            'decided_at'   => now(),
        ]);

        return back()->with('success', "Refund rejected with reason. User account remains active.");
    }
}
