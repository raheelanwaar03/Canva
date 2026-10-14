<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Canva ADS') }}</title>

    <!-- Google Fonts (Poppins / Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome / RemixIcon for 3D/Clean Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #ede9fe 50%, #fce7f3 100%);
            min-height: 100vh;
        }
        /* Custom animated wave ambient background */
        .wave-bg {
            background: radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.25) 0%, transparent 40%),
                        radial-gradient(circle at 90% 10%, rgba(192, 132, 252, 0.3) 0%, transparent 40%),
                        radial-gradient(circle at 50% 80%, rgba(244, 114, 182, 0.2) 0%, transparent 50%);
        }
        /* Glassmorphism utilities */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.08), 0 8px 10px -6px rgba(99, 102, 241, 0.04);
        }
        .glass-gradient-btn {
            background: linear-gradient(90deg, #00c6ff 0%, #9b51e0 100%);
        }
    </style>
</head>
<body class="wave-bg text-slate-800 antialiased flex justify-center min-h-screen">

    <!-- Mobile Device Container Wrapper -->
    <div class="w-full max-w-md min-h-screen flex flex-col justify-between relative pb-24 shadow-2xl bg-white/20">

        <!-- Top Header Navigation -->
        <header class="px-5 pt-5 pb-3 flex items-center justify-between z-20">
            @if(request()->routeIs('dashboard'))
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
                <div class="flex items-center space-x-3">
                    <button class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-slate-600 hover:text-indigo-600 transition relative">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-pink-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-pink-500 hover:bg-pink-50 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            @else
                <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-slate-700 hover:bg-white transition">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-pink-500 hover:bg-pink-50 transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            @endif
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 px-5">
            {{ $slot }}
        </main>

        <!-- Persistent Bottom App Navigation -->
        <nav class="fixed bottom-3 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[416px] glass-card rounded-3xl py-2 px-4 flex justify-between items-center z-30 shadow-xl border border-white/80">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
                <i class="fa-solid fa-house text-lg mb-0.5"></i>
                <span class="text-[10px]">Dashboard</span>
            </a>
            <a href="#" class="flex flex-col items-center py-1 px-3 text-slate-400 hover:text-indigo-500">
                <i class="fa-regular fa-bell text-lg mb-0.5"></i>
                <span class="text-[10px]">Notifications</span>
            </a>
            <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('tasks.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
                <i class="fa-solid fa-clipboard-check text-lg mb-0.5"></i>
                <span class="text-[10px]">Tasks</span>
            </a>
            <a href="{{ route('wallet') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('wallet') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
                <i class="fa-solid fa-wallet text-lg mb-0.5"></i>
                <span class="text-[10px]">Wallet</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('profile.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
                <i class="fa-regular fa-user text-lg mb-0.5"></i>
                <span class="text-[10px]">Account</span>
            </a>
        </nav>
    </div>

    <!-- Clipboard Toast Helper -->
    <script>
        function copyReferralLink(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Referral link copied to clipboard!');
            });
        }
    </script>
</body>
</html>
