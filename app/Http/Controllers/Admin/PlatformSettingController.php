<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Support direct (Level 0) through Level 8
        $levelRates = [];
        for ($i = 0; $i <= 8; $i++) {
            $default = match ($i) {
                0 => '7.00',
                1 => '2.00',
                2 => '3.00',
                3 => '4.00',
                4 => '5.00',
                5 => '6.00',
                6 => '7.00',
                7 => '8.00',
                8 => '10.00',
                default => '7.00'
            };
            $levelRates[$i] = $settings["ad_reward_level_{$i}"] ?? $default;
        }

        return view('admin.settings.index', compact('settings', 'levelRates'));
    }

    public function update(Request $request)
    {
        // 1. Save standard settings if submitted
        if ($request->has('settings')) {
            foreach ($request->input('settings') as $key => $val) {
                Setting::set($key, $val);
            }
        }

        // 2. Save dynamic referral level task rates (Levels 0 through 8)
        if ($request->has('level_rates')) {
            foreach ($request->input('level_rates') as $level => $rate) {
                Setting::set("ad_reward_level_{$level}", $rate);
            }
        }

        return back()->with('success', 'Level reward rates (Levels 0–8) updated successfully.');
    }
}
