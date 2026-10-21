@extends('layouts.app')

@php
    $headerTitle = 'My Rewards';
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-6">

    <!-- Top Total Rewards Card (High Contrast Purple-Indigo-Cyan Gradient) -->
    <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #06b6d4 100%);"
         class="rounded-3xl p-5 text-white shadow-xl space-y-2 relative overflow-hidden">

        <!-- Subtle Glow Overlay -->
        <div class="absolute -top-10 -right-10 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

        <div class="flex justify-between items-center relative z-10">
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-indigo-100">Total Earned Rewards</span>
            <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-gift text-sm"></i>
            </div>
        </div>

        <div class="text-3xl font-black tracking-tight text-white drop-shadow-sm relative z-10">
            Rs {{ number_format($wallet->task_earnings + $wallet->referral_earnings, 2) }}
        </div>

        <p class="text-[11px] text-white/90 font-medium leading-relaxed relative z-10">
            Combined earnings from verified ads and referral commissions.
        </p>
    </div>

    <!-- 2 Metric Breakdown Cards -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Task Rewards Card -->
        <div class="glass-action-card rounded-2xl p-4 space-y-1 bg-white">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Ad Tasks</span>
            </div>
            <div class="text-base font-black text-slate-900 mt-1">
                Rs {{ number_format($wallet->task_earnings, 2) }}
            </div>
            <span class="text-[10px] text-slate-500 font-semibold block">
                {{ $totalAdsCompleted }} Ads Verified
            </span>
        </div>

        <!-- Referral Rewards Card -->
        <div class="glass-action-card rounded-2xl p-4 space-y-1 bg-white">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Referrals</span>
            </div>
            <div class="text-base font-black text-slate-900 mt-1">
                Rs {{ number_format($wallet->referral_earnings, 2) }}
            </div>
            <span class="text-[10px] text-slate-500 font-semibold block">
                {{ auth()->user()->referrals()->count() }} Partners Invited
            </span>
        </div>
    </div>

    <!-- Live Rewards History Feed -->
    <div class="space-y-2.5 pt-1">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-600 px-1">Rewards History</h3>

        <div class="space-y-2">
            @forelse($rewardTransactions as $tx)
                <div class="glass-action-card rounded-2xl p-3.5 flex items-center justify-between bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm {{ $tx->type === 'referral_bonus' ? 'bg-purple-100 text-purple-700' : 'bg-cyan-100 text-cyan-700' }}">
                            <i class="{{ $tx->type === 'referral_bonus' ? 'fa-solid fa-users' : 'fa-solid fa-bullhorn' }}"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $tx->description }}</h4>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                {{ $tx->created_at->format('d M, Y • h:i A') }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-xs font-black text-emerald-600">
                            +Rs {{ number_format($tx->amount, 2) }}
                        </div>
                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Credited
                        </span>
                    </div>
                </div>
            @empty
                <div class="glass-action-card rounded-2xl p-6 text-center text-xs text-slate-400 bg-white">
                    No reward transactions recorded yet. Complete daily ad tasks or invite friends to earn!
                </div>
            @endforelse
        </div>

        @if($rewardTransactions->hasPages())
            <div class="pt-2">
                {{ $rewardTransactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
