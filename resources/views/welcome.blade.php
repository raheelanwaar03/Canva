<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Canva ADS — Earn Daily Watching Ads</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef5ff;
            min-height: 100vh;
        }
        .wave-background {
            background:
                radial-gradient(circle at 15% 10%, rgba(56, 189, 248, 0.45) 0%, transparent 45%),
                radial-gradient(circle at 90% 15%, rgba(217, 70, 239, 0.35) 0%, transparent 45%),
                radial-gradient(circle at 50% 60%, rgba(129, 140, 248, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 90%, rgba(56, 189, 248, 0.35) 0%, transparent 50%),
                #e8f1fc;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 12px 30px -5px rgba(79, 70, 229, 0.12);
        }
    </style>
</head>
<body class="wave-background flex justify-center items-center min-h-screen p-4 text-slate-800">

    <div class="w-full max-w-[430px] flex flex-col items-center">

        <!-- Logo Branding Header -->
        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-xl border-4 border-white/80 mb-5">
            <span class="font-extrabold text-white text-2xl tracking-wider italic">Canva</span>
            <span class="font-black text-white text-xs tracking-widest -mt-1">ADS</span>
        </div>

        <h1 class="text-3xl font-black text-slate-900 tracking-tight text-center">Canva ADS</h1>
        <p class="text-xs text-slate-500 text-center mt-1.5 max-w-xs">
            The next-generation advertising platform. Watch daily ads, build your team, and withdraw your earnings directly.
        </p>

        <!-- Main Card with Action Links -->
        <div class="w-full glass-card rounded-3xl p-6 mt-6 space-y-4 shadow-xl">
            @auth
                <div class="text-center space-y-3">
                    <p class="text-xs font-semibold text-slate-600">
                        Welcome back, <strong class="text-slate-900">{{ auth()->user()->name }}</strong>!
                    </p>
                    <a href="{{ route('dashboard') }}"
                       style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                       class="block text-center text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-lg hover:opacity-95 transition">
                        Go to Dashboard &rarr;
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    <a href="{{ route('login') }}"
                       style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                       class="block text-center text-white font-bold py-3.5 px-6 rounded-2xl text-xs shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('register') }}"
                       class="block text-center bg-white hover:bg-slate-50 text-indigo-700 font-bold py-3.5 px-6 rounded-2xl text-xs border border-indigo-100 shadow-sm transition">
                        Create New Account
                    </a>
                </div>

                <!-- Membership Packages Preview -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">4 Membership Packages</span>
                    <div class="grid grid-cols-4 gap-1.5 text-[10px] font-bold text-slate-700">
                        <div class="bg-indigo-50/70 p-2 rounded-xl">
                            <div>Basic</div>
                            <div class="text-indigo-600 mt-0.5">Rs 500</div>
                        </div>
                        <div class="bg-indigo-50/70 p-2 rounded-xl">
                            <div>Gold</div>
                            <div class="text-indigo-600 mt-0.5">Rs 1,500</div>
                        </div>
                        <div class="bg-indigo-50/70 p-2 rounded-xl">
                            <div>Standard</div>
                            <div class="text-indigo-600 mt-0.5">Rs 2,500</div>
                        </div>
                        <div class="bg-indigo-50/70 p-2 rounded-xl">
                            <div>Premium</div>
                            <div class="text-indigo-600 mt-0.5">Rs 5,500</div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Footer Note -->
        <p class="text-[11px] text-slate-400 text-center mt-6">
            &copy; {{ date('Y') }} Canva ADS. Fast, secure, and reliable payouts via JazzCash & Easypaisa.
        </p>

    </div>

</body>
</html>
