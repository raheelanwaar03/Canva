<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Account' }} — Cavaearn</title>

    <!-- Cavaearn Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=2">

    <!-- Fonts -->
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

    <div class="w-full max-w-[400px] flex flex-col items-center">

        <!-- Cavaearn New Auth Logo -->
        <a href="/" class="flex flex-col items-center group mb-4">
            <!-- Sleek, centered 3D Brand Badge -->
            <div
                class="w-16 h-16 rounded-2xl overflow-hidden shadow-xl border border-white/40 group-hover:scale-105 transition-transform flex-shrink-0">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Cavaearn Logo" class="w-full h-full object-cover">
            </div>
            <!-- Dynamic and secure styling (no clipping or run-out) -->
            <div class="mt-2 text-center leading-none flex items-baseline justify-center">
                <span
                    class="canvearn-brand-script text-[32px] font-black text-[#00c4cc] tracking-tight drop-shadow-sm">Cava</span>
                <span class="text-2xl font-black text-indigo-700 tracking-tight ml-0.5">earn</span>
            </div>
        </a>

        <!-- Page Heading -->
        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">{{ $heading ?? 'Welcome' }}</h1>
        <p class="text-xs text-slate-500 text-center mt-1 leading-normal px-4">
            {{ $subheading ?? 'Access your digital advertising portal' }}</p>

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
