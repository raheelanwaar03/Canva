<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = RefundRequest::with(['user.activePackage.package'])->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $refunds = $query->paginate(20)->withQueryString();

        return view('admin.refunds.index', compact('refunds', 'status'));
    }

    public function approve(Request $request, RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'This refund has already been decided.');
        }

        DB::transaction(function () use ($refund, $request) {
            $refund->update([
                'status'        => 'approved',
                'admin_remark'  => $request->input('remark', 'Refund approved and dispatched.'),
                'decided_at'    => now(),
            ]);

            // Deactivate member's active package upon refund approval
            UserPackage::where('user_id', $refund->user_id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);
        });

        return back()->with('success', "Refund of Rs {$refund->amount} for {$refund->user->name} has been approved.");
    }

    public function reject(Request $request, RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'This refund has already been decided.');
        }

        $refund->update([
            'status'        => 'rejected',
            'admin_remark'  => $request->input('remark', 'Refund request declined according to policy.'),
            'decided_at'    => now(),
        ]);

        return back()->with('success', "Refund request for {$refund->user->name} was rejected.");
    }
}
