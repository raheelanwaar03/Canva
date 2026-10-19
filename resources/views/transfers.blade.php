@extends('layouts.app')

@php
    $headerTitle = 'Balance Transfer';
@endphp

@section('content')
    <div class="space-y-4 pt-1 pb-6">

        <!-- Header Balance Card -->
        <div class="ref-gradient-card rounded-3xl p-5 text-white shadow-lg space-y-2 relative overflow-hidden">
            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-white/90 uppercase tracking-wider">Available Main Balance</span>
                <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right-arrow-left text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-black">
                Rs {{ number_format($wallet->withdrawable_balance, 2) }}
            </div>
            <p class="text-[11px] text-white/80">Only Main Balance is transferable. Rewards Balance cannot be transferred.
            </p>
        </div>

        @if (session('success'))
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Transfer Form -->
        <div class="glass-card-item rounded-3xl p-5 shadow-md space-y-3.5">
            <div>
                <h3 class="text-sm font-black text-slate-900">Member-to-Member Transfer</h3>
                <p class="text-[10px] text-slate-400">Transfer fee: Rs 2 + 2% of the amount</p>
            </div>

            <form action="{{ route('transfer.store') }}" method="POST" class="space-y-3">
                @csrf

                <!-- Recipient Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Recipient Member Email</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2 flex-shrink-0 text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="recipient_email" value="{{ old('recipient_email') }}" required
                            placeholder="recipient@example.com"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Transfer Amount -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Amount to Transfer (PKR)</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-indigo-400">
                        <div
                            class="w-7 h-7 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center mr-2 flex-shrink-0 text-xs">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <input type="number" id="transferAmount" name="amount" value="{{ old('amount') }}" step="1"
                            min="10" required placeholder="Min Rs 10" oninput="calculateFee()"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <!-- Dynamic Fee Breakdown Box -->
                <div class="bg-slate-50/90 rounded-2xl p-3 border border-slate-100 text-[11px] space-y-1">
                    <div class="flex justify-between text-slate-500">
                        <span>Transfer Fee (Rs 2 + 2%):</span>
                        <strong id="feeDisplay" class="text-slate-800">Rs 0.00</strong>
                    </div>
                    <div class="flex justify-between text-slate-700 border-t border-slate-200/60 pt-1 font-bold">
                        <span>Total Deducted from You:</span>
                        <strong id="totalDisplay" class="text-indigo-600">Rs 0.00</strong>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-95 transition flex items-center justify-center space-x-2 text-xs tracking-wide">
                    <span>Confirm & Send Balance</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Transfer History Feed -->
        <div class="space-y-2.5 pt-1">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Recent Transfers</h3>

            <div class="space-y-2">
                @forelse($transfers as $tx)
                    @php
                        $isSent = $tx->sender_id === auth()->id();
                    @endphp
                    <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm {{ $isSent ? 'bg-rose-50 text-rose-500' : 'bg-emerald-50 text-emerald-600' }}">
                                <i class="{{ $isSent ? 'fa-solid fa-arrow-up' : 'fa-solid fa-arrow-down' }}"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 leading-tight">
                                    {{ $isSent ? 'Sent Transfer' : 'Received Transfer' }}
                                </h4>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($tx->created_at)->format('d M, Y • h:i A') }}
                                    @if ($isSent)
                                        • Fee: Rs {{ number_format($tx->fee, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs font-black {{ $isSent ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $isSent ? '-' : '+' }}Rs {{ number_format($tx->amount, 2) }}
                            </div>
                            <span
                                class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $isSent ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                        No balance transfers yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function calculateFee() {
                const input = document.getElementById('transferAmount');
                const amount = parseFloat(input.value) || 0;

                if (amount > 0) {
                    const fee = 2.00 + (amount * 0.02);
                    const total = amount + fee;
                    document.getElementById('feeDisplay').innerText = 'Rs ' + fee.toFixed(2);
                    document.getElementById('totalDisplay').innerText = 'Rs ' + total.toFixed(2);
                } else {
                    document.getElementById('feeDisplay').innerText = 'Rs 0.00';
                    document.getElementById('totalDisplay').innerText = 'Rs 0.00';
                }
            }
        </script>
    @endpush
@endsection
