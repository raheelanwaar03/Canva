@extends('layouts.app')

@php
    $headerTitle = 'Withdrawals';
@endphp

@push('scripts')
    <!-- jQuery & DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <style>
        .dataTables_wrapper {
            font-family: inherit;
            padding-top: 4px;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 12px;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
        }
        .dataTables_wrapper .dataTables_length select {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 11px;
            outline: none;
            margin: 0 4px;
        }
        .dataTables_wrapper .dataTables_filter input {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 11px;
            color: #1e293b;
            outline: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            background: #ffffff;
        }
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 6px !important;
            border: none !important;
        }
        table.dataTable thead th {
            background: transparent !important;
            color: #475569 !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border: none !important;
            padding: 8px 10px !important;
        }
        table.dataTable tbody tr {
            background: rgba(255, 255, 255, 0.7) !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.04);
            transition: all 0.15s ease;
        }
        table.dataTable tbody tr:hover {
            background: rgba(255, 255, 255, 0.95) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
        }
        table.dataTable tbody td {
            border: none !important;
            padding: 10px 10px !important;
            font-size: 11px !important;
            vertical-align: middle !important;
        }
        table.dataTable tbody tr td:first-child {
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
        }
        table.dataTable tbody tr td:last-child {
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 8px !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#withdrawTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search withdrawals..."
                }
            });
        });
    </script>
@endpush

@section('content')
<div class="space-y-4 pt-1">
    <div class="flex items-center justify-between px-1">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Withdrawal Requests</h2>
            <p class="text-[11px] text-slate-500">Approve payouts or reject with automatic refunds</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 bg-white/80 shadow-sm border border-indigo-100 text-indigo-700 rounded-full">
            {{ $withdrawals->count() }} Total
        </span>
    </div>

    <!-- Admin Sub-Nav -->
    <div class="grid grid-cols-4 gap-1.5 text-center text-[10px] font-bold">
        <a href="{{ route('admin.payments.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payments</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="p-2 rounded-xl bg-indigo-600 text-white shadow-md">Withdrawals</a>
        <a href="{{ route('admin.packages.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Packages</a>
        <a href="{{ route('admin.tasks.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Ads</a>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="glass-card-item rounded-3xl p-4 overflow-x-auto shadow-md">
        <table id="withdrawTable" class="w-full text-left display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method & Account</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $w)
                    <tr>
                        <td class="font-mono text-slate-400 font-bold">#{{ $w->id }}</td>
                        <td>
                            <div class="font-extrabold text-slate-800 text-xs">{{ $w->user->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $w->user->email }}</div>
                        </td>
                        <td class="font-extrabold text-slate-900">
                            Rs {{ number_format($w->amount, 0) }}
                        </td>
                        <td>
                            <div class="font-bold text-indigo-600 text-xs">{{ $w->method }}</div>
                            <div class="text-[10px] text-slate-600 font-mono">{{ $w->account_details }}</div>
                        </td>
                        <td>
                            @if($w->status === 'approved')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                </span>
                            @elseif($w->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Pending
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Rejected
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($w->status === 'pending')
                                <div class="flex space-x-1.5">
                                    <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-[11px] text-slate-400">Processed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
