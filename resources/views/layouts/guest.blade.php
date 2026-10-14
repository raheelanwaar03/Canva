<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Canva ADS' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #ede9fe 50%, #fce7f3 100%);
            min-height: 100vh;
        }
        .wave-ambient {
            background: radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.22) 0%, transparent 40%),
                        radial-gradient(circle at 90% 10%, rgba(192, 132, 252, 0.25) 0%, transparent 40%),
                        radial-gradient(circle at 50% 80%, rgba(244, 114, 182, 0.18) 0%, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 12px 30px -5px rgba(99, 102, 241, 0.12);
        }
        .glass-gradient-btn {
            background: linear-gradient(90deg, #00c6ff 0%, #9b51e0 100%);
        }
    </style>
</head>
<body class="wave-ambient text-slate-800 antialiased flex justify-center items-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <!-- Logo Branding Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-lg border-2 border-white/80">
                <span class="font-extrabold text-white text-lg tracking-wider italic">Canva</span>
                <span class="font-black text-white text-[11px] tracking-widest -mt-1">ADS</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mt-3">{{ $heading ?? 'Canva ADS' }}</h1>
            <p class="text-xs text-slate-500">{{ $subheading ?? 'Start earning daily with advertising tasks' }}</p>
        </div>

        <!-- Form Card Content -->
        <div class="glass-card rounded-3xl p-6 sm:p-7">
            @yield('content')
        </div>
    </div>

</body>
</html>
