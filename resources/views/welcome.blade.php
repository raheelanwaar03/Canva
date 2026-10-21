<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Cavaearn — Watch Daily Ads & Earn</title>

    <!-- Cavaearn Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">

    <!-- Google Fonts -->
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
            background: #eef3fe;
            min-height: 100vh;
        }
        .canvearn-brand-script {
            font-family: 'Dancing Script', cursive;
        }
        .ambient-wave-welcome {
            background:
                radial-gradient(circle at 10% 10%, rgba(0, 196, 204, 0.32) 0%, transparent 45%),
                radial-gradient(circle at 90% 12%, rgba(139, 92, 246, 0.32) 0%, transparent 45%),
                radial-gradient(circle at 50% 55%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 92%, rgba(0, 196, 204, 0.25) 0%, transparent 50%),
                #edf2fe;
            background-attachment: fixed;
        }
        .glass-welcome-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 45px -10px rgba(79, 70, 229, 0.14), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
        }
        .btn-gradient {
            background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #8b5cf6 100%);
            box-shadow: 0 10px 24px -4px rgba(0, 114, 255, 0.35);
        }
        .btn-gradient:hover {
            box-shadow: 0 14px 28px -4px rgba(0, 114, 255, 0.45);
        }
        .package-pill {
            background: rgba(248, 250, 252, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.9);
            transition: all 0.2s ease;
        }
        .package-pill:hover {
            transform: translateY(-2px);
            border-color: #818cf8;
            box-shadow: 0 6px 16px -2px rgba(99, 102, 241, 0.12);
        }
    </style>
</head>
<body class="ambient-wave-welcome flex justify-center items-center min-h-screen p-4 text-slate-800">

    <div class="w-full max-w-[430px] flex flex-col items-center py-6">

        <!-- Redesigned Cavaearn 3D Logo Header -->
        <div class="flex flex-col items-center group mb-2">
            <div class="w-20 h-20 rounded-3xl overflow-hidden shadow-2xl border-2 border-white/60 p-[3px] bg-gradient-to-tr from-cyan-400 via-indigo-600 to-purple-600 group-hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Cavaearn" class="w-full h-full object-cover rounded-[22px]">
            </div>

            <div class="mt-3 text-center leading-none flex items-baseline justify-center">
                <span class="canvearn-brand-script text-4xl font-black text-[#00c4cc] drop-shadow-sm tracking-tight">Cava</span>
                <span class="text-3xl font-extrabold text-indigo-700 tracking-tight ml-0.5">earn</span>
            </div>
        </div>

        <!-- Tagline & Description -->
        <p class="text-xs text-slate-600 text-center mt-2 max-w-xs leading-relaxed font-medium">
            The next-generation advertising platform. Watch daily ads, build your partner team, and withdraw your earnings directly.
        </p>

        <!-- Trust Badges Row -->
        <div class="flex items-center space-x-3 mt-3 text-[10px] font-bold text-slate-500">
            <span class="inline-flex items-center"><i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Instant Payouts</span>
            <span>•</span>
            <span class="inline-flex items-center"><i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> FBR Compliant</span>
            <span>•</span>
            <span class="inline-flex items-center"><i class="fa-solid fa-users text-indigo-500 mr-1"></i> 8-Tier Levels</span>
        </div>

        <!-- Main Glass Card Container -->
        <div class="w-full glass-welcome-card rounded-3xl p-6 mt-5 space-y-4 shadow-2xl">

            <!-- Primary CTAs -->
            <div class="space-y-2.5">
                @auth
                    <!-- If already logged in, quick entry -->
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                       class="btn-gradient w-full text-white font-extrabold py-3.5 px-6 rounded-2xl flex items-center justify-center space-x-2 text-xs tracking-wide transition hover:opacity-95">
                        <span>Go to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <!-- Sign In Button -->
                    <a href="{{ route('login') }}"
                       class="btn-gradient w-full text-white font-extrabold py-3.5 px-6 rounded-2xl flex items-center justify-center space-x-2 text-xs tracking-wide transition hover:opacity-95">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <!-- Create Account Button -->
                    <a href="{{ route('register') }}"
                       class="w-full bg-white hover:bg-slate-50 text-indigo-700 font-extrabold py-3.5 px-6 rounded-2xl border border-indigo-200 shadow-sm flex items-center justify-center space-x-2 text-xs tracking-wide transition hover:shadow">
                        <i class="fa-regular fa-user text-xs"></i>
                        <span>Create New Account</span>
                    </a>
                @endauth
            </div>

            <!-- Packages Showcase Section -->
            <div class="pt-3 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2.5 px-1">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">4 Membership Packages</span>
                    <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">30 Days Validity</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Basic -->
                    <div class="package-pill rounded-2xl p-2.5 text-center">
                        <div class="text-[10px] font-bold text-slate-500 uppercase">Basic</div>
                        <div class="text-sm font-black text-indigo-700 mt-0.5">Rs 500</div>
                        <div class="text-[9px] text-slate-400 mt-0.5 font-medium">1 Ad / Day</div>
                    </div>

                    <!-- Gold -->
                    <div class="package-pill rounded-2xl p-2.5 text-center">
                        <div class="text-[10px] font-bold text-slate-500 uppercase">Gold</div>
                        <div class="text-sm font-black text-amber-600 mt-0.5">Rs 1,500</div>
                        <div class="text-[9px] text-slate-400 mt-0.5 font-medium">2 Ads / Day</div>
                    </div>

                    <!-- Standard -->
                    <div class="package-pill rounded-2xl p-2.5 text-center">
                        <div class="text-[10px] font-bold text-slate-500 uppercase">Standard</div>
                        <div class="text-sm font-black text-indigo-700 mt-0.5">Rs 2,500</div>
                        <div class="text-[9px] text-slate-400 mt-0.5 font-medium">4 Ads / Day</div>
                    </div>

                    <!-- Premium -->
                    <div class="package-pill rounded-2xl p-2.5 text-center">
                        <div class="text-[10px] font-bold text-slate-500 uppercase">Premium</div>
                        <div class="text-sm font-black text-purple-700 mt-0.5">Rs 5,500</div>
                        <div class="text-[9px] text-slate-400 mt-0.5 font-medium">6 Ads / Day</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Note -->
        <p class="text-[11px] text-slate-400 text-center mt-5 leading-normal">
            &copy; {{ date('Y') }} Cavaearn. Fast, secure, and reliable payouts via JazzCash & Easypaisa.
        </p>

    </div>

</body>
</html>
