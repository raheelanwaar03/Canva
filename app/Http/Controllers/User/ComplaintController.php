<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = auth()->user()->complaints()->latest()->paginate(10);
        return view('user.complaints', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'message.required' => 'Please write your complaint or suggestion in detail.',
            'message.min' => 'Message must be at least 10 characters long.',
        ]);

        Complaint::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject ?: 'General Issue / Query',
            'message' => $request->message,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Your complaint has been submitted. Our team will review it shortly.');
    }
}
