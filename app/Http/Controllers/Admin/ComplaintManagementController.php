<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Complaint::with(['user.activePackage.package'])->latest();

        if (in_array($status, ['pending', 'in_progress', 'resolved'])) {
            $query->where('status', $status);
        }

        $complaints = $query->paginate(20)->withQueryString();

        return view('admin.complaints.index', compact('complaints', 'status'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status'      => 'required|in:pending,in_progress,resolved',
            'admin_reply' => 'nullable|string|max:1000',
        ]);

        $complaint->update([
            'status'      => $request->status,
            'admin_reply' => $request->admin_reply,
            'resolved_by' => $request->status === 'resolved' ? auth()->id() : $complaint->resolved_by,
            'resolved_at' => $request->status === 'resolved' ? now() : ($request->status === 'pending' ? null : $complaint->resolved_at),
        ]);

        return back()->with('success', "Complaint #{$complaint->id} status updated to " . ucfirst(str_replace('_', ' ', $request->status)) . ".");
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return back()->with('success', 'Complaint deleted successfully.');
    }
}
