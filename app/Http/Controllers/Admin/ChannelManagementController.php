<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelManagementController extends Controller
{
    public function index()
    {
        $channels = Channel::latest()->get();
        return view('admin.channels.index', compact('channels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        Channel::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?: 'fa-solid fa-paper-plane',
            'is_active' => true,
        ]);

        return back()->with('success', 'Channel created successfully!');
    }

    public function update(Request $request, Channel $channel)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);

        $channel->update($validated);

        return back()->with('success', 'Channel updated successfully!');
    }

    public function toggle(Channel $channel)
    {
        $channel->update(['is_active' => !$channel->is_active]);
        $status = $channel->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Channel {$status}!");
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return back()->with('success', 'Channel deleted successfully!');
    }
}
