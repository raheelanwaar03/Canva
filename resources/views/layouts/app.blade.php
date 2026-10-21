<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CanvaEdits — Digital Advertising & Earnings Platform</title>

    <!-- CanvaEdits Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=4">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=4">

    <!-- Fonts: Plus Jakarta Sans & Custom Script Font for Canva -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef2fe;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* High-contrast gradient cards for Rewards, Team, Tasks, and Transfers */
        .ref-gradient-card,
        .hero-gradient-card {
            background: linear-gradient(135deg, #6366f1 0%, #7c3aed 45%, #06b6d4 100%) !important;
            box-shadow: 0 14px 30px -6px rgba(99, 102, 241, 0.35);
            color: #ffffff !important;
        }

        .purple-blue-gradient {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%) !important;
            color: #ffffff !important;
        }

        .canva-brand-script {
            font-family: 'Dancing Script', cursive;
        }

        .ambient-wave-canvas {
            background:
                radial-gradient(circle at 12% 10%, rgba(0, 198, 255, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 90% 12%, rgba(192, 132, 252, 0.30) 0%, transparent 45%),
                radial-gradient(circle at 50% 55%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 85% 95%, rgba(0, 198, 255, 0.25) 0%, transparent 50%),
                #edf2fe;
            background-attachment: fixed;
        }

        /* 3D Glass Action Cards (Matches user.jpeg) */
        .glass-action-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 25px -4px rgba(99, 102, 241, 0.08), 0 3px 8px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-action-card:active {
            transform: scale(0.94);
        }

        /* Liquid Hero Glass Card */
        .hero-fluid-card {
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #06b6d4 100%);
            box-shadow: 0 18px 40px -10px rgba(79, 70, 229, 0.45);
            border-radius: 38px;
        }

        .bottom-nav-glass {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 -8px 28px rgba(79, 70, 229, 0.10);
        }
    </style>
</head>

<body class="ambient-wave-canvas flex justify-center items-start min-h-screen relative overflow-x-hidden">

    <!-- Subtle Floating 3D Spheres in Ambient Background (Matches sample) -->
    <div
        class="fixed top-24 -left-6 w-14 h-14 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 blur-[1px] opacity-40 shadow-xl pointer-events-none">
    </div>
    <div
        class="fixed top-64 right-3 w-10 h-10 rounded-full bg-gradient-to-tr from-purple-400 to-fuchsia-500 blur-[1px] opacity-35 shadow-lg pointer-events-none">
    </div>
    <div
        class="fixed bottom-36 left-4 w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-300 to-cyan-400 blur-[1px] opacity-40 shadow-lg pointer-events-none">
    </div>

    <!-- Mobile Screen Wrapper -->
    <div
        class="w-full max-w-[430px] min-h-screen relative flex flex-col justify-between pb-24 shadow-2xl bg-white/20 backdrop-blur-sm z-10">

        <!-- Top Header Navigation -->
        <header class="px-5 pt-6 pb-2 flex items-center justify-between z-20 relative">
            <!-- Redesigned CanvaEdits Header -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 group">
                <div
                    class="w-9 h-9 rounded-xl overflow-hidden shadow-md group-hover:scale-105 transition-transform flex-shrink-0 border border-white/20">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="CanvaEdits Icon"
                        class="w-full h-full object-cover">
                </div>
                <div class="leading-none flex items-baseline">
                    <span
                        class="canvearn-brand-script text-[26px] font-black text-[#00c4cc] drop-shadow-sm tracking-tight">Canva</span>
                    <span class="text-xl font-extrabold text-indigo-600 tracking-tight ml-0.5">Edits</span>
                </div>
            </a>

            <div class="flex items-center space-x-2.5 relative">
                <!-- Notifications Bell -->
                <button type="button"
                    class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-700 relative hover:bg-slate-50 transition">
                    <i class="fa-solid fa-bell text-sm"></i>
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                </button>

                <!-- Interactive User Avatar / Initials Dropdown Pill -->
                <div class="relative">
                    <button type="button" id="userMenuButton" onclick="toggleUserMenu()"
                        class="flex items-center space-x-1 pl-1 pr-2 py-1 rounded-full bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition border border-slate-100 focus:outline-none">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-inner overflow-hidden">
                            @if (auth()->check() && auth()->user()->avatar)
                                <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover"
                                    alt="DP">
                            @elseif(auth()->check())
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @else
                                CE
                            @endif
                        </div>
                        <i id="userMenuChevron"
                            class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                    </button>

                    <!-- Dropdown Menu / Action Sheet -->
                    <div id="userDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white py-2 z-50 transform origin-top-right transition-all">

                        <!-- User Name Header -->
                        <div class="px-3.5 py-2 border-b border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Signed in
                                as</span>
                            <span
                                class="text-xs font-black text-slate-800 truncate block">{{ auth()->user()->name ?? 'User' }}</span>
                        </div>

                        <!-- 1. Go to Account Settings -->
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center space-x-2.5 px-3.5 py-2.5 text-xs font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fa-regular fa-user text-sm text-indigo-500 w-4 text-center"></i>
                            <span>Account Settings</span>
                        </a>

                        <!-- 2. Logout Action -->
                        <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-slate-100">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center space-x-2.5 px-3.5 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition text-left">
                                <i
                                    class="fa-solid fa-arrow-right-from-bracket text-sm text-rose-500 w-4 text-center"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Dynamic Content -->
        <main class="flex-1 px-4 py-1">
            @yield('content')
        </main>

        <!-- Sticky Bottom Navigation Bar -->
        <nav
            class="fixed bottom-3 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[398px] bottom-nav-glass rounded-3xl py-2 px-3 flex justify-between items-center z-30">
            <!-- 1. Home -->
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[9px] mt-0.5">Home</span>
            </a>

            <!-- 2. Tasks (Replaced Notifications) -->
            <a href="{{ route('tasks.index') }}"
                class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('tasks.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-clipboard-check text-base"></i>
                <span class="text-[9px] mt-0.5">Tasks</span>
            </a>

            <!-- 3. Earnings ($ Icon) -->
            <a href="{{ route('rewards') }}"
                class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('rewards*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-dollar-sign text-base"></i>
                <span class="text-[9px] mt-0.5">Earnings</span>
            </a>

            <!-- 4. Support (Direct WhatsApp Chat with Admin) -->
            <a href="https://wa.me/923001234567?text=Hello%20CanvaEdits%20Admin,%20I%20need%20assistance."
                target="_blank"
                class="flex flex-col items-center py-1 px-2.5 text-slate-400 hover:text-emerald-600 transition">
                <i class="fa-brands fa-whatsapp text-lg text-emerald-500"></i>
                <span class="text-[9px] mt-0.5">Support</span>
            </a>

            <!-- 5. Account -->
            <a href="{{ route('profile.edit') }}"
                class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('profile.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-user text-base"></i>
                <span class="text-[9px] mt-0.5">Account</span>
            </a>
        </nav>

    </div>

    <!-- Floating Toast Notification Container -->
    <div id="copyToast"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 transform -translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div
            class="px-5 py-2.5 rounded-2xl bg-slate-900/90 text-white text-xs font-bold shadow-2xl backdrop-blur-md flex items-center space-x-2 border border-white/20">
            <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
            <span id="toastMessage">Referral link copied to clipboard!</span>
        </div>
    </div>

    @stack('scripts')

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('userMenuChevron');
            if (!menu) return;

            menu.classList.toggle('hidden');
            if (chevron) {
                chevron.classList.toggle('rotate-180');
            }
        }

        // Close dropdown when tapping anywhere outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('userMenuButton');
            const menu = document.getElementById('userDropdownMenu');
            const chevron = document.getElementById('userMenuChevron');

            if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                }
            }
        });
    </script>
</body>

</html>
