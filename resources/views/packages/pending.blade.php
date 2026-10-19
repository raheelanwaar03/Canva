@extends('layouts.app')

@php
    $headerTitle = 'Payment Verification';
@endphp

@section('content')
    <div class="space-y-5 pt-3 pb-6 text-center">

        <!-- Glowing Pending Icon -->
        <div
            class="w-24 h-24 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 mx-auto flex items-center justify-center text-white shadow-xl border-4 border-white">
            <i class="fa-solid fa-hourglass-half text-3xl animate-pulse"></i>
        </div>

        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment Under Review</h2>
            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                Your payment for the <strong class="text-indigo-600">{{ $pendingPayment->package->name }} Plan</strong> has
                been submitted.
            </p>
        </div>

        <!-- Details Summary Card -->
        <div class="glass-card-item rounded-3xl p-5 text-left space-y-2.5 shadow-md">
            <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                <span class="text-slate-400 font-bold">Package:</span>
                <span class="font-extrabold text-slate-900">{{ $pendingPayment->package->name }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                <span class="text-slate-400 font-bold">Amount Paid:</span>
                <span class="font-black text-indigo-600">Rs {{ number_format($pendingPayment->amount, 0) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                <span class="text-slate-400 font-bold">Method:</span>
                <span class="font-semibold text-slate-800">{{ $pendingPayment->payment_method }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-100">
                <span class="text-slate-400 font-bold">TRX / TID:</span>
                <span class="font-mono font-bold text-slate-800">{{ $pendingPayment->trx_id }}</span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-400 font-bold">Status:</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                    Awaiting Admin Approval
                </span>
            </div>
        </div>

        <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 text-[11px] text-slate-600 space-y-1">
            <p class="font-bold text-indigo-900">What happens next?</p>
            <p>Our administrator is verifying your transaction. Once verified, your 30-day package and daily ads will
                activate automatically, and you will get instant access to your Dashboard.</p>
        </div>

        <!-- Refresh Status Button -->
        <a href="{{ route('packages.pending') }}"
            style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
            class="block text-center text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition text-xs tracking-wide">
            <i class="fa-solid fa-rotate-right mr-1.5"></i> Check Approval Status
        </a>

    </div>
@endsection
