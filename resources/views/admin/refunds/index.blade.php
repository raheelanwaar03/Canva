@extends('layouts.admin')

@php
    $title = 'Refund Requests';
@endphp

@section('admin_content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Refund Requests</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Approve member plan refund applications within their
                    30-day processing window.</p>
            </div>
            <span
                class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
                {{ $refunds->total() }} Total Applications
            </span>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
            <!-- Tabs -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black shadow-inner">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Refund Processing Queue</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Decision must be taken within 30 days of
                            submission.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @php $cur = request('status', 'all'); @endphp
                    <a href="{{ route('admin.refunds.index', ['status' => 'all']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $cur === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        All
                    </a>
                    <a href="{{ route('admin.refunds.index', ['status' => 'pending']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $cur === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.refunds.index', ['status' => 'approved']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $cur === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Approved
                    </a>
                    <a href="{{ route('admin.refunds.index', ['status' => 'rejected']) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $cur === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Rejected
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">#ID</th>
                            <th class="px-5 py-3.5">Applicant Details</th>
                            <th class="px-5 py-3.5">Refund Amount</th>
                            <th class="px-5 py-3.5">Account / Method</th>
                            <th class="px-5 py-3.5">Reason</th>
                            <th class="px-5 py-3.5">Status & Deadline</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($refunds as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 font-mono font-black text-slate-400">#{{ $item->id }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-800 text-sm">{{ $item->user->name }}</div>
                                    <div class="text-[11px] font-semibold text-indigo-600">{{ $item->user->email }}</div>
                                    <div class="text-[10px] text-slate-400">Joined:
                                        {{ $item->user->created_at->format('d M, Y') }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-black text-slate-800 text-sm">Rs
                                        {{ number_format($item->amount, 2) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="px-2 py-0.5 bg-slate-100 text-[10px] uppercase font-black text-slate-700 rounded">
                                        {{ $item->gateway_name }}
                                    </span>
                                    <div class="font-mono font-bold text-slate-800 mt-1">{{ $item->account_number }}</div>
                                    <div class="text-[11px] text-slate-500 font-semibold">{{ $item->account_name }}</div>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <p class="text-xs text-slate-600 line-clamp-2" title="{{ $item->reason }}">
                                        {{ $item->reason }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($item->status === 'approved')
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-extrabold border border-emerald-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            <span>Approved</span>
                                        </span>
                                    @elseif($item->status === 'rejected')
                                        <span
                                            class="px-3 py-1 bg-rose-50 text-rose-700 rounded-xl text-[11px] font-extrabold border border-rose-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-xmark text-rose-500"></i>
                                            <span>Rejected</span>
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-50 text-amber-700 rounded-xl text-[11px] font-extrabold border border-amber-200 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-clock text-amber-500"></i>
                                            <span>Pending</span>
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-1 font-semibold">
                                            Deadline:
                                            {{ $item->decision_deadline ? $item->decision_deadline->format('d M, Y') : '30 Days' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    @if ($item->status === 'pending')
                                        <form method="POST" action="{{ route('admin.refunds.approve', $item->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Approve refund of Rs {{ number_format($item->amount, 2) }}?');"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-sm">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.refunds.reject', $item->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Reject this refund request?');"
                                                class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-xl font-bold text-xs transition">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 font-bold">
                                    No refund requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($refunds->hasPages())
                <div class="pt-4 border-t border-slate-100">
                    {{ $refunds->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
