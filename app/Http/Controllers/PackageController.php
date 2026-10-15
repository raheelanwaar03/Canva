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

    public function checkout(Package $package)
    {
        $activeSub = auth()->user()?->activePackage;

        $isUpgrade = false;
        $amountToPay = $package->price;

        if ($activeSub) {
            if ($package->id === $activeSub->package_id) {
                return redirect()->route('packages.index')->with('error', 'You already have this plan active.');
            }

            if ($package->price < $activeSub->package->price) {
                return redirect()->route('packages.index')->with('error', 'Downgrades are not permitted.');
            }

            $isUpgrade = true;
            $amountToPay = $package->price - $activeSub->package->price;
        }

        return view('packages.checkout', compact('package', 'isUpgrade', 'amountToPay'));
    }

    public function submitPayment(Request $request, Package $package)
    {
        $request->validate([
            'payment_method' => ['required', 'in:Easypaisa,JazzCash'],
            'sender_number' => ['required', 'string', 'max:50'],
            'trx_id' => ['required', 'string', 'max:100'],
            'screenshot' => ['nullable', 'image', 'max:4096'],
        ]);

        $activeSub = auth()->user()?->activePackage;
        $isUpgrade = false;
        $amountToPay = $package->price;

        if ($activeSub) {
            if ($package->price > $activeSub->package->price) {
                $isUpgrade = true;
                $amountToPay = $package->price - $activeSub->package->price;
            }
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('payments', 'public');
        }

        PackagePayment::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'amount' => $amountToPay,
            'payment_method' => $request->payment_method,
            'sender_number' => $request->sender_number,
            'trx_id' => $request->trx_id,
            'screenshot' => $screenshotPath,
            'type' => $isUpgrade ? 'upgrade' : 'new',
            'status' => 'pending',
        ]);

        $msg = $isUpgrade
            ? 'Upgrade payment submitted! Admin will verify and upgrade your plan.'
            : 'Payment submitted successfully! Admin will verify and activate your package soon.';

        return redirect()->route('dashboard')->with('status', $msg);
    }
}
