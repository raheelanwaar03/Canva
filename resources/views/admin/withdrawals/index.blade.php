@extends('layouts.admin')

@php
    $title = 'Withdrawal Requests';
@endphp

@section('admin_content')
    <div class="space-y-6">

        <!-- Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Withdrawal Requests</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Approve member payout requests or reject with automatic
                    wallet refunds.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
                    {{ count($withdrawals) }} Total Requests
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

            <!-- Card Header & Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black shadow-inner">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Payout Queue</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Review recipient payment method and account
                            number before transfer.</p>
                    </div>
                </div>

                <!-- Status Tabs & Search -->
                <div class="flex flex-wrap items-center gap-2">
                    @php $currentStatus = request('status', 'all'); @endphp
                    <a href="{{ route('admin.withdrawals.index', ['status' => 'all']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        All
                    </a>
                    <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Approved
                    </a>
                    <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Rejected
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">#ID</th>
                            <th class="px-5 py-3.5">User</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Method & Account</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($withdrawals as $withdrawal)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 font-mono font-black text-slate-400">
                                    #{{ $withdrawal->id }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-800 text-sm">
                                        {{ $withdrawal->user->name ?? 'User #' . $withdrawal->user_id }}</div>
                                    <div class="text-[11px] font-semibold text-indigo-600">
                                        {{ $withdrawal->user->email ?? 'No email' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $withdrawal->user->phone ?? 'No phone' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-black text-slate-800 text-sm">Rs
                                        {{ number_format($withdrawal->amount, 2) }}</div>
                                    @if (isset($withdrawal->charge) && $withdrawal->charge > 0)
                                        <div class="text-[10px] text-slate-400">Fee: Rs
                                            {{ number_format($withdrawal->charge, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-700 flex items-center space-x-1.5">
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-black text-slate-600">
                                            {{ $withdrawal->method ?? ($withdrawal->payment_gateway ?? 'EasyPaisa') }}
                                        </span>
                                    </div>
                                    <div class="font-mono font-extrabold text-slate-800 text-xs mt-1">
                                        {{ $withdrawal->account_number ?? ($withdrawal->account ?? 'N/A') }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-semibold">
                                        {{ $withdrawal->account_name ?? ($withdrawal->account_holder ?? '') }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($withdrawal->status === 'approved' || $withdrawal->status === 1)
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-extrabold border border-emerald-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-check text-[10px] text-emerald-500"></i>
                                            <span>Paid / Approved</span>
                                        </span>
                                    @elseif($withdrawal->status === 'rejected' || $withdrawal->status === 2)
                                        <span
                                            class="px-3 py-1 bg-rose-50 text-rose-700 rounded-xl text-[11px] font-extrabold border border-rose-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-xmark text-[10px] text-rose-500"></i>
                                            <span>Rejected & Refunded</span>
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-50 text-amber-700 rounded-xl text-[11px] font-extrabold border border-amber-200 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-clock text-[10px] text-amber-500"></i>
                                            <span>Pending Review</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-400 font-semibold text-[11px]">
                                    {{ $withdrawal->created_at ? $withdrawal->created_at->format('d M, Y h:i A') : 'N/A' }}
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    @if ($withdrawal->status === 'pending' || $withdrawal->status === 0)
                                        <!-- Approve Form -->
                                        <form method="POST"
                                            action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Confirm payout of Rs {{ number_format($withdrawal->amount, 2) }}?');"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form method="POST"
                                            action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Reject request and refund Rs {{ number_format($withdrawal->amount, 2) }} to user wallet?');"
                                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs border border-rose-200 transition inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 font-bold">
                                    No withdrawal requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($withdrawals, 'links') && $withdrawals->hasPages())
                <div class="pt-4 border-t border-slate-100">
                    {{ $withdrawals->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
