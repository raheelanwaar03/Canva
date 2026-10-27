@extends('layouts.admin')

@php
    $title = 'Settings';
@endphp

@section('admin_content')

    <div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Platform & Earning Settings</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Configure your deposit payment account, ad task rates,
                    and multi-tier referral commissions.</p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <!-- 1. Payment Gateway & Wallet Info Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                    <div
                        class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Deposit & Payment Gateway Info</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Displayed on the checkout screen when users
                            purchase or upgrade a plan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Gateway Name</label>
                        <input type="text" name="payment_gateway_name"
                            value="{{ old('payment_gateway_name', $settings['payment_gateway_name'] ?? 'EasyPaisa') }}"
                            required
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Account Title / Name</label>
                        <input type="text" name="payment_account_title"
                            value="{{ old('payment_account_title', $settings['payment_account_title'] ?? 'Shama') }}"
                            required
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Account / Mobile Number</label>
                        <input type="text" name="payment_account_number"
                            value="{{ old('payment_account_number', $settings['payment_account_number'] ?? '03714780429') }}"
                            required
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-2">Checkout Instructions Note</label>
                    <input type="text" name="payment_instruction"
                        value="{{ old('payment_instruction', $settings['payment_instruction'] ?? 'After sending payment, upload receipt screenshot to proceed.') }}"
                        class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                </div>
            </div>

            <!-- 2. Ads & Earning Rules Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                    <div
                        class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-rectangle-ad"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Ads & Task Earning Rates</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Default rate rewarded to users for completing an
                            ad watch task.</p>
                    </div>
                </div>

                <div class="max-w-xs">
                    <label class="block text-xs font-extrabold text-slate-700 mb-2">Default Earning Per Ad View (Rs)</label>
                    <div
                        class="flex items-center bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 focus-within:border-indigo-500 transition">
                        <span class="text-xs font-black text-slate-400 mr-2">Rs</span>
                        <input type="number" step="0.01" min="0" name="default_ad_rate"
                            value="{{ old('default_ad_rate', $settings['default_ad_rate'] ?? '25.00') }}" required
                            class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                    </div>
                </div>
            </div>

            <!-- 3. Referral Commission Rules Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                    <div
                        class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Referral Commission Matrix (%)</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Percentage paid to upline referrers whenever a
                            downline purchases or upgrades a package.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Level 1 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_1_percent"
                            value="{{ old('referral_level_1_percent', $settings['referral_level_1_percent'] ?? 10) }}"
                            required
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                        <span class="text-[10px] text-slate-400 mt-1.5 block">Direct sponsor commission.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Level 2 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_2_percent"
                            value="{{ old('referral_level_2_percent', $settings['referral_level_2_percent'] ?? 5) }}"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                        <span class="text-[10px] text-slate-400 mt-1.5 block">Tier 2 upline commission.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-2">Level 3 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_3_percent"
                            value="{{ old('referral_level_3_percent', $settings['referral_level_3_percent'] ?? 2) }}"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500 transition">
                        <span class="text-[10px] text-slate-400 mt-1.5 block">Tier 3 upline commission.</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs shadow-lg shadow-indigo-600/20 active:scale-95 transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save All Platform Settings
                </button>
            </div>
        </form>
    </div>
@endsection
