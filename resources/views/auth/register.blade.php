<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Cavaearn</title>

    <!-- Cavaearn Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-icon.png') }}?v=3">

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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 16px 36px -8px rgba(79, 70, 229, 0.15), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="ambient-wave-auth flex justify-center items-center min-h-screen p-4 text-slate-800">

    <div class="w-full max-w-[420px] flex flex-col items-center py-4">

        <!-- Cavaearn Logo -->
        <a href="/" class="flex flex-col items-center group mb-2">
            <div class="w-16 h-16 rounded-3xl overflow-hidden shadow-xl border-2 border-white/60 p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-600 to-purple-600 group-hover:scale-105 transition-transform flex-shrink-0">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Cavaearn" class="w-full h-full object-cover rounded-[22px]">
            </div>
            <div class="mt-2 text-center leading-tight flex items-baseline justify-center">
                <span class="canvearn-brand-script text-3xl font-black text-[#00c4cc]">Cava</span><span class="text-2xl font-black text-indigo-700 tracking-tight ml-0.5">earn</span>
            </div>
        </a>

        <!-- Page Heading -->
        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">Create Account</h1>
        <p class="text-xs text-slate-500 text-center mt-1">Join with your referral code & start earning</p>

        <!-- Form Card -->
        <div class="w-full glass-auth-card rounded-3xl p-6 mt-5 shadow-2xl">

            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold mb-3">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <!-- Referral Code -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Referral Code <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <input type="text" name="referral_code" required
                               value="{{ old('referral_code', request('ref')) }}"
                               placeholder="Enter Referral Code"
                               class="w-full bg-transparent border-0 text-xs text-slate-800 font-bold focus:outline-none p-0 uppercase">
                    </div>
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="e.g. Khadija Zaman"
                               class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="name@example.com"
                               class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Password with Eye Toggle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="regPassword" name="password" required autocomplete="new-password"
                               placeholder="Create password"
                               class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                        <button type="button" onclick="togglePassword('regPassword', 'eyeIconReg')" class="text-slate-400 hover:text-indigo-600 px-1 focus:outline-none">
                            <i class="fa-regular fa-eye" id="eyeIconReg"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password with Eye Toggle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password</label>
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <input type="password" id="regPasswordConfirm" name="password_confirmation" required autocomplete="new-password"
                               placeholder="Repeat password"
                               class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                        <button type="button" onclick="togglePassword('regPasswordConfirm', 'eyeIconRegConfirm')" class="text-slate-400 hover:text-indigo-600 px-1 focus:outline-none">
                            <i class="fa-regular fa-eye" id="eyeIconRegConfirm"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                        class="w-full text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
                    <span>Create Account</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <div class="text-center pt-3 border-t border-slate-100 mt-3">
                <p class="text-xs text-slate-500 font-medium">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Sign In</a>
                </p>
            </div>
        </div>

        <p class="text-[11px] text-slate-400 text-center mt-6">
            &copy; {{ date('Y') }} Cavaearn. Fast & verified payouts via JazzCash & Easypaisa.
        </p>

    </div>

    <!-- Toggle Password Visibility Script -->
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
</body>
</html>
