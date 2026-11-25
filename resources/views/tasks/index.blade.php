@extends('layouts.app')

@php
    $headerTitle = 'Daily Tasks';
@endphp

@section('content')
    <div class="space-y-3 pt-1 pb-6">

        @if ($isPlanExpired ?? false)
            @if ($isFirstTaskBonus ?? false)
                <div class="p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow-md space-y-1">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-gift text-amber-300 text-sm"></i>
                        <h4 class="text-xs font-black uppercase tracking-wide">New User Welcome Bonus Active</h4>
                    </div>
                    <p class="text-[11px] text-white/90 leading-relaxed">
                        Complete and claim your first task to receive <strong>Rs. 50 Joining Bonus + Rs. 5 Task Earning (Rs.
                            55 total)</strong> directly in your Main Wallet!
                    </p>
                </div>
            @endif

            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <h4 class="text-xs font-black text-rose-700">Your Package Has Expired</h4>
                    <p class="text-[11px] text-rose-600 font-medium">Daily task earnings and plan benefits are paused. Renew
                        or upgrade your plan to resume earning.</p>
                </div>
                <a href="{{ route('packages.index') }}"
                    class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[11px] font-black whitespace-nowrap shadow-sm transition">
                    Renew Plan →
                </a>
            </div>
        @endif

        @if (session('success'))
            <div
                class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Lock Alert Banner (if applicable) -->
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
                        Choose Plan →
                    </a>
                @endif
            </div>
        @endif

        <!-- TASKS LIST -->
        <div class="space-y-3">
            @php
                $illustrations = [
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gLap" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#38bdf8"/><stop offset="100%" stop-color="#6366f1"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f0fdf4"/>
                    <rect x="25" y="15" width="70" height="44" rx="6" fill="#1e293b"/>
                    <rect x="29" y="19" width="62" height="36" rx="3" fill="url(#gLap)"/>
                    <text x="60" y="42" fill="#ffffff" font-family="Dancing Script, cursive" font-size="16" font-weight="bold" text-anchor="middle">Canva</text>
                    <rect x="18" y="58" width="84" height="5" rx="2.5" fill="#94a3b8"/>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gPho" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#60a5fa"/><stop offset="100%" stop-color="#3b82f6"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#eff6ff"/>
                    <rect x="36" y="14" width="50" height="50" rx="10" fill="#bfdbfe" transform="rotate(10 61 39)"/>
                    <rect x="30" y="15" width="50" height="50" rx="10" fill="url(#gPho)"/>
                    <circle cx="44" cy="27" r="4" fill="#ffffff"/>
                    <polygon points="34,56 46,38 56,50 64,42 76,56" fill="#ffffff"/>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gLogo" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#9333ea"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f5f3ff"/>
                    <rect x="35" y="15" width="50" height="50" rx="14" fill="url(#gLogo)"/>
                    <text x="60" y="50" fill="#ffffff" font-family="Dancing Script, cursive" font-size="32" font-weight="bold" text-anchor="middle">C</text>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gTxt" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#38bdf8"/><stop offset="100%" stop-color="#0284c7"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f0f9ff"/>
                    <rect x="30" y="15" width="54" height="50" rx="10" fill="#ffffff" stroke="#bae6fd" stroke-width="2"/>
                    <rect x="28" y="13" width="6" height="6" fill="#0284c7" rx="1"/>
                    <rect x="80" y="13" width="6" height="6" fill="#0284c7" rx="1"/>
                    <rect x="28" y="61" width="6" height="6" fill="#0284c7" rx="1"/>
                    <rect x="80" y="61" width="6" height="6" fill="#0284c7" rx="1"/>
                    <text x="57" y="49" fill="url(#gTxt)" font-family="Arial, sans-serif" font-size="28" font-weight="900" text-anchor="middle">T</text>
                    <polygon points="72,46 84,58 78,60 76,68 70,54" fill="#6366f1"/>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <rect width="120" height="80" rx="16" fill="#faf5ff"/>
                    <rect x="30" y="18" width="16" height="44" rx="4" fill="#0284c7"/>
                    <rect x="48" y="18" width="16" height="44" rx="4" fill="#6366f1"/>
                    <rect x="66" y="18" width="16" height="44" rx="4" fill="#ec4899"/>
                    <circle cx="38" cy="26" r="3" fill="#ffffff"/>
                    <circle cx="56" cy="26" r="3" fill="#ffffff"/>
                    <circle cx="74" cy="26" r="3" fill="#ffffff"/>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gElem" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#4f46e5"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f0fdfa"/>
                    <rect x="30" y="22" width="28" height="28" rx="6" fill="url(#gElem)"/>
                    <circle cx="74" cy="30" r="12" fill="#c084fc"/>
                    <polygon points="74,44 63,60 85,60" fill="#38bdf8"/>
                </svg>',
                    '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gVid" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#7c3aed"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#eef2ff"/>
                    <rect x="25" y="18" width="70" height="44" rx="10" fill="url(#gVid)"/>
                    <polygon points="56,33 69,40 56,47" fill="#ffffff"/>
                    <line x1="33" y1="52" x2="87" y2="52" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" opacity="0.6"/>
                    <line x1="33" y1="52" x2="52" y2="52" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                </svg>',
                ];

                $defaultTitles = [
                    'Canva Picture',
                    'Canva Photo',
                    'Canva Logo',
                    'Canva Text',
                    'Canva Design',
                    'Canva Elements',
                    'Canva Video',
                ];
            @endphp

            @forelse($tasks as $index => $t)
                @php
                    $isDone = in_array($t->id, $completedTaskIds);
                    $canWatch =
                        !($isPlanExpired ?? false) &&
                        $eligibility['unlocked'] &&
                        !$isDone &&
                        $completedTodayCount < $dailyLimit;
                    $svgIllustration = $illustrations[$index % count($illustrations)];
                    $displayTitle = $t->title ?: $defaultTitles[$index % count($defaultTitles)] ?? 'Partner Task';
                    $taskUrl = $t->url ?? ($t->link ?? ($t->ad_url ?? '#'));
                @endphp

                <div
                    class="bg-white rounded-3xl p-3 flex items-center justify-between shadow-sm border border-slate-100 hover:shadow-md transition {{ $isDone ? 'opacity-50 bg-slate-50' : '' }}">

                    <!-- Left: 3D Illustration Mockup -->
                    <div
                        class="w-24 h-16 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm border border-slate-100 flex items-center justify-center bg-slate-50">
                        {!! $svgIllustration !!}
                    </div>

                    <!-- Center: Task Title & Status -->
                    <div class="flex-1 px-3.5">
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">{{ $displayTitle }}</h3>
                        <span id="task-status-{{ $t->id }}"
                            class="text-[10px] text-slate-400 font-semibold block mt-0.5">
                            @if ($isDone)
                                Completed • Rs {{ number_format($rewardPerTask, 0) }} Earned
                            @elseif($canWatch)
                                Click play & watch 15s • Rs {{ number_format($rewardPerTask, 0) }}
                            @else
                                Verified Partner Ad
                            @endif
                        </span>
                    </div>

                    <!-- Right: Action Area (Play -> 15s Countdown -> Claim Button) -->
                    <div class="flex items-center">
                        @if ($isDone)
                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm shadow-inner"
                                title="Completed">
                                <i class="fa-solid fa-check text-emerald-500 text-base"></i>
                            </div>
                        @elseif($canWatch)
                            <!-- 1. Initial Play Button (Opens link in new tab & starts 15s timer) -->
                            <a href="{{ $taskUrl }}" target="_blank" rel="noopener noreferrer"
                                id="play-btn-{{ $t->id }}"
                                onclick="startTaskTimer({{ $t->id }}, '{{ route('tasks.start', $t->id) }}', {{ $isFirstTaskBonus ?? false ? 55 : $rewardPerTask }})"
                                style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 50%, #7c3aed 100%);"
                                class="w-12 h-12 rounded-full text-white flex items-center justify-center shadow-md hover:scale-105 active:scale-95 transition-transform"
                                title="Open Task Link">
                                <i class="fa-solid fa-play text-sm ml-0.5"></i>
                            </a>

                            <!-- 2. 15-Second Countdown Badge (Hidden initially) -->
                            <button type="button" disabled id="timer-btn-{{ $t->id }}"
                                class="hidden px-3.5 py-2.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 font-black text-xs whitespace-nowrap cursor-wait">
                                <i class="fa-solid fa-clock fa-spin mr-1"></i> <span
                                    id="timer-sec-{{ $t->id }}">15</span>s
                            </button>

                            <!-- 3. Claim Reward Form (Revealed after 15s finishes) -->
                            <form action="{{ route('tasks.complete', $t->id) }}" method="POST"
                                id="claim-form-{{ $t->id }}" class="hidden">
                                @csrf
                                <button type="submit"
                                    class="px-3.5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-md active:scale-95 transition whitespace-nowrap">
                                    @if ($isFirstTaskBonus ?? false)
                                        Claim Rs 55 (Rs 5 + Rs 50 Bonus)
                                    @else
                                        Claim Rs {{ number_format($rewardPerTask, 0) }}
                                    @endif
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="w-12 h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center text-sm cursor-not-allowed">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-8 text-center text-xs text-slate-400 shadow-sm">
                    No tasks available right now.
                </div>
            @endforelse
        </div>

    </div>

    <script>
        const activeTimers = {};

        function startTaskTimer(taskId, startUrl, rewardAmount) {
            // Prevent duplicate intervals if clicked twice
            if (activeTimers[taskId]) return;

            // 1. Notify backend so server records start timestamp in session
            fetch(startUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).catch(err => console.error('Timer start error:', err));

            // 2. Switch Play button -> Countdown button
            const playBtn = document.getElementById(`play-btn-${taskId}`);
            const timerBtn = document.getElementById(`timer-btn-${taskId}`);
            const timerSec = document.getElementById(`timer-sec-${taskId}`);
            const claimForm = document.getElementById(`claim-form-${taskId}`);
            const statusText = document.getElementById(`task-status-${taskId}`);

            if (playBtn) playBtn.classList.add('hidden');
            if (timerBtn) timerBtn.classList.remove('hidden');
            if (statusText) {
                statusText.textContent = 'Watching task... Please wait 15 seconds';
                statusText.classList.replace('text-slate-400', 'text-amber-600');
            }

            let secondsLeft = 15;
            activeTimers[taskId] = setInterval(() => {
                secondsLeft--;
                if (timerSec) timerSec.textContent = secondsLeft;

                if (secondsLeft <= 0) {
                    clearInterval(activeTimers[taskId]);
                    delete activeTimers[taskId];

                    // 3. Switch Countdown -> Claim button
                    if (timerBtn) timerBtn.classList.add('hidden');
                    if (claimForm) claimForm.classList.remove('hidden');
                    if (statusText) {
                        statusText.textContent = `Ready! Claim Rs ${rewardAmount} to withdrawable balance`;
                        statusText.classList.replace('text-amber-600', 'text-emerald-600');
                    }
                }
            }, 1000);
        }
    </script>
@endsection
