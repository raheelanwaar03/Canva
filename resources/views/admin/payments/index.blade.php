@extends('layouts.admin')

@php
    $title = 'Payment Verification';
@endphp

@section('admin_content')
    <div class="space-y-6">

        <!-- Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Payment Verification</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Review user package payments, deposit proofs, and
                    commission hierarchy.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
                    {{ count($payments) }} Total Records
                </span>
            </div>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Container Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">

            <!-- Card Header & Status Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black shadow-inner">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Deposit Invoices</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Verify transaction IDs and receipt attachments
                            before activating memberships.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @php $currentStatus = request('status', 'all'); @endphp
                    <a href="{{ route('admin.payments.index', ['status' => 'all']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        All
                    </a>
                    <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Approved
                    </a>
                    <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Rejected
                    </a>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">#ID</th>
                            <th class="px-5 py-3.5">User Details</th>
                            <th class="px-5 py-3.5">Upline Sponsor</th>
                            <th class="px-5 py-3.5">Package & Amount</th>
                            <th class="px-5 py-3.5">Transaction Info</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 font-mono font-black text-slate-400">
                                    #{{ $payment->id }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-800 text-sm">
                                        {{ $payment->user->name ?? 'User #' . $payment->user_id }}</div>
                                    <div class="text-[11px] font-semibold text-indigo-600">
                                        {{ $payment->user->email ?? 'No email' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $payment->user->phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    @if (optional($payment->user)->referrer)
                                        <div class="font-bold text-slate-700">{{ $payment->user->referrer->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $payment->user->referrer->email }}</div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Direct (No Upline)</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-[11px] font-extrabold border border-indigo-100 inline-block mb-1">
                                        {{ $payment->package->name ?? 'Package' }}
                                    </span>
                                    <div class="font-black text-slate-800 text-sm">
                                        Rs {{ number_format($payment->amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-700 flex items-center space-x-1.5">
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-black text-slate-600">
                                            {{ $payment->gateway ?? ($payment->payment_method ?? 'EasyPaisa') }}
                                        </span>
                                    </div>
                                    @if ($payment->trx_id || $payment->transaction_id)
                                        <div class="font-mono text-slate-500 text-[11px] mt-1 font-semibold">
                                            Trx: {{ $payment->trx_id ?? $payment->transaction_id }}
                                        </div>
                                    @endif
                                    @if ($payment->screenshot || $payment->proof_image || $payment->attachment)
                                        @php $proof = $payment->screenshot ?? $payment->proof_image ?? $payment->attachment; @endphp
                                        <a href="{{ asset('storage/' . $proof) }}" target="_blank"
                                            class="inline-flex items-center space-x-1 text-sky-600 hover:text-sky-700 text-[11px] font-bold mt-1">
                                            <i class="fa-solid fa-paperclip"></i>
                                            <span>View Receipt</span>
                                        </a>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($payment->status === 'approved' || $payment->status === 'active' || $payment->status === 1)
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-extrabold border border-emerald-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-check text-[10px] text-emerald-500"></i>
                                            <span>Approved</span>
                                        </span>
                                    @elseif($payment->status === 'rejected' || $payment->status === 2)
                                        <span
                                            class="px-3 py-1 bg-rose-50 text-rose-700 rounded-xl text-[11px] font-extrabold border border-rose-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-xmark text-[10px] text-rose-500"></i>
                                            <span>Rejected</span>
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-50 text-amber-700 rounded-xl text-[11px] font-extrabold border border-amber-200 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-clock text-[10px] text-amber-500"></i>
                                            <span>Pending</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                    @if ($payment->status === 'pending' || $payment->status === 0)
                                        <!-- Approve Form -->
                                        <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Approve package payment of Rs {{ number_format($payment->amount, 2) }} for {{ $payment->user->name ?? 'User' }}?');"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Reject this deposit payment proof?');"
                                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs border border-rose-200 transition inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Settled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 font-bold">
                                    No payment receipts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($payments, 'links') && $payments->hasPages())
                <div class="pt-4 border-t border-slate-100">
                    {{ $payments->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
