@extends('layouts.app')

@php
    $headerTitle = 'Withdraw';
@endphp

@section('content')
    <div class="space-y-4 pt-1">

        <!-- Canva ADS Branding Circle -->
        <div class="flex flex-col items-center">
            <div
                class="w-20 h-20 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 flex flex-col items-center justify-center shadow-lg border-2 border-white/60">
                <span class="font-extrabold text-white text-lg tracking-wider italic">Canva</span>
                <span class="font-black text-white text-[11px] tracking-widest -mt-1">ADS</span>
            </div>
            <h2 class="text-xl font-black text-slate-800 mt-2">Withdraw</h2>
            <div class="w-10 h-1 bg-gradient-to-r from-cyan-400 to-purple-600 rounded-full mt-1"></div>
        </div>

        <!-- Errors Alert -->
        @if ($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Withdrawal Form -->
        <form action="{{ route('withdraw.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <!-- Payment Method -->
            <div class="glass-card-item rounded-2xl p-4">
                <label class="block text-xs font-bold text-slate-700 mb-2">Method</label>
                <div
                    class="flex items-center bg-white/80 border border-slate-200 rounded-xl px-3 py-1 focus-within:ring-2 focus-within:ring-indigo-400">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2 flex-shrink-0">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <select name="method" required
                        class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-2">
                        <option value="" disabled selected>Select One</option>
                        <option value="Easypaisa" {{ old('method') == 'Easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                        <option value="JazzCash" {{ old('method') == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                    </select>
                </div>
            </div>

            <!-- Amount -->
            <div class="glass-card-item rounded-2xl p-4">
                <label class="block text-xs font-bold text-slate-700 mb-2">Amount</label>
                <div
                    class="flex items-center bg-white/80 border border-slate-200 rounded-xl px-3 py-1 focus-within:ring-2 focus-within:ring-indigo-400">
                    <div
                        class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center mr-2 flex-shrink-0">
                        <i class="fa-solid fa-coins text-sm"></i>
                    </div>
                    <input type="number" step="1" min="{{ $minWithdrawal }}" name="amount"
                        value="{{ old('amount') }}" placeholder="Enter Amount..." required
                        class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-2">
                </div>
                <div class="flex justify-between items-center text-[10px] text-slate-400 mt-2">
                    <span>Available: <strong class="text-slate-700">Rs
                            {{ number_format($wallet->withdrawable_balance, 2) }}</strong></span>
                    <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                        Min: Rs {{ $minWithdrawal }} (Withdrawal #{{ $withdrawalCount + 1 }})
                    </span>
                </div>
            </div>

            <!-- Account Title & Number -->
            <div class="glass-card-item rounded-2xl p-4">
                <label class="block text-xs font-bold text-slate-700 mb-2">Account Title & Number</label>
                <div
                    class="flex items-center bg-white/80 border border-slate-200 rounded-xl px-3 py-1 focus-within:ring-2 focus-within:ring-indigo-400">
                    <div
                        class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mr-2 flex-shrink-0">
                        <i class="fa-solid fa-user-check text-sm"></i>
                    </div>
                    <input type="text" name="account_details" value="{{ old('account_details') }}"
                        placeholder="e.g. Ali Khan - 03001234567" required
                        class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-2">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-sm tracking-wide">
                <span>Submit</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

    </div>
@endsection
