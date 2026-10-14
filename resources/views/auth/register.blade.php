@extends('layouts.guest')

@php
    $heading = 'Create Account';
    $subheading = 'Join Canva ADS and start earning daily';
@endphp

@section('content')
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

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Referral Code -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                Referral Code <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-500">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <input type="text" name="referral_code" required
                       value="{{ old('referral_code', request('ref')) }}"
                       placeholder="Enter Referral Code"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Full Name -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Full Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-regular fa-user text-sm"></i>
                </div>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="e.g. Khadija Zaman"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="name@example.com"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input type="password" name="password" required autocomplete="new-password"
                       placeholder="Create a password"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Confirm Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Re-enter your password"
                       class="w-full bg-white/70 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full glass-gradient-btn text-white font-semibold py-3 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 mt-2">
            <span>Create Account</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <div class="text-center mt-5 pt-4 border-t border-slate-200/60">
        <p class="text-xs text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 ml-1">Sign In</a>
        </p>
    </div>
@endsection
