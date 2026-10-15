@extends('layouts.app')

@php
    $headerTitle = 'My Rewards';
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-6">

    <!-- Top Total Rewards Card -->
    <div class="ref-gradient-card rounded-3xl p-5 text-white shadow-lg space-y-2 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <span class="text-xs font-bold text-white/90 uppercase tracking-wider">Total Earned Rewards</span>
            <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                <i class="fa-solid fa-gift text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-black">
            Rs {{ number_format($wallet->task_earnings + $wallet->referral_earnings, 2) }}
        </div>
        <p class="text-[11px] text-white/80">Combined earnings from verified ads and referral commissions.</p>
    </div>

    <!-- 2 Metric Breakdown Cards -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Task Rewards Card -->
        <div class="glass-card-item rounded-2xl p-3.5 space-y-1">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Ad Tasks</span>
            </div>
            <div class="text-sm font-black text-slate-800">
                Rs {{ number_format($wallet->task_earnings, 2) }}
            </div>
            <span class="text-[10px] text-slate-500 font-medium block">
                {{ $totalAdsCompleted }} Ads Verified
            </span>
        </div>

        <!-- Referral Rewards Card -->
        <div class="glass-card-item rounded-2xl p-3.5 space-y-1">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Referrals</span>
            </div>
            <div class="text-sm font-black text-slate-800">
                Rs {{ number_format($wallet->referral_earnings, 2) }}
            </div>
            <span class="text-[10px] text-slate-500 font-medium block">
                {{ auth()->user()->referrals()->count() }} Partners Invited
            </span>
        </div>
    </div>

    <!-- Live Rewards History Feed -->
    <div class="space-y-2.5 pt-1">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Rewards History</h3>

        <div class="space-y-2">
            @forelse($rewardTransactions as $tx)
                <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm {{ $tx->type === 'referral_bonus' ? 'bg-purple-50 text-purple-600' : 'bg-cyan-50 text-cyan-600' }}">
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
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            Credited
                        </span>
                    </div>
                </div>
            @empty
                <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
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
