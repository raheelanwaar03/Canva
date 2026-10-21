<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Reset Password — Cavaearn</title>

    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' stop-color='%2300c6ff'/><stop offset='50%25' stop-color='%230072ff'/><stop offset='100%25' stop-color='%239b51e0'/></linearGradient></defs><circle cx='50' cy='50' r='48' fill='url(%23g)' stroke='%23ffffff' stroke-width='4'/><text x='50' y='52' font-family='Arial, sans-serif' font-weight='900' font-size='22' font-style='italic' fill='%23ffffff' text-anchor='middle'>Cavaearn</text><text x='50' y='72' font-family='Arial, sans-serif' font-weight='900' font-size='13' letter-spacing='2' fill='%23ffffff' text-anchor='middle'>ADS</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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

        <a href="/"
            class="w-20 h-20 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-xl border-4 border-white/80 mb-3 hover:scale-105 transition">
            <span class="font-extrabold text-white text-lg tracking-wider italic">Cavaearn</span>
            <span class="font-black text-white text-[10px] tracking-widest -mt-1">ADS</span>
        </a>

        <h1 class="text-2xl font-black text-slate-900 tracking-tight text-center">Reset Password</h1>
        <p class="text-xs text-slate-500 text-center mt-1 max-w-xs">
            Forgot your password? Enter your email to receive a password reset link.
        </p>

        <div class="w-full glass-card rounded-3xl p-6 mt-5 space-y-4 shadow-xl">

            @if (session('status'))
                <div
                    class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-3.5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="name@example.com"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <button type="submit"
                    style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
                    <span>Email Password Reset Link</span>
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>

            <div class="text-center pt-3 border-t border-slate-100">
                <p class="text-xs text-slate-500 font-medium">
                    Remembered your password?
                    <a href="{{ route('login') }}"
                        class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Back to Sign In</a>
                </p>
            </div>
        </div>

    </div>

</body>

</html>
