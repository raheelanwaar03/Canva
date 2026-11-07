@extends('layouts.app')

@php
    $headerTitle = 'Top Leaderboard';
@endphp

@section('content')
    <div class="space-y-4 pt-1 pb-16">

        <!-- Header Gradient Banner -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 50%, #d97706 100%);"
            class="rounded-3xl p-5 text-white shadow-xl space-y-2 relative overflow-hidden">
            <div class="flex justify-between items-center relative z-10">
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-100">Performance Ranking</span>
                <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                    <i class="fa-solid fa-trophy text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-black tracking-tight text-white drop-shadow-sm relative z-10">
                Top Earners & Leaders
            </div>
            <p class="text-[11px] text-white/90 font-medium relative z-10">
                Ranked by team referrals and approved withdrawal achievements.
            </p>
        </div>

        <!-- Leaderboard User List -->
        <div class="space-y-2.5">
            @forelse($topUsers as $index => $u)
                @php
                    $rank = $index + 1;
                    $medalClass = match ($rank) {
                        1 => 'bg-amber-400 text-white shadow-amber-300/50',
                        2 => 'bg-slate-300 text-slate-800 shadow-slate-300/50',
                        3 => 'bg-amber-600 text-white shadow-amber-600/50',
                        default => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <div
                    class="glass-action-card rounded-2xl p-3.5 flex items-center justify-between bg-white border border-slate-100 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <!-- Rank Badge -->
                        <div
                            class="w-8 h-8 rounded-xl {{ $medalClass }} flex items-center justify-center font-black text-xs shadow flex-shrink-0">
                            @if ($rank <= 3)
                                <i class="fa-solid fa-crown text-[11px]"></i>
                            @else
                                #{{ $rank }}
                            @endif
                        </div>

                        <!-- User Identity -->
                        <div>
                            <div class="flex items-center space-x-1.5">
                                <h4 class="text-xs font-black text-slate-900 truncate max-w-[140px]">{{ $u->name }}
                                </h4>
                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-indigo-50 text-indigo-700">Lvl
                                    {{ $u->referral_level }}</span>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5 flex items-center space-x-2">
                                <span><i
                                        class="fa-solid fa-users mr-1 text-indigo-500"></i>{{ number_format($u->team_count ?? 0) }}
                                    Team</span>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Badge -->
                    <div class="text-right flex-shrink-0">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Withdrawn</span>
                        <span class="text-xs font-black text-slate-800">
                            Rs {{ number_format($u->total_withdrawn ?? 0, 0) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="glass-action-card rounded-2xl p-6 text-center text-xs text-slate-400 bg-white">
                    No verified leaderboard records available yet.
                </div>
            @endforelse
        </div>

    </div>
@endsection
