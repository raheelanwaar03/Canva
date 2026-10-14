<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Canva ADS</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN (Guarantees zero build/asset errors) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef5ff;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Ambient Wave Gradient Background */
        .wave-background {
            background:
                radial-gradient(circle at 15% 10%, rgba(56, 189, 248, 0.45) 0%, transparent 45%),
                radial-gradient(circle at 90% 15%, rgba(217, 70, 239, 0.35) 0%, transparent 45%),
                radial-gradient(circle at 50% 60%, rgba(129, 140, 248, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 90%, rgba(56, 189, 248, 0.35) 0%, transparent 50%),
                #e8f1fc;
            background-attachment: fixed;
        }

        /* Glass Cards */
        .glass-card-item {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 24px -4px rgba(79, 70, 229, 0.08), 0 4px 8px -2px rgba(0, 0, 0, 0.03);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .glass-card-item:active {
            transform: scale(0.97);
        }

        /* Referral Card Gradient */
        .ref-gradient-card {
            background: linear-gradient(105deg, #00d2ff 0%, #3a7bd5 50%, #9055ff 100%);
            box-shadow: 0 12px 28px -6px rgba(0, 180, 255, 0.35);
        }

        /* 3D Icon Containers */
        .icon-box-3d {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 16px -3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Floating App Nav */
        .bottom-nav-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 -8px 30px rgba(79, 70, 229, 0.12);
        }
    </style>
</head>
<body class="wave-background flex justify-center items-start min-h-screen">

    <!-- Mobile Shell: Centers on desktop, exact 100% width on phone -->
    <div class="w-full max-w-[430px] min-h-screen relative flex flex-col justify-between pb-24 shadow-2xl bg-white/30 backdrop-blur-sm">

        <!-- Top Header Bar -->
        <header class="px-5 pt-6 pb-2 flex items-center justify-between z-20">
            @if(request()->routeIs('dashboard'))
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Dashboard</h1>
                <div class="flex items-center space-x-3">
                    <button class="w-10 h-10 rounded-full bg-white/90 shadow-md flex items-center justify-center text-slate-700 relative">
                        <i class="fa-solid fa-bell text-base"></i>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-white/90 shadow-md flex items-center justify-center text-rose-500 hover:bg-rose-50">
                            <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                        </button>
                    </form>
                </div>
            @else
                <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-white/90 shadow-md flex items-center justify-center text-slate-700">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <span class="font-bold text-slate-900 text-lg">{{ $headerTitle ?? 'Canva ADS' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-10 h-10 rounded-full bg-white/90 shadow-md flex items-center justify-center text-rose-500 hover:bg-rose-50">
                        <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                    </button>
                </form>
            @endif
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 px-4 py-2">
            @yield('content')
        </main>

        <!-- Bottom Navigation Bar -->
        <nav class="fixed bottom-3 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[398px] bottom-nav-glass rounded-3xl py-2 px-3 flex justify-between items-center z-30">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-extrabold' : 'text-slate-400' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] mt-0.5">Dashboard</span>
            </a>
            <a href="#" class="flex flex-col items-center py-1 px-3 text-slate-400">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="text-[10px] mt-0.5">Notifications</span>
            </a>
            <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('tasks.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400' }}">
                <i class="fa-solid fa-clipboard-check text-lg"></i>
                <span class="text-[10px] mt-0.5">Tasks</span>
            </a>
            <a href="{{ route('wallet') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('wallet') ? 'text-indigo-600 font-extrabold' : 'text-slate-400' }}">
                <i class="fa-solid fa-wallet text-lg"></i>
                <span class="text-[10px] mt-0.5">Wallet</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('profile.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400' }}">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[10px] mt-0.5">Account</span>
            </a>
        </nav>
    </div>

    @stack('scripts')
</body>
</html>
