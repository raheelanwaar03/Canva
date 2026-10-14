@extends('layouts.app')

@php
    $headerTitle = 'My Team';
@endphp

@section('content')
    <div class="space-y-4 pt-1">

        <!-- Team Overview Card -->
        <div class="ref-gradient-card rounded-2xl p-4 text-white shadow-md">
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold text-white/90">Total Direct Referrals</span>
                <span class="text-xs bg-white/20 px-2.5 py-0.5 rounded-full font-extrabold">
                    {{ $referrals->count() }} Members
                </span>
            </div>
            <p class="text-[11px] text-white/80">Active referrals unlock your daily tasks after Day 15.</p>
        </div>

        <!-- Referral List -->
        <div class="space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Direct Partners</h3>

            @forelse($referrals as $ref)
                @php
                    $activePlan = $ref->activePackage;
                @endphp
                <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-indigo-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ strtoupper(substr($ref->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $ref->name }}</h4>
                            <div class="text-[10px] text-slate-400 mt-0.5">Joined {{ $ref->created_at->format('d M, Y') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        @if ($activePlan)
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                {{ $activePlan->package->name }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-semibold bg-slate-100 text-slate-400">
                                No Plan
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                    No direct referrals yet. Share your referral link to invite partners!
                </div>
            @endforelse
        </div>

    </div>
@endsection
