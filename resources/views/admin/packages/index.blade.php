@extends('layouts.admin')

@php
    $title = 'Plan Upgrade Settings';
@endphp

@section('admin_content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Plan Upgrade Settings</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Configure pricing tiers, daily task limits, task rates, and referral rewards.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
                {{ count($packages) }} Active Packages
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold space-y-1">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- 4 Packages Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($packages as $pkg)
            @php
                $theme = match(strtolower($pkg->name)) {
                    'basic' => [
                        'badge_bg' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'icon' => 'fa-seedling text-sky-500',
                        'gradient' => 'from-sky-500 to-indigo-600',
                    ],
                    'gold' => [
                        'badge_bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'icon' => 'fa-crown text-amber-500',
                        'gradient' => 'from-amber-400 to-orange-500',
                    ],
                    'standard' => [
                        'badge_bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'icon' => 'fa-gem text-indigo-500',
                        'gradient' => 'from-indigo-600 to-purple-600',
                    ],
                    'premium' => [
                        'badge_bg' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'icon' => 'fa-wand-magic-sparkles text-purple-500',
                        'gradient' => 'from-fuchsia-600 to-indigo-600',
                    ],
                    default => [
                        'badge_bg' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'icon' => 'fa-box text-slate-500',
                        'gradient' => 'from-slate-700 to-slate-900',
                    ],
                };
            @endphp

            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 space-y-5 relative overflow-hidden flex flex-col justify-between">
                
                <!-- Package Top Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-base">
                            <i class="fa-solid {{ $theme['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800 tracking-tight">{{ $pkg->name }}</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Package ID #{{ $pkg->id }}</span>
                        </div>
                    </div>

                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-black border {{ $theme['badge_bg'] }}">
                        Rs {{ number_format($pkg->price) }}
                    </span>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('admin.packages.update', $pkg->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3.5">
                        <!-- Price -->
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Price (PKR)</label>
                            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $pkg->price) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl px-4 py-2.5 text-xs font-black text-slate-800 outline-none transition">
                        </div>

                        <!-- Daily Ads Limit -->
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Daily Ads Limit</label>
                            <input type="number" min="1" name="daily_task_limit" value="{{ old('daily_task_limit', $pkg->daily_task_limit) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl px-4 py-2.5 text-xs font-black text-slate-800 outline-none transition">
                        </div>

                        <!-- Reward Per Task -->
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Reward Per Ad (PKR)</label>
                            <input type="number" step="0.01" min="0" name="reward_per_task" value="{{ old('reward_per_task', $pkg->reward_per_task ?? 7.00) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl px-4 py-2.5 text-xs font-black text-slate-800 outline-none transition">
                        </div>

                        <!-- Referral Commission -->
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Referral Bonus (PKR)</label>
                            <input type="number" step="0.01" min="0" name="referral_commission" value="{{ old('referral_commission', $pkg->referral_commission) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl px-4 py-2.5 text-xs font-black text-slate-800 outline-none transition">
                        </div>
                    </div>

                    <!-- Validity Duration -->
                    <div class="pt-1">
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Validity (Days)</label>
                        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $pkg->duration_days ?? 30) }}" required
                               class="w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl px-4 py-2.5 text-xs font-black text-slate-800 outline-none transition">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-2 py-3 px-4 rounded-2xl text-xs font-black text-white bg-gradient-to-r {{ $theme['gradient'] }} hover:opacity-95 shadow-md shadow-indigo-500/10 active:scale-[0.98] transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Save {{ $pkg->name }} Settings</span>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

</div>
@endsection