<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageManagementController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'daily_task_limit' => ['required', 'integer', 'min:1'],
            'reward_per_task' => ['required', 'numeric', 'min:0'],
            'referral_commission' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
        ]);

        $package->update($validated);

        return back()->with('success', "{$package->name} settings updated successfully!");
    }
}
