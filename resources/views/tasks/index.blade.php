@extends('layouts.app')

@php
    $headerTitle = 'Daily Tasks';
@endphp

@section('content')
<div class="space-y-3 pt-1 pb-6">

    @if(session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Lock Alert Banner (if applicable) -->
    @if(!$eligibility['unlocked'])
        <div class="bg-gradient-to-r from-rose-500 to-pink-600 rounded-2xl p-4 text-white shadow-lg space-y-1">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-lock text-base"></i>
                <h3 class="font-extrabold text-sm">Tasks Locked</h3>
            </div>
            <p class="text-[11px] text-white/90 leading-relaxed">{{ $eligibility['reason'] }}</p>
            @if(!$activeSub)
                <a href="{{ route('packages.index') }}" class="inline-block mt-2 px-3 py-1 bg-white text-rose-600 rounded-xl text-xs font-bold shadow-sm">
                    Choose Plan &rarr;
                </a>
            @endif
        </div>
    @else
        <!-- Daily Progress Counter Bar -->
        <div class="flex justify-between items-center px-1 text-xs">
            <span class="font-bold text-slate-700">Today: <strong class="text-indigo-600">{{ $completedTodayCount }} / {{ $dailyLimit }} Completed</strong></span>
            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                Level {{ auth()->user()->referral_level }}
            </span>
        </div>
    @endif

    <!-- TASKS LIST (Exact Match to task.jpeg: Rounded Card, Illustration, Title, Gradient Play Button) -->
    <div class="space-y-3">
        @php
            // Custom high-definition 3D vector illustrations mapped to task names or indexes
            $illustrations = [
                // 1. Canva Picture (Laptop mockup)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gLap" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#38bdf8"/><stop offset="100%" stop-color="#6366f1"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f0fdf4"/>
                    <rect x="25" y="15" width="70" height="44" rx="6" fill="#1e293b"/>
                    <rect x="29" y="19" width="62" height="36" rx="3" fill="url(#gLap)"/>
                    <text x="60" y="42" fill="#ffffff" font-family="Dancing Script, cursive" font-size="16" font-weight="bold" text-anchor="middle">Canva</text>
                    <rect x="18" y="58" width="84" height="5" rx="2.5" fill="#94a3b8"/>
                </svg>',

                // 2. Canva Photo (Landscape Picture Cards)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gPho" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#60a5fa"/><stop offset="100%" stop-color="#3b82f6"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#eff6ff"/>
                    <rect x="36" y="14" width="50" height="50" rx="10" fill="#bfdbfe" transform="rotate(10 61 39)"/>
                    <rect x="30" y="15" width="50" height="50" rx="10" fill="url(#gPho)"/>
                    <circle cx="44" cy="27" r="4" fill="#ffffff"/>
                    <polygon points="34,56 46,38 56,50 64,42 76,56" fill="#ffffff"/>
                </svg>',

                // 3. Canva Logo (3D C Token)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gLogo" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#9333ea"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f5f3ff"/>
                    <rect x="35" y="15" width="50" height="50" rx="14" fill="url(#gLogo)"/>
                    <text x="60" y="50" fill="#ffffff" font-family="Dancing Script, cursive" font-size="32" font-weight="bold" text-anchor="middle">C</text>
                </svg>',

                // 4. Canva Text (3D Typography Box)
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

                // 5. Canva Design (Color Palette Swatch)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <rect width="120" height="80" rx="16" fill="#faf5ff"/>
                    <rect x="30" y="18" width="16" height="44" rx="4" fill="#0284c7"/>
                    <rect x="48" y="18" width="16" height="44" rx="4" fill="#6366f1"/>
                    <rect x="66" y="18" width="16" height="44" rx="4" fill="#ec4899"/>
                    <circle cx="38" cy="26" r="3" fill="#ffffff"/>
                    <circle cx="56" cy="26" r="3" fill="#ffffff"/>
                    <circle cx="74" cy="26" r="3" fill="#ffffff"/>
                </svg>',

                // 6. Canva Elements (3D Geometric Shapes)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gElem" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#4f46e5"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#f0fdfa"/>
                    <rect x="30" y="22" width="28" height="28" rx="6" fill="url(#gElem)"/>
                    <circle cx="74" cy="30" r="12" fill="#c084fc"/>
                    <polygon points="74,44 63,60 85,60" fill="#38bdf8"/>
                </svg>',

                // 7. Canva Video (Video Player Window)
                '<svg viewBox="0 0 120 80" class="w-full h-full object-cover rounded-2xl">
                    <defs><linearGradient id="gVid" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00c6ff"/><stop offset="100%" stop-color="#7c3aed"/></linearGradient></defs>
                    <rect width="120" height="80" rx="16" fill="#eef2ff"/>
                    <rect x="25" y="18" width="70" height="44" rx="10" fill="url(#gVid)"/>
                    <polygon points="56,33 69,40 56,47" fill="#ffffff"/>
                    <line x1="33" y1="52" x2="87" y2="52" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" opacity="0.6"/>
                    <line x1="33" y1="52" x2="52" y2="52" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                </svg>',
            ];

            // Default titles from user.jpeg if database titles are generic
            $defaultTitles = [
                'Canva Picture',
                'Canva Photo',
                'Canva Logo',
                'Canva Text',
                'Canva Design',
                'Canva Elements',
                'Canva Video'
            ];
        @endphp

        @forelse($tasks as $index => $t)
            @php
                $isDone = in_array($t->id, $completedTaskIds);
                $canWatch = $eligibility['unlocked'] && !$isDone && ($completedTodayCount < $dailyLimit);
                $svgIllustration = $illustrations[$index % count($illustrations)];
                $displayTitle = $defaultTitles[$index % count($defaultTitles)] ?? $t->title;
            @endphp

            <!-- Task Row Card (Exact Match to task.jpeg) -->
            <div class="bg-white rounded-3xl p-3 flex items-center justify-between shadow-sm border border-slate-100 hover:shadow-md transition {{ $isDone ? 'opacity-50 bg-slate-50' : '' }}">

                <!-- Left: 3D Illustration Mockup -->
                <div class="w-24 h-16 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm border border-slate-100 flex items-center justify-center bg-slate-50">
                    {!! $svgIllustration !!}
                </div>

                <!-- Center: Task Title -->
                <div class="flex-1 px-3.5">
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug">{{ $displayTitle }}</h3>
                    <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Verified Partner Ad</span>
                </div>

                <!-- Right: Circular Blue-Purple Gradient Play Button (Exact match to task.jpeg) -->
                <div>
                    @if($isDone)
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm shadow-inner" title="Completed">
                            <i class="fa-solid fa-check text-emerald-500 text-base"></i>
                        </div>
                    @elseif($canWatch)
                        <a href="{{ route('tasks.view', $t->id) }}"
                           style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 50%, #7c3aed 100%);"
                           class="w-12 h-12 rounded-full text-white flex items-center justify-center shadow-md hover:scale-105 active:scale-95 transition-transform"
                           title="Watch Ad">
                            <i class="fa-solid fa-play text-sm ml-0.5"></i>
                        </a>
                    @else
                        <button disabled class="w-12 h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center text-sm cursor-not-allowed">
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
@endsection
