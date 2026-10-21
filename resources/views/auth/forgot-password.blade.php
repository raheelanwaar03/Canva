@extends('layouts.auth')

@php
    $title = 'Reset Password';
    $heading = 'Reset Password';
    $subheading = 'Enter your email to receive a password reset link';
@endphp

@section('content')
<div class="space-y-4">

    @if (session('status'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold mb-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold mb-3">
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
            <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400 shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5 flex-shrink-0 text-sm">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="name@example.com"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
            </div>
        </div>

        <button type="submit"
                style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
            <span>Email Password Reset Link</span>
            <i class="fa-solid fa-paper-plane text-xs"></i>
        </button>
    </form>

    <div class="text-center pt-3 border-t border-slate-100">
        <p class="text-xs text-slate-500 font-medium">
            Remembered your password?
            <a href="{{ route('login') }}" class="font-extrabold text-indigo-600 hover:text-indigo-700 ml-1">Back to Sign In</a>
        </p>
    </div>

</div>
@endsection
