<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackagePayment;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    // List all 4 packages
    public function index()
    {
        $packages = Package::all();
        $activePackage = auth()->user()?->activePackage;

        return view('packages.index', compact('packages', 'activePackage'));
    }

    // Checkout / Payment submission form
    public function checkout(Package $package)
    {
        return view('packages.checkout', compact('package'));
    }

    // Store manual payment proof
    public function submitPayment(Request $request, Package $package)
    {
        $request->validate([
            'payment_method' => ['required', 'in:Easypaisa,JazzCash'],
            'sender_number' => ['required', 'string', 'max:50'],
            'trx_id' => ['required', 'string', 'max:100'],
            'screenshot' => ['nullable', 'image', 'max:4096'],
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('payments', 'public');
        }

        PackagePayment::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'amount' => $package->price,
            'payment_method' => $request->payment_method,
            'sender_number' => $request->sender_number,
            'trx_id' => $request->trx_id,
            'screenshot' => $screenshotPath,
            'type' => 'new',
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('status', 'Payment submitted successfully! Admin will verify and activate your package soon.');
    }
}
