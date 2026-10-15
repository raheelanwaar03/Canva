@extends('layouts.app')

@php
    $headerTitle = 'Payment Checkout';
@endphp

@section('content')
    <div class="space-y-4 pt-1">
    
        <!-- Plan Summary Header -->
    <div class="glass-card-item rounded-2xl p-4 text-center">
        @if(isset($isUpgrade) && $isUpgrade)
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                Plan Upgrade
            </span>
            <h2 class="text-xl font-black text-slate-900 mt-1">Upgrade to {{ $package->name }}</h2>
            <div class="text-2xl font-black text-indigo-600 mt-1">
                Rs {{ number_format($amountToPay, 0) }}
            </div>
            <span class="text-[10px] text-slate-400 block mt-0.5">Price difference payable</span>
        @else
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Activating Package</span>
            <h2 class="text-xl font-black text-slate-900 mt-1">{{ $package->name }} Plan</h2>
            <div class="text-2xl font-black text-indigo-600 mt-1">
                Rs {{ number_format($package->price, 0) }}
            </div>
        @endif
    </div>

        <!-- Admin Payment Account Information Box -->
        <div class="ref-gradient-card rounded-2xl p-4 text-white shadow-md space-y-2">
            <h4 class="font-bold text-xs uppercase tracking-wider text-white/90">Official Payment Accounts</h4>

            <div class="bg-white/20 rounded-xl p-2.5 backdrop-blur-md flex justify-between items-center text-xs">
                <div>
                    <div class="font-bold">Easypaisa Account</div>
                    <div class="text-white/80 font-mono">0300-1234567 (Admin Canva ADS)</div>
                </div>
                <button onclick="navigator.clipboard.writeText('03001234567'); alert('Copied Easypaisa number!')"
                    class="px-2 py-1 bg-white/20 rounded-lg text-[10px]">
                    Copy
                </button>
            </div>

            <div class="bg-white/20 rounded-xl p-2.5 backdrop-blur-md flex justify-between items-center text-xs">
                <div>
                    <div class="font-bold">JazzCash Account</div>
                    <div class="text-white/80 font-mono">0301-7654321 (Admin Canva ADS)</div>
                </div>
                <button onclick="navigator.clipboard.writeText('03017654321'); alert('Copied JazzCash number!')"
                    class="px-2 py-1 bg-white/20 rounded-lg text-[10px]">
                    Copy
                </button>
            </div>
            <p class="text-[10px] text-white/80 italic text-center pt-1">Send payment and submit the transaction ID and
                proof screenshot below.</p>
        </div>

        <!-- Proof Submission Form -->
        <form action="{{ route('packages.submitPayment', $package->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-3">
            @csrf

            @if ($errors->any())
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Select Method -->
            <div class="glass-card-item rounded-2xl p-3.5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Method</label>
                <select name="payment_method" required
                    class="w-full bg-white/80 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="Easypaisa">Easypaisa</option>
                    <option value="JazzCash">JazzCash</option>
                </select>
            </div>

            <!-- Sender Mobile Number -->
            <div class="glass-card-item rounded-2xl p-3.5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Sender Mobile / Account Number</label>
                <input type="text" name="sender_number" required placeholder="e.g. 03001234567"
                    class="w-full bg-white/80 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Transaction ID (TID) -->
            <div class="glass-card-item rounded-2xl p-3.5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Transaction ID (TRX / TID)</label>
                <input type="text" name="trx_id" required placeholder="e.g. 1984274920"
                    class="w-full bg-white/80 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Payment Screenshot Upload -->
            <div class="glass-card-item rounded-2xl p-3.5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Payment Screenshot (Optional)</label>
                <input type="file" name="screenshot" accept="image/*"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <!-- Submit Button -->
            <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-sm tracking-wide">
                <span>Submit Payment Proof</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

    </div>
@endsection
