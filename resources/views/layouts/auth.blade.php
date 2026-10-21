<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Account' }} — Cavaearn</title>

    <!-- Cavaearn Dynamic SVG Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='cavaFav' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' stop-color='%2300c4cc'/><stop offset='50%25' stop-color='%234f46e5'/><stop offset='100%25' stop-color='%238b5cf6'/></linearGradient></defs><rect width='100' height='100' rx='28' fill='url(%23cavaFav)'/><text x='50' y='64' font-family='Arial, sans-serif' font-weight='900' font-size='42' font-style='italic' fill='%23ffffff' text-anchor='middle'>C</text><circle cx='76' cy='24' r='6' fill='%2300ffc2'/></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef2fe;
            min-height: 100vh;
        }
        .canvearn-brand-script {
            font-family: 'Dancing Script', cursive;
        }
        .ambient-wave-auth {
            background:
                radial-gradient(circle at 15% 10%, rgba(0, 196, 204, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 88% 12%, rgba(139, 92, 246, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 50% 60%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 92%, rgba(0, 196, 204, 0.22) 0%, transparent 50%),
                #edf2fe;
            background-attachment: fixed;
        }
        .glass-auth-card {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 16px 36px -8px rgba(79, 70, 229, 0.12), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="ambient-wave-auth flex justify-center items-center min-h-screen p-4 text-slate-800">

    <div class="w-full max-w-[420px] flex flex-col items-center">

        <!-- Modern Redesigned Cavaearn Brand Logo Header -->
        <a href="/" class="flex flex-col items-center group mb-3">
            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-cyan-400 via-indigo-600 to-purple-600 p-[2.5px] shadow-xl group-hover:scale-105 transition-transform">
                <div class="w-full h-full bg-[#000842] rounded-3xl flex items-center justify-center shadow-inner">
                    <span class="text-white font-black text-2xl italic tracking-tighter">C</span>
                </div>
            </div>
            <div class="mt-2 text-center leading-tight">
                <span class="canvearn-brand-script text-3xl font-black text-[#00c4cc]">Cava</span><span class="text-2xl font-black text-indigo-700 tracking-tight">earn</span>
            </div>
        </a>

        <!-- Page Heading -->
        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">{{ $heading ?? 'Welcome' }}</h1>
        <p class="text-xs text-slate-500 text-center mt-1">{{ $subheading ?? 'Access your digital advertising portal' }}</p>

        <!-- Main Auth Form Card -->
        <div class="w-full glass-auth-card rounded-3xl p-6 mt-5 shadow-2xl">
            @yield('content')
        </div>

        <!-- Footer Copyright -->
        <p class="text-[11px] text-slate-400 text-center mt-6">
            &copy; {{ date('Y') }} Cavaearn. Fast & verified payouts via JazzCash & Easypaisa.
        </p>

    </div>

    <!-- Universal Password Toggle Script -->
    <script>
        function togglePassword(inputId, iconId) {
            const field = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!field || !icon) return;

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
