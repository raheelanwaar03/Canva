@extends('layouts.admin')

@php
    $title = 'Users Management';
@endphp

@push('scripts')
    <!-- jQuery & DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <style>
        .dataTables_wrapper .dataTables_length select {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 11px;
            outline: none;
            margin: 0 4px;
        }
        .dataTables_wrapper .dataTables_filter input {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 7px 14px;
            font-size: 12px;
            width: 260px;
            outline: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #3b49df;
            box-shadow: 0 0 0 3px rgba(59, 73, 223, 0.15);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b49df !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                pageLength: 15,
                order: [[0, 'desc']],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search by name or email..."
                }
            });
        });
    </script>
@endpush

@section('admin_content')
<div class="space-y-6">

    <!-- Header & Category Badges -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Users Management</h1>
            <p class="text-xs text-slate-500">Filter and instantly search users by username or email</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 bg-white shadow-sm border border-slate-200 text-indigo-700 rounded-full w-fit">
            {{ count($users) }} Records
        </span>
    </div>

    <!-- FILTER TABS: All, Approved, Pending, Rejected -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200/80 pb-3 text-xs font-bold">
        <a href="{{ route('admin.users.index', ['status' => 'all']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'all' ? 'bg-[#3b49df] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            All Users
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Approved Users
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Pending Requests
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-xl transition {{ $status === 'rejected' ? 'bg-rose-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Rejected Users
        </a>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 overflow-x-auto">
        <table id="usersTable" class="w-full text-left display responsive nowrap text-xs" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider">
                    <th>#ID</th>
                    <th>User Details</th>
                    <th>Direct Upline</th>
                    <th>Referral Level</th>
                    <th>Active Plan</th>
                    <th>Main Balance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    @php
                        $upline = $u->referrer;
                        $hasActive = $u->activePackage;
                        $hasPending = $u->packagePayments->where('status', 'pending')->first();
                        $hasRejected = $u->packagePayments->where('status', 'rejected')->first();
                    @endphp
                    <tr>
                        <td class="font-mono text-slate-400 font-bold">#{{ $u->id }}</td>
                        <td>
                            <div class="font-extrabold text-slate-800 text-xs">{{ $u->name }}</div>
                            <div class="text-[11px] text-indigo-600 font-medium">{{ $u->email }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $u->phone ?? 'No phone' }}</div>
                        </td>
                        <td>
                            @if($upline)
                                <a href="{{ route('admin.users.show', $upline->id) }}" class="font-bold text-slate-700 hover:text-indigo-600 hover:underline">
                                    {{ $upline->name }}
                                </a>
                                <span class="block text-[10px] text-slate-400">{{ $upline->email }}</span>
                            @else
                                <span class="text-slate-400 italic">Direct / Root</span>
                            @endif
                        </td>
                        <td>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                Level {{ $u->referral_level }}
                            </span>
                        </td>
                        <td>
                            @if($hasActive)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    {{ $hasActive->package->name }}
                                </span>
                            @elseif($hasPending)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    Pending ({{ $hasPending->package->name }})
                                </span>
                            @elseif($hasRejected)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                    Rejected
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-400">
                                    No Plan
                                </span>
                            @endif
                        </td>
                        <td class="font-extrabold text-slate-900">
                            Rs {{ number_format($u->wallet?->withdrawable_balance ?? 0, 2) }}
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $u->id) }}"
                               class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 font-bold hover:bg-[#3b49df] hover:text-white transition shadow-sm inline-flex items-center space-x-1">
                                <span>Profile</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
