@extends('layouts.app')

@php
    $headerTitle = 'My Wallet';
@endphp

@section('content')
<div class="space-y-4 pt-1">

    <!-- Canva ADS Logo Circle -->
    <div class="flex flex-col items-center">
        <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-lg border-2 border-white/60">
            <span class="font-extrabold text-white text-lg tracking-wider italic">Canva</span>
            <span class="font-black text-white text-[11px] tracking-widest -mt-1">ADS</span>
        </div>
        <h2 class="text-xl font-black text-slate-800 mt-2">Wallet</h2>
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- 6 Balance & Earnings Cards (Sleek Glassmorphic Palette) -->
    <div class="grid grid-cols-3 gap-2.5">
        <!-- 1. Current Balance -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($wallet->withdrawable_balance, 0) }}</div>
            <div class="text-[9px] text-indigo-100 font-semibold mt-1">Current Balance</div>
        </div>

        <!-- 2. Referral Earnings -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($wallet->referral_earnings, 0) }}</div>
            <div class="text-[9px] text-indigo-100 font-semibold mt-1">Referral Earning</div>
        </div>

        <!-- 3. Canva Task Earnings -->
        <div class="bg-gradient-to-br from-purple-600 to-fuchsia-600 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($wallet->task_earnings, 0) }}</div>
            <div class="text-[9px] text-fuchsia-100 font-semibold mt-1">Canva Earning</div>
        </div>

        <!-- 4. Approved Withdraw -->
        <div class="bg-gradient-to-br from-slate-700 to-slate-800 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($approvedWithdrawTotal, 0) }}</div>
            <div class="text-[9px] text-slate-300 font-semibold mt-1">Approved Withdraw</div>
        </div>

        <!-- 5. Pending Withdraw -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($pendingWithdrawTotal, 0) }}</div>
            <div class="text-[9px] text-amber-100 font-semibold mt-1">Pending Withdraw</div>
        </div>

        <!-- 6. Rejected Withdraw -->
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-2xl p-3 shadow-sm text-center flex flex-col justify-center">
            <div class="text-xs font-black truncate">Rs {{ number_format($rejectedWithdrawTotal, 0) }}</div>
            <div class="text-[9px] text-rose-100 font-semibold mt-1">Rejected Withdraw</div>
        </div>
    </div>

    <!-- Quick Withdraw CTA -->
    <a href="{{ route('withdraw.create') }}"
       style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
       class="block text-center text-white font-bold py-3 px-4 rounded-2xl text-xs shadow-md hover:opacity-95 transition">
        Request New Withdrawal &rarr;
    </a>

    <!-- Withdraw History Section -->
    <div class="space-y-2.5 pt-1">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1 text-center">Withdraw History</h3>

        <div class="space-y-2.5">
            @forelse($withdrawals as $w)
                <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-extrabold text-slate-800">
                            Rs {{ number_format($w->amount, 2) }}
                        </div>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[9px] font-bold
                            {{ $w->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($w->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                            {{ ucfirst($w->status) }}
                        </span>
                    </div>
                    <div class="text-right text-[10px] text-slate-400">
                        <div class="font-medium text-slate-600">{{ $w->method }}</div>
                        <div class="flex items-center justify-end mt-0.5">
                            <i class="fa-regular fa-calendar mr-1 text-[9px]"></i>
                            {{ $w->created_at->format('Y-m-d h:i A') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                    No withdrawal history yet.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
