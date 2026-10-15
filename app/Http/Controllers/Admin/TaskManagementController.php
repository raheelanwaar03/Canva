<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ad_url' => ['required', 'url'],
            'duration_seconds' => ['required', 'integer', 'min:5', 'max:120'],
        ]);

        Task::create($validated + ['is_active' => true]);

        return back()->with('success', 'New ad task created successfully!');
    }

    public function toggle(Task $task)
    {
        $task->update(['is_active' => !$task->is_active]);
        $status = $task->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Ad task {$status}!");
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Ad task deleted successfully!');
    }
}
