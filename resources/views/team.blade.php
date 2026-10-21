@extends('layouts.app')

@php
    $headerTitle = 'My Team';
    $currentLvl = auth()->user()->referral_level;
    $lvlInfo = auth()->user()->next_level_info;
    $approvedCount = auth()->user()->approvedDirectReferralsCount();
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-6">

    <!-- Top Total Referrals Overview -->
    <div class="ref-gradient-card rounded-3xl p-5 text-white shadow-lg space-y-2 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <span class="text-xs font-bold text-white/90 uppercase tracking-wider">Direct Referrals Overview</span>
            <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
        </div>
        <div class="flex items-baseline space-x-2">
            <span class="text-3xl font-black">{{ $approvedCount }}</span>
            <span class="text-xs text-white/80">Approved ({{ $referrals->count() }} Total Invited)</span>
        </div>
        <p class="text-[11px] text-white/80 leading-relaxed">
            Approved referrals increase your Referral Level, unlock daily ads after Day 15, and increase your daily task limits.
        </p>
    </div>

    <!-- Referral Level Progression Card -->
    <div class="glass-card-item rounded-3xl p-4 shadow-sm space-y-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-400 to-indigo-600 flex items-center justify-center text-white shadow-sm font-bold text-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Referral Status</span>
                    <h3 class="text-sm font-black text-slate-900 leading-tight">Level {{ $currentLvl }}</h3>
                </div>
            </div>
            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-extrabold border border-indigo-100">
                Daily Limit: {{ auth()->user()->effective_daily_task_limit }} Ads
            </span>
        </div>

        @if($currentLvl < 8)
            <!-- Progress Bar to Next Level -->
            <div class="space-y-1.5 pt-1">
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200/60">
                    <div class="h-2.5 rounded-full bg-gradient-to-r from-cyan-400 via-indigo-500 to-purple-600 transition-all duration-500"
                         style="width: {{ $lvlInfo['progress'] }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-slate-400 font-semibold px-0.5">
                    <span class="text-indigo-600 font-bold">{{ $lvlInfo['needed'] }} more needed for Level {{ $lvlInfo['next_level'] }}</span>
                    <span>Target: {{ $lvlInfo['target'] }} Approved</span>
                </div>
            </div>
        @else
            <div class="p-2.5 bg-emerald-50 rounded-2xl text-[11px] font-bold text-emerald-700 text-center border border-emerald-200 flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-crown text-emerald-600 text-xs"></i>
                <span>Max Tier Reached: Level 8 (Cavaearn Withdrawable)</span>
            </div>
        @endif
    </div>

    <!-- Referral Link Box -->
    <div class="glass-card-item rounded-3xl p-4 shadow-sm space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Invite New Members</span>
            <button type="button" onclick="copyRefLinkTeam()" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center">
                <i class="fa-regular fa-copy mr-1 text-[11px]"></i> Copy
            </button>
        </div>
        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2">
            <input type="text" id="teamRefInput" readonly
                   value="{{ url('/register?ref=' . auth()->user()->referral_code) }}"
                   class="bg-transparent border-0 text-slate-800 text-xs font-mono w-full focus:outline-none p-0 truncate select-all">
        </div>
    </div>

    <!-- Direct Referrals List -->
    <div class="space-y-2.5 pt-1">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">
            Direct Partners ({{ $referrals->count() }})
        </h3>

        <div class="space-y-2">
            @forelse($referrals as $ref)
                @php
                    $activePlan = $ref->activePackage;
                    $isApproved = $ref->packages()->whereIn('status', ['active', 'upgraded'])->exists();
                @endphp
                <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-indigo-500 text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                            {{ strtoupper(substr($ref->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $ref->name }}</h4>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                Joined {{ $ref->created_at->format('d M, Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        @if($activePlan)
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 block">
                                {{ $activePlan->package->name }}
                            </span>
                        @elseif($isApproved)
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 block">
                                Expired
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-semibold bg-slate-100 text-slate-400 block">
                                No Plan
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                    No direct referrals yet. Share your referral link above to start growing your team!
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('scripts')
<script>
    function copyRefLinkTeam() {
        const input = document.getElementById('teamRefInput');
        navigator.clipboard.writeText(input.value).then(() => {
            alert('Referral link copied to clipboard!');
        });
    }
</script>
@endpush
@endsection
