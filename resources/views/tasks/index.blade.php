@extends('layouts.app')

@php
    $headerTitle = 'Daily Tasks';
@endphp

@section('content')
    <div class="space-y-4 pt-1">

        @if (session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Task Eligibility / Lock Banner -->
        @if (!$eligibility['unlocked'])
            <div class="bg-gradient-to-r from-rose-500 to-pink-600 rounded-2xl p-4 text-white shadow-lg space-y-1">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-lock text-base"></i>
                    <h3 class="font-extrabold text-sm">Tasks Locked</h3>
                </div>
                <p class="text-[11px] text-white/90 leading-relaxed">{{ $eligibility['reason'] }}</p>
                @if (!$activeSub)
                    <a href="{{ route('packages.index') }}"
                        class="inline-block mt-2 px-3 py-1 bg-white text-rose-600 rounded-xl text-xs font-bold shadow-sm">
                        Choose Plan &rarr;
                    </a>
                @endif
            </div>
        @else
            <!-- Stats Summary Card -->
            <div class="ref-gradient-card rounded-2xl p-4 text-white shadow-md">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-indigo-600/90">Daily Tasks Progress</span>
                    <span class="text-[10px] text-indigo-600/90 px-2.5 py-0.5 rounded-full font-bold">
                        {{ $eligibility['days_left'] }} Days Left in Window
                    </span>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <div class="text-2xl text-indigo-600 font-black">{{ $completedTodayCount }} / {{ $dailyLimit }}</div>
                        <div class="text-[10px] text-indigo-600/80">Completed Today</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-indigo-600 font-bold">Rs {{ number_format($rewardPerTask, 0) }}</div>
                        <div class="text-[10px] text-indigo-600/80">Per Ad Reward</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ad Tasks List -->
        <div class="space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Available Ads</h3>

            @forelse($tasks as $t)
                @php
                    $isDone = in_array($t->id, $completedTaskIds);
                    $canWatch = $eligibility['unlocked'] && !$isDone && $completedTodayCount < $dailyLimit;
                @endphp

                <div
                    class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between {{ $isDone ? 'opacity-60 bg-slate-100' : '' }}">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-indigo-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            @if ($isDone)
                                <i class="fa-solid fa-check text-base"></i>
                            @else
                                <i class="fa-solid fa-play text-xs ml-0.5"></i>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $t->title }}</h4>
                            <div class="text-[10px] text-slate-400 mt-0.5 flex items-center space-x-2">
                                <span><i class="fa-regular fa-clock mr-1"></i>{{ $t->duration_seconds }}s</span>
                                <span>•</span>
                                <span class="text-indigo-600 font-bold">+Rs {{ number_format($rewardPerTask, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        @if ($isDone)
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-200 text-slate-500">
                                Completed
                            </span>
                        @elseif($canWatch)
                            <a href="{{ route('tasks.view', $t->id) }}"
                                style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                                class="px-3 py-1.5 rounded-xl text-white text-xs font-bold shadow-sm hover:opacity-90 transition">
                                Watch Ad
                            </a>
                        @else
                            <button disabled
                                class="px-3 py-1.5 rounded-xl text-slate-400 bg-slate-100 text-xs font-bold cursor-not-allowed">
                                Locked
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                    No advertising tasks available right now.
                </div>
            @endforelse
        </div>

    </div>
@endsection
