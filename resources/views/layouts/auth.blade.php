<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Account' }} — CanvaEdits</title>

    <!-- CanvaEdits Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=4">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=4">

    <!-- Fonts: Plus Jakarta Sans & Dancing Script for Canva branding -->
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
        .canva-brand-script {
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 16px 36px -8px rgba(79, 70, 229, 0.15), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="ambient-wave-auth flex justify-center items-center min-h-screen p-4 text-slate-800">

    <div class="w-full max-w-[420px] flex flex-col items-center">

        <!-- CanvaEdits Logo Header -->
        <a href="/" class="flex flex-col items-center group mb-2">
            <div class="w-16 h-16 rounded-3xl overflow-hidden shadow-xl border-2 border-white/60 p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-600 to-purple-600 group-hover:scale-105 transition-transform flex-shrink-0">
                <img src="{{ asset('images/logo-full.png') }}" alt="CanvaEdits" class="w-full h-full object-cover rounded-[22px]">
            </div>
            <div class="mt-2 text-center leading-tight flex items-baseline justify-center">
                <span class="canva-brand-script text-3xl font-black text-[#00c4cc]">Canva</span><span class="text-2xl font-black text-indigo-700 tracking-tight ml-0.5">Edits</span>
            </div>
        </a>

        <!-- Page Heading -->
        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">{{ $heading ?? 'Welcome' }}</h1>
        <p class="text-xs text-slate-500 text-center mt-1">{{ $subheading ?? 'Access your digital advertising portal' }}</p>

        <!-- Main Form Card -->
        <div class="w-full glass-auth-card rounded-3xl p-6 mt-5 shadow-2xl">
            @yield('content')
        </div>

        <!-- Footer Notice -->
        <p class="text-[11px] text-slate-400 text-center mt-6">
            &copy; {{ date('Y') }} CanvaEdits. Fast & verified payouts via JazzCash & Easypaisa.
        </p>

    </div>

    <!-- Password Visibility Toggle Helper -->
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
