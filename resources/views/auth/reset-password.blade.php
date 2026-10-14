<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Set New Password — Canva ADS</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
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
        <a href="/"
            class="w-20 h-20 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-xl border-4 border-white/80 mb-3 hover:scale-105 transition">
            <span class="font-extrabold text-white text-lg tracking-wider italic">Canva</span>
            <span class="font-black text-white text-[10px] tracking-widest -mt-1">ADS</span>
        </a>

        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">New Password</h1>
        <p class="text-xs text-slate-500 text-center mt-1 max-w-xs">
            Create a new secure password to restore access to your account
        </p>

        <!-- Form Card -->
        <div class="w-full glass-card rounded-3xl p-6 mt-5 space-y-4 shadow-xl">

            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-3.5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                            autofocus autocomplete="username" placeholder="name@example.com"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- New Password with Eye Toggle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">New Password</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="resetPassword" name="password" required autocomplete="new-password"
                            placeholder="Enter new password"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                        <button type="button" onclick="togglePassword('resetPassword', 'eyeIconReset')"
                            class="text-slate-400 hover:text-indigo-600 px-1 focus:outline-none">
                            <i class="fa-regular fa-eye" id="eyeIconReset"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password with Eye Toggle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Confirm New Password</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <input type="password" id="resetPasswordConfirm" name="password_confirmation" required
                            autocomplete="new-password" placeholder="Confirm new password"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                        <button type="button" onclick="togglePassword('resetPasswordConfirm', 'eyeIconResetConfirm')"
                            class="text-slate-400 hover:text-indigo-600 px-1 focus:outline-none">
                            <i class="fa-regular fa-eye" id="eyeIconResetConfirm"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
                    <span>Update Password</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <div class="text-center pt-3 border-t border-slate-100">
                <p class="text-xs text-slate-500 font-medium">
                    Return to
                    <a href="{{ route('login') }}"
                        class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Sign In</a>
                </p>
            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePassword(inputId, iconId) {
            const field = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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
