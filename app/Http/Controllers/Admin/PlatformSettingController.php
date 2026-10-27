<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Gateway Details
            'payment_gateway_name'    => 'required|string|max:50',
            'payment_account_title'   => 'required|string|max:100',
            'payment_account_number'  => 'required|string|max:50',
            'payment_instruction'     => 'nullable|string|max:500',

            // Earning Rules
            'default_ad_rate'         => 'required|numeric|min:0',
            'referral_level_1_percent' => 'required|numeric|min:0|max:100',
            'referral_level_2_percent' => 'nullable|numeric|min:0|max:100',
            'referral_level_3_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Platform business settings and wallet details updated successfully!');
    }
}
