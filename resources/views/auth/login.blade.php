@extends('layouts.guest')

@php
    $heading = 'Welcome Back';
    $subheading = 'Sign in to access your dashboard';
@endphp

@section('content')
    <!-- Status Message (e.g. password reset successful) -->
    @if (session('status'))
        <div class="mb-4 text-xs font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 p-3 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-xs">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="name@example.com"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-slate-600">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] text-indigo-600 hover:text-indigo-700">Forgot?</a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input type="password" name="password" required autocomplete="current-password"
                       placeholder="Enter your password"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <span>Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full glass-gradient-btn text-white font-semibold py-3 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 mt-2">
            <span>Sign In</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="text-center mt-5 pt-4 border-t border-slate-200/60">
        <p class="text-xs text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 ml-1">Register Now</a>
        </p>
    </div>
@endsection
