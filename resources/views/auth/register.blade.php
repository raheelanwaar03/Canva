@extends('layouts.auth')

@php
    $title = 'Create Account';
    $heading = 'Create Account';
    $subheading = 'Join with your referral code & start earning';
@endphp

@section('content')
<div class="space-y-3.5">

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

    <div class="text-center pt-3 border-t border-slate-100">
        <p class="text-xs text-slate-500 font-medium">
            Already registered?
            <a href="{{ route('login') }}" class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Sign In</a>
        </p>
    </div>

</div>
@endsection
