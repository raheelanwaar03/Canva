@extends('layouts.app')

@php
    $headerTitle = 'Deposit / Payment';
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-10">

    <!-- 1. Top Package & Price Badge -->
    <div class="flex justify-center">
        <div style="background: linear-gradient(135deg, #6366f1 0%, #7c3aed 50%, #00c4cc 100%);"
             class="rounded-3xl px-6 py-4 text-center text-white shadow-xl min-w-[220px] max-w-xs">
            <h2 class="text-base font-black tracking-tight underline underline-offset-4 decoration-white/40">
                CanvaEdits {{ $package->name }}
            </h2>
            <div class="text-xl font-black mt-1 tracking-tight">
                Rs {{ number_format($amountToPay ?? $package->price, 2) }}
            </div>
            @if(isset($isUpgrade) && $isUpgrade)
                <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-bold block mt-1">Upgrade Difference</span>
            @endif
        </div>
    </div>

    <!-- 2. EasyPaisa Account Card -->
    <div style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);"
         class="rounded-3xl p-6 text-white text-center shadow-2xl relative overflow-hidden space-y-2">

        <h3 class="text-3xl font-black tracking-tight text-white drop-shadow-sm">
            EasyPaisa
        </h3>

        <div class="text-sm font-bold text-white/95">
            Name: <span>Shama</span>
        </div>

        <div class="text-lg font-mono font-black text-white pt-1 flex items-center justify-center space-x-2">
            <span>Number:</span>
            <span id="accountNumberDisplay" class="underline underline-offset-4 select-all">03714780429</span>
            <button type="button" onclick="copyAccountNumber()"
                    class="ml-1 w-8 h-8 rounded-xl bg-white/25 hover:bg-white/35 active:scale-90 transition flex items-center justify-center text-sm shadow">
                <i id="copyIcon" class="fa-regular fa-copy"></i>
            </button>
        </div>

        <span id="copyFeedback" class="text-[11px] text-emerald-100 font-semibold block transition-all">
            Tap the button or number to copy
        </span>
    </div>

    @if ($errors->any())
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. Payment Submission Form -->
    <form action="{{ route('packages.submitPayment', $package->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Fixed Gateway Value -->
        <input type="hidden" name="payment_method" value="EasyPaisa">

        <!-- Transaction ID Field -->
        <div>
            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">Transaction ID</label>
            <div class="flex items-center bg-white border-2 border-slate-200 rounded-2xl px-4 py-3 focus-within:border-indigo-500 shadow-sm transition">
                <input type="text" name="trx_id" value="{{ old('trx_id') }}" required placeholder="Enter Transaction ID"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-bold focus:outline-none p-0 placeholder:text-slate-400">
            </div>
        </div>

        <!-- Sender Number Field -->
        <div>
            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">Sender Number</label>
            <div class="flex items-center bg-white border-2 border-slate-200 rounded-2xl px-4 py-3 focus-within:border-indigo-500 shadow-sm transition">
                <input type="text" name="sender_number" value="{{ old('sender_number') }}" required placeholder="Enter Your Mobile Number"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-bold focus:outline-none p-0 placeholder:text-slate-400">
            </div>
        </div>

        <!-- Upload Screenshot Field -->
        <div>
            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">
                Upload Payment Screenshot <span class="text-rose-500">*</span>
            </label>
            <div class="bg-white border-2 border-slate-200 rounded-2xl p-3 focus-within:border-indigo-500 shadow-sm">
                <input type="file" name="screenshot" id="screenshotInput" accept="image/*" required
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                <span class="text-[10px] text-slate-400 block mt-1.5">Accepted formats: JPG, JPEG, PNG</span>
            </div>
        </div>

        <!-- Notice Box -->
        <div class="p-3.5 bg-slate-800 text-amber-300 rounded-2xl text-xs font-bold shadow flex items-center space-x-2 border border-slate-700">
            <i class="fa-solid fa-triangle-exclamation text-amber-400 text-sm flex-shrink-0"></i>
            <span>After payment, upload screenshot for verification</span>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-black py-4 px-6 rounded-2xl shadow-xl hover:opacity-95 active:scale-95 transition-all text-sm tracking-wide text-center block">
            Submit Payment
        </button>
    </form>

</div>

@push('scripts')
<script>
    function copyAccountNumber() {
        const textToCopy = '03714780429';
        const feedback = document.getElementById('copyFeedback');
        const icon = document.getElementById('copyIcon');

        // Robust clipboard method with document.execCommand fallback
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(fallbackCopy);
        } else {
            fallbackCopy();
        }

        function fallbackCopy() {
            const tempInput = document.createElement('input');
            tempInput.value = textToCopy;
            tempInput.style.position = 'fixed';
            tempInput.style.opacity = '0';
            document.body.appendChild(tempInput);
            tempInput.focus();
            tempInput.select();
            try {
                document.execCommand('copy');
                showSuccess();
            } catch (err) {
                if (feedback) feedback.innerText = 'Please select and copy manually: ' + textToCopy;
            }
            document.body.removeChild(tempInput);
        }

        function showSuccess() {
            if (icon) {
                icon.className = 'fa-solid fa-check text-emerald-200';
            }
            if (feedback) {
                feedback.innerText = 'Copied to clipboard: ' + textToCopy;
                feedback.classList.add('text-yellow-200');
            }
            setTimeout(() => {
                if (icon) icon.className = 'fa-regular fa-copy';
                if (feedback) {
                    feedback.innerText = 'Tap the button or number to copy';
                    feedback.classList.remove('text-yellow-200');
                }
            }, 3000);
        }
    }

    // Allow copying by clicking the number directly as well
    document.getElementById('accountNumberDisplay').addEventListener('click', copyAccountNumber);
</script>
@endpush
@endsection
