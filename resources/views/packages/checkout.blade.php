@extends('layouts.app')

@php
    $headerTitle = isset($isUpgrade) && $isUpgrade ? 'Upgrade Plan Payment' : 'Deposit / Payment';
    $finalPrice = $amountToPay ?? $package->price;
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-10">

    <!-- 1. TOP PACKAGE & PRICE BADGE -->
    <div class="flex justify-center">
        <div style="background: linear-gradient(135deg, #6366f1 0%, #7c3aed 50%, #00c4cc 100%);"
             class="rounded-3xl px-6 py-4 text-center text-white shadow-xl min-w-[240px] max-w-xs">

            <div class="text-[11px] font-bold uppercase tracking-wider text-cyan-200">
                {{ isset($isUpgrade) && $isUpgrade ? 'Plan Upgrade' : 'Package Activation' }}
            </div>

            <h2 class="text-base font-black tracking-tight underline underline-offset-4 decoration-white/40 mt-0.5">
                CanvaEdits {{ $package->name }}
            </h2>

            <div class="text-2xl font-black mt-1 tracking-tight">
                Rs {{ number_format($finalPrice, 2) }}
            </div>

            @if(isset($isUpgrade) && $isUpgrade && isset($currentPackage))
                <div class="text-[10px] bg-white/20 px-2.5 py-1 rounded-full font-bold mt-1.5 inline-block">
                    Upgrade from {{ $currentPackage->name }} (Rs {{ number_format($currentPackage->price) }})
                </div>
            @endif
        </div>
    </div>

    <!-- 2. SAME WALLET DETAILS CARD (EasyPaisa Only) -->
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
            <span id="accountNumberDisplay" class="underline underline-offset-4 select-all cursor-pointer">03714780429</span>
            <button type="button" onclick="copyAccountNumber()"
                    class="ml-1 w-8 h-8 rounded-xl bg-white/25 hover:bg-white/35 active:scale-90 transition flex items-center justify-center text-sm shadow">
                <i id="copyIcon" class="fa-regular fa-copy"></i>
            </button>
        </div>

        <span id="copyFeedback" class="text-[11px] text-emerald-100 font-semibold block transition-all">
            Tap button or number to copy
        </span>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. PAYMENT SUBMISSION FORM -->
    <form id="paymentForm"
          action="{{ isset($isUpgrade) && $isUpgrade ? route('packages.submitUpgrade', $package->id) : route('packages.submitPayment', $package->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4">
        @csrf

        <input type="hidden" name="payment_method" value="EasyPaisa">
        <input type="hidden" name="amount" value="{{ $finalPrice }}">

        <!-- Transaction ID Field -->
        <div>
            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">
                Transaction ID <span class="text-rose-500">*</span>
            </label>
            <div class="flex items-center bg-white border-2 border-slate-200 rounded-2xl px-4 py-3 focus-within:border-indigo-500 shadow-sm transition">
                <input type="text" name="trx_id" value="{{ old('trx_id') }}" required placeholder="Enter Transaction ID"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-bold focus:outline-none p-0 placeholder:text-slate-400">
            </div>
        </div>

        <!-- Sender Number Field -->
        <div>
            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">
                Sender Number <span class="text-rose-500">*</span>
            </label>
            <div class="flex items-center bg-white border-2 border-slate-200 rounded-2xl px-4 py-3 focus-within:border-indigo-500 shadow-sm transition">
                <input type="text" name="sender_number" value="{{ old('sender_number') }}" required placeholder="Enter Your Mobile Number"
                       class="w-full bg-transparent border-0 text-xs text-slate-800 font-bold focus:outline-none p-0 placeholder:text-slate-400">
            </div>
        </div>

        <!-- Mandatory Payment Screenshot Field -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-extrabold text-slate-800">
                    Upload Payment Screenshot <span class="text-rose-500">* (Mandatory)</span>
                </label>
                <span id="fileBadge" class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                    Required
                </span>
            </div>

            <div class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center hover:border-indigo-400 transition" id="dropArea">
                <input type="file" name="screenshot" id="screenshotInput" accept="image/png, image/jpeg, image/jpg, image/webp" required class="hidden">

                <label for="screenshotInput" class="cursor-pointer flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-2" id="previewIcon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <span id="fileLabel" class="text-xs font-bold text-slate-700">Choose receipt screenshot</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">JPG, JPEG, PNG up to 5MB</span>
                </label>
            </div>
        </div>

        <!-- Notice Box -->
        <div class="p-3.5 bg-slate-800 text-amber-300 rounded-2xl text-xs font-bold shadow flex items-center space-x-2 border border-slate-700">
            <i class="fa-solid fa-triangle-exclamation text-amber-400 text-sm flex-shrink-0"></i>
            <span>After sending payment, upload receipt screenshot to proceed.</span>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                id="submitBtn"
                style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-black py-4 px-6 rounded-2xl shadow-xl hover:opacity-95 active:scale-95 transition-all text-sm tracking-wide text-center block">
            {{ isset($isUpgrade) && $isUpgrade ? 'Submit Upgrade Payment' : 'Submit Payment' }}
        </button>
    </form>

</div>

@push('scripts')
<script>
    function copyAccountNumber() {
        const textToCopy = '03714780429';
        const feedback = document.getElementById('copyFeedback');
        const icon = document.getElementById('copyIcon');

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
                if (feedback) feedback.innerText = 'Select manually: ' + textToCopy;
            }
            document.body.removeChild(tempInput);
        }

        function showSuccess() {
            if (icon) icon.className = 'fa-solid fa-check text-emerald-200';
            if (feedback) {
                feedback.innerText = 'Copied to clipboard: ' + textToCopy;
                feedback.classList.add('text-yellow-200');
            }
            setTimeout(() => {
                if (icon) icon.className = 'fa-regular fa-copy';
                if (feedback) {
                    feedback.innerText = 'Tap button or number to copy';
                    feedback.classList.remove('text-yellow-200');
                }
            }, 3000);
        }
    }

    document.getElementById('accountNumberDisplay').addEventListener('click', copyAccountNumber);

    // Screenshot file preview & validation
    document.getElementById('screenshotInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileLabel = document.getElementById('fileLabel');
        const fileBadge = document.getElementById('fileBadge');
        const previewIcon = document.getElementById('previewIcon');

        if (file) {
            fileLabel.innerText = file.name;
            fileBadge.innerText = 'Uploaded ✓';
            fileBadge.className = 'text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200';
            previewIcon.className = 'w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-2';
            previewIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        }
    });

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const input = document.getElementById('screenshotInput');
        if (!input.files || input.files.length === 0) {
            e.preventDefault();
            alert('Please attach your payment screenshot before submitting.');
        }
    });
</script>
@endpush
@endsection
