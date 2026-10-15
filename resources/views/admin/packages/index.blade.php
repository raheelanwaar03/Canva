@extends('layouts.app')

@php
    $headerTitle = 'Package Settings';
@endphp

@section('content')
    <div class="space-y-4 pt-1">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Membership Packages</h2>
            <p class="text-[11px] text-slate-500">Configure task limits, rewards & referral bonuses</p>
        </div>

        <!-- Admin Sub-Nav -->
        <div class="grid grid-cols-4 gap-1.5 text-center text-[10px] font-bold">
            <a href="{{ route('admin.payments.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payments</a>
            <a href="{{ route('admin.withdrawals.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Withdrawals</a>
            <a href="{{ route('admin.packages.index') }}"
                class="p-2 rounded-xl bg-indigo-600 text-white shadow-md">Packages</a>
            <a href="{{ route('admin.tasks.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Ads</a>
        </div>

        @if (session('success'))
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-3.5">
            @foreach ($packages as $pkg)
                <div class="glass-card-item rounded-3xl p-4 space-y-3 shadow-md">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <h3 class="text-base font-extrabold text-slate-900">{{ $pkg->name }}</h3>
                        <span
                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                            Rs {{ number_format($pkg->price, 0) }}
                        </span>
                    </div>

                    <form action="{{ route('admin.packages.update', $pkg->id) }}" method="POST"
                        class="space-y-2.5 text-xs font-semibold">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Price -->
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">Price (PKR)</label>
                                <input type="number" step="1" name="price"
                                    value="{{ old('price', (int) $pkg->price) }}" required
                                    class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>

                            <!-- Daily Task Limit -->
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">Daily Ads Limit</label>
                                <input type="number" step="1" name="daily_task_limit"
                                    value="{{ old('daily_task_limit', $pkg->daily_task_limit) }}" required
                                    class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>

                            <!-- Reward Per Task -->
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">Reward Per Ad (PKR)</label>
                                <input type="number" step="0.5" name="reward_per_task"
                                    value="{{ old('reward_per_task', $pkg->reward_per_task) }}" required
                                    class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>

                            <!-- Referral Commission -->
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">Referral Bonus (PKR)</label>
                                <input type="number" step="1" name="referral_commission"
                                    value="{{ old('referral_commission', $pkg->referral_commission) }}" required
                                    class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                        </div>

                        <!-- Duration Days (hidden or fixed 30) -->
                        <input type="hidden" name="duration_days" value="30">

                        <button type="submit"
                            style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                            class="w-full text-white font-bold py-2 rounded-xl text-xs shadow-sm hover:opacity-95 transition mt-2">
                            Save {{ $pkg->name }} Settings
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
