@extends('layouts.admin')

@php
    $title = 'Edit User — ' . $user->name;
@endphp

@section('admin_content')
    <div class="space-y-6 max-w-4xl pb-12">

        <!-- Top Breadcrumb & Back Action -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Edit Member: {{ $user->name }}</h1>
                <p class="text-xs text-slate-500">Update account credentials, referral status, packages, and level</p>
            </div>
            <a href="{{ route('admin.users.show', $user->id) }}"
                class="px-3.5 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 font-bold rounded-xl text-xs transition flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Details</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Edit Form -->
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: PERSONAL & CONTACT INFORMATION -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center border-b border-slate-100 pb-3">
                    <i class="fa-regular fa-user mr-2 text-indigo-600"></i> Personal & Account Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <!-- Full Name -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Phone / Mobile -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mobile Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="e.g. 03001234567"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Account Status -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Account Status</label>
                        <select name="status" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active
                                (Normal Access)</option>
                            <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>
                                Pending Verification</option>
                            <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>Banned
                                / Suspended (Cannot Login)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: REFERRAL & LEVEL SETTINGS -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-users mr-2 text-indigo-600"></i> Referral & Level Management
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <!-- Referral Code -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Referral Code</label>
                        <input type="text" name="referral_code" value="{{ old('referral_code', $user->referral_code) }}"
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-mono font-bold text-indigo-600 uppercase focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Direct Upline (Sponsor) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Change Direct Upline (Sponsor)</label>
                        <select name="referred_by"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">None (Root User / No Upline)</option>
                            @foreach ($potentialUplines as $up)
                                <option value="{{ $up->id }}"
                                    {{ old('referred_by', $user->referred_by) == $up->id ? 'selected' : '' }}>
                                    {{ $up->name }} ({{ $up->email }}) — Code: {{ $up->referral_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Referral Level Setting -->
                    <div class="sm:col-span-2 bg-indigo-50/60 p-4 rounded-2xl border border-indigo-100 space-y-2">
                        <label class="block font-bold text-indigo-950">Referral Level Setting</label>
                        <p class="text-[11px] text-slate-500">
                            Choose whether the user's level should follow the auto-calculated approved direct referrals or
                            force-assign a specific tier (Level 0 through Level 8).
                        </p>
                        <select name="custom_level"
                            class="w-full bg-white border border-indigo-200 rounded-xl px-3.5 py-2.5 font-bold text-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value=""
                                {{ old('custom_level', $user->custom_level) === null ? 'selected' : '' }}>
                                ⚡ Auto-Calculate based on referrals (Currently: Level {{ $user->referral_level }})
                            </option>
                            @for ($i = 0; $i <= 8; $i++)
                                <option value="{{ $i }}"
                                    {{ old('custom_level', $user->custom_level) === $i ? 'selected' : '' }}>
                                    🔒 Force Override: Level {{ $i }}
                                    {{ $i == 8 ? '(Canva Earnings Withdrawable)' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Wallet & Balance Adjustment -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <span class="text-indigo-600 text-lg">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                    <h3 class="text-sm font-black text-slate-800">Wallet & Balance Management</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Main / Available Balance -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">
                            Main / Withdrawable Balance (Rs)
                        </label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:border-indigo-500 focus-within:bg-white transition">
                            <span class="text-xs font-bold text-slate-400 mr-2">Rs</span>
                            <input type="number" step="0.01" min="0" name="main_balance"
                                value="{{ old('main_balance', $user->wallet->main_balance ?? 0) }}"
                                class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Current balance available for
                            withdrawals.</span>
                    </div>

                    <!-- Rewards Balance -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">
                            Rewards Balance (Rs)
                        </label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:border-indigo-500 focus-within:bg-white transition">
                            <span class="text-xs font-bold text-slate-400 mr-2">Rs</span>
                            <input type="number" step="0.01" min="0" name="rewards_balance"
                                value="{{ old('rewards_balance', $user->wallet->rewards_balance ?? 0) }}"
                                class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Internal reward and bonus wallet balance.</span>
                    </div>

                    <!-- Ad Task Earnings Total (Optional edit / adjust) -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">
                            Task Earnings (Rs)
                        </label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:border-indigo-500 focus-within:bg-white transition">
                            <span class="text-xs font-bold text-slate-400 mr-2">Rs</span>
                            <input type="number" step="0.01" min="0" name="task_earnings"
                                value="{{ old('task_earnings', $user->wallet->task_earnings ?? 0) }}"
                                class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                    </div>

                    <!-- Referral Earnings Total -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">
                            Referral Earnings (Rs)
                        </label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:border-indigo-500 focus-within:bg-white transition">
                            <span class="text-xs font-bold text-slate-400 mr-2">Rs</span>
                            <input type="number" step="0.01" min="0" name="referral_earnings"
                                value="{{ old('referral_earnings', $user->wallet->referral_earnings ?? 0) }}"
                                class="w-full bg-transparent border-0 text-xs font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PACKAGE MANAGEMENT / MANUAL UPGRADE -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-crown mr-2 text-indigo-600"></i> Package Activation & Manual Upgrade
                </h3>

                <div
                    class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs flex justify-between items-center">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Currently Active Package</span>
                        <strong
                            class="text-sm text-slate-900">{{ $activePackage ? $activePackage->package->name : 'No Active Plan' }}</strong>
                    </div>
                    @if ($activePackage)
                        <div class="text-right">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Expires On</span>
                            <strong class="text-indigo-600">{{ $activePackage->expires_at->format('d M, Y') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                    <!-- Assign / Change Package -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Assign / Change Package</label>
                        <select name="package_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Keep Existing / Do Not Change</option>
                            @foreach ($packages as $pkg)
                                <option value="{{ $pkg->id }}"
                                    {{ $activePackage && $activePackage->package_id == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }} Plan — Rs {{ number_format($pkg->price, 0) }}
                                    ({{ $pkg->daily_task_limit }} Ads/Day)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Validity Duration -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">New Package Duration (Days)</label>
                        <input type="number" name="package_days" value="30" min="1" max="365"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <span class="text-[10px] text-slate-400 block mt-1">Default is 30 days. Starts immediately upon
                            save.</span>
                    </div>
                </div>
            </div>

            <!-- Submit & Save Bar -->
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.users.show', $user->id) }}"
                    class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    Cancel
                </a>

                <button type="submit" style="background: linear-gradient(90deg, #3b49df 0%, #4f46e5 100%);"
                    class="px-8 py-3 text-white font-extrabold text-xs rounded-2xl shadow-lg hover:opacity-95 transition flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save All Changes</span>
                </button>
            </div>

        </form>

    </div>
@endsection
