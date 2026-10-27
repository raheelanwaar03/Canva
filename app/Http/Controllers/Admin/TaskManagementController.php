<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(20);
        return view('admin.tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'ad_url'           => 'required|url|max:500',
            'duration_seconds' => 'required|integer|min:5',
            'is_active'        => 'required|boolean',
        ]);

        \App\Models\Task::create($validated);

        return back()->with('success', 'Ad successfully added to inventory.');
    }

    public function update(Request $request, \App\Models\Task $task)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'ad_url'           => 'required|url|max:500',
            'duration_seconds' => 'required|integer|min:5',
            'is_active'        => 'required|boolean',
        ]);

        $task->update($validated);

        return back()->with('success', 'Ad updated successfully.');
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
        return back()->with('success', 'Ad deleted successfully.');
    }
}
