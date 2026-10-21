@extends('layouts.auth')

@php
    $title = 'Sign In';
    $heading = 'Welcome Back';
    $subheading = 'Sign in to access your advertising dashboard';
@endphp

@section('content')
<div class="space-y-4">

    @if (session('status'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold">
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

    <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
            <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="name@example.com"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
            </div>
        </div>

        <!-- Password with Eye Toggle -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-bold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] text-indigo-600 hover:text-indigo-700 font-semibold">Forgot?</a>
                @endif
            </div>
            <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" id="loginPassword" name="password" required autocomplete="current-password"
                       placeholder="Enter your password"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                <button type="button" onclick="togglePassword('loginPassword', 'eyeIconLogin')" class="text-slate-400 hover:text-indigo-600 px-1 focus:outline-none">
                    <i class="fa-regular fa-eye" id="eyeIconLogin"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-0.5">
            <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <span class="text-[11px] font-semibold">Remember me</span>
            </label>
        </div>

        <!-- Sign In Button -->
        <button type="submit"
                style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
            <span>Sign In</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="text-center pt-3 border-t border-slate-100">
        <p class="text-xs text-slate-500 font-medium">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Create Account</a>
        </p>
    </div>

</div>
@endsection
