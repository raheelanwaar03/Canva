@extends('layouts.app')

@php
    $headerTitle = 'Account Status';
@endphp

@section('content')
    <div class="space-y-4 pt-2 pb-8">

        <!-- Rejection Header Card -->
        <div class="glass-card-item rounded-3xl p-5 text-center relative overflow-hidden shadow-md space-y-3">
            <div
                class="w-20 h-20 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center text-3xl shadow-sm border-2 border-rose-200">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Your Account Request Has Been Rejected</h2>
                <p class="text-xs text-slate-500 mt-1">Please review the administrator's reason below, update your details,
                    and resubmit.</p>
            </div>
        </div>

        <!-- Admin Reason Banner -->
        <div class="p-4 bg-rose-50 border-2 border-rose-200 rounded-3xl text-left space-y-1 shadow-sm">
            <div class="flex items-center space-x-2 text-rose-700 font-extrabold text-xs uppercase tracking-wider">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Administrator Rejection Reason:</span>
            </div>
            <p
                class="text-xs text-slate-800 font-medium leading-relaxed bg-white/80 p-3 rounded-2xl border border-rose-100 font-mono">
                "{{ $rejectedPayment->admin_note ?? 'Your payment verification details could not be verified. Please provide valid transaction credentials.' }}"
            </p>
        </div>

        <!-- Resubmission Form (Toggled via button or visible by default) -->
        <div class="glass-card-item rounded-3xl p-5 shadow-md space-y-3.5">
            <div>
                <h3 class="text-sm font-black text-slate-900">Resubmit Payment Request</h3>
                <p class="text-[10px] text-slate-400">Correct the TID, sender number, or upload a clear receipt</p>
            </div>

            <form action="{{ route('packages.resubmit', $rejectedPayment->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-3">
                @csrf

                <!-- Select Package -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Package</label>
                    <select name="package_id" required
                        class="w-full bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg->id }}"
                                {{ $rejectedPayment->package_id == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} — Rs {{ number_format($pkg->price, 0) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Payment Method</label>
                    <select name="payment_method" required
                        class="w-full bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="Easypaisa" {{ $rejectedPayment->payment_method == 'Easypaisa' ? 'selected' : '' }}>
                            Easypaisa</option>
                        <option value="JazzCash" {{ $rejectedPayment->payment_method == 'JazzCash' ? 'selected' : '' }}>
                            JazzCash</option>
                    </select>
                </div>

                <!-- Sender Number -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Sender Mobile / Account Number</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="text" name="sender_number"
                            value="{{ old('sender_number', $rejectedPayment->sender_number) }}" required
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Transaction ID (TRX / TID) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Transaction ID (TRX / TID)</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="text" name="trx_id" value="{{ old('trx_id', $rejectedPayment->trx_id) }}" required
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Receipt Screenshot Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Upload New Payment Screenshot</label>
                    <input type="file" name="screenshot" accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <!-- Submit Button -->
                <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide mt-2">
                    <i class="fa-solid fa-rotate-right mr-1"></i>
                    <span>Resubmit Request</span>
                </button>
            </form>
        </div>

    </div>
@endsection
