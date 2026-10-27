<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} — CanvaEdits</title>

    <!-- Cavaearn Admin Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }
    </style>
</head>

<body class="text-slate-800 antialiased flex min-h-screen bg-[#f4f6f9]">

    <!-- Overlay for Mobile Sidebar -->
    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

    <!-- 1. DARK SIDEBAR (PTCLab Style) -->
    <aside id="adminSidebar"
        class="fixed top-0 bottom-0 left-0 w-64 bg-[#0c1017] text-slate-300 z-50 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl">

        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/80 bg-[#090d14]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md flex-shrink-0 border border-slate-800">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="CanvaEdits" class="w-full h-full object-cover">
                </div>
                <div class="leading-none">
                    <div class="flex items-baseline">
                        <span class="canvearn-brand-script text-xl font-bold text-[#00c4cc]">Canva</span>
                        <span class="text-white font-extrabold text-sm tracking-tight ml-0.5">Edits</span>
                    </div>
                    <span class="text-[9px] text-indigo-400 font-bold tracking-widest block mt-0.5">ADMIN PANEL</span>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Sidebar Navigation List -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-1 text-xs font-semibold">

            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-solid fa-house w-4 text-center"></i>
                <span class="flex-1">Dashboard</span>
            </a>

            <!-- All Approved Users -->
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-regular fa-circle-check w-4 text-center"></i>
                <span class="flex-1">All Approved Users</span>
            </a>

            <!-- New User Request (Deposit / Plan requests) -->
            @php
                $pendingDeposits = \App\Models\PackagePayment::where('status', 'pending')->count();
                $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
            @endphp
            <a href="{{ route('admin.payments.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-regular fa-circle-dot w-4 text-center"></i>
                <span class="flex-1">New User Request</span>
                @if ($pendingDeposits > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse">{{ $pendingDeposits }}</span>
                @endif
            </a>

            <!-- Withdrawal Requests -->
            <a href="{{ route('admin.withdrawals.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.withdrawals.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-solid fa-money-bill-transfer w-4 text-center"></i>
                <span class="flex-1">Withdrawal Request</span>
                @if ($pendingWithdrawalsCount > 0)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white">{{ $pendingWithdrawalsCount }}</span>
                @endif
            </a>

            <!-- Plan Upgrades -->
            <a href="{{ route('admin.packages.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.packages.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-solid fa-arrow-up-right-dots w-4 text-center"></i>
                <span class="flex-1">Plan Upgrade Settings</span>
            </a>

            <!-- Section: SETTINGS -->
            <div class="pt-4 pb-1 px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">
                Settings
            </div>

            <!-- Ad Tasks -->
            <a href="{{ route('admin.tasks.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.tasks.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <i class="fa-solid fa-bullhorn w-4 text-center"></i>
                <span class="flex-1">Daily Ads Inventory</span>
            </a>

            <!-- Official Channels -->
            <a href="{{ route('admin.channels.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.channels.*') ? 'bg-[#4f46e5] text-white shadow-lg' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-paper-plane text-sm"></i>
                <span>Official Channels</span>
            </a>

            <!-- Platform Settings (NEW) -->
            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#4f46e5] text-white shadow-lg' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-gear text-sm"></i>
                <span>Platform Settings</span>
            </a>

            <!-- Refund Requests (NEW) -->
            <a href="{{ route('admin.refunds.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.refunds.*') ? 'bg-[#4f46e5] text-white shadow-lg' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i class="fa-solid fa-arrow-rotate-left text-sm"></i>
                <span>Refund Requests</span>
            </a>

        </nav>

        <!-- Footer / Version -->
        <div class="p-3 border-t border-slate-800/80 bg-[#090d14] text-center text-[10px] text-slate-500 font-mono">
            CANVAEDITS v2.6 • PTCLAB
        </div>
    </aside>

    <!-- 2. MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        <!-- Top Header Navigation Bar -->
        <header
            class="h-16 bg-[#000842] text-white flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-md">

            <div class="flex items-center space-x-3 flex-1 max-w-lg">
                <!-- Hamburger Button (Mobile) -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 text-white hover:text-indigo-200">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- Search Bar -->
                <div class="relative w-full max-w-xs sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" placeholder="Search here..."
                        class="w-full bg-white/10 text-white placeholder-slate-300 text-xs rounded-xl pl-8 pr-3 py-2 border border-white/20 focus:outline-none focus:bg-white/20 transition">
                </div>
            </div>

            <!-- Header Right Menu -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.payments.index') }}" class="relative p-2 text-slate-200 hover:text-white">
                    <i class="fa-regular fa-bell text-base"></i>
                    @if ($pendingDeposits > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </a>

                <!-- Admin Profile Dropdown & Logout -->
                <div class="flex items-center space-x-2 pl-2 border-l border-white/20">
                    <span class="hidden sm:block text-xs font-bold text-white">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout"
                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-rose-500 text-white flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Dynamic Main Body Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('admin_content')
        </main>
    </div>

    <!-- Toggle Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>

</html>
