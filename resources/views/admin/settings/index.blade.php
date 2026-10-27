@extends('layouts.app')

@php
    $headerTitle = 'Platform Settings';
@endphp

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-800">Platform & Earning Settings</h1>
                <p class="text-xs text-slate-500">Configure global ad earning rates, referral commissions, and payment
                    gateway details.</p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <!-- 1. Payment Gateway & Wallet Details -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-wallet text-emerald-500 text-lg"></i>
                    <h3 class="text-sm font-black text-slate-800">Deposit & Payment Gateway Info</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Gateway Name</label>
                        <input type="text" name="payment_gateway_name"
                            value="{{ old('payment_gateway_name', $settings['payment_gateway_name'] ?? 'EasyPaisa') }}"
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Account Title / Name</label>
                        <input type="text" name="payment_account_title"
                            value="{{ old('payment_account_title', $settings['payment_account_title'] ?? 'Shama') }}"
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Account / Phone Number</label>
                        <input type="text" name="payment_account_number"
                            value="{{ old('payment_account_number', $settings['payment_account_number'] ?? '03714780429') }}"
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Payment Instructions Note (Shown on
                        Checkout)</label>
                    <input type="text" name="payment_instruction"
                        value="{{ old('payment_instruction', $settings['payment_instruction'] ?? 'After sending payment, upload receipt screenshot to proceed.') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <!-- 2. Ads & Earning Rules -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-rectangle-ad text-indigo-500 text-lg"></i>
                    <h3 class="text-sm font-black text-slate-800">Ads & Task Earning Rates</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Default Earning Per Ad View
                            (Rs)</label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:border-indigo-500">
                            <span class="text-xs font-bold text-slate-400 mr-2">Rs</span>
                            <input type="number" step="0.01" min="0" name="default_ad_rate"
                                value="{{ old('default_ad_rate', $settings['default_ad_rate'] ?? '25.00') }}" required
                                class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Default amount credited to user wallet per
                            completed ad.</span>
                    </div>
                </div>
            </div>

            <!-- 3. Referral Commission Rules -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-users-gear text-cyan-500 text-lg"></i>
                    <h3 class="text-sm font-black text-slate-800">Referral Commission Matrix (%)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Level 1 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_1_percent"
                            value="{{ old('referral_level_1_percent', $settings['referral_level_1_percent'] ?? 10) }}"
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">Direct upline sponsor bonus percentage.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Level 2 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_2_percent"
                            value="{{ old('referral_level_2_percent', $settings['referral_level_2_percent'] ?? 5) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">2nd tier downline bonus.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Level 3 Commission (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="referral_level_3_percent"
                            value="{{ old('referral_level_3_percent', $settings['referral_level_3_percent'] ?? 2) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <span class="text-[10px] text-slate-400 mt-1 block">3rd tier downline bonus.</span>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs shadow-lg transition">
                Save All Platform Settings
            </button>
        </form>
    </div>
@endsection
