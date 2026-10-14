@extends('layouts.app')

@php
    $headerTitle = 'Payment Approvals';
@endphp

@push('scripts')
    <!-- jQuery & DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <style>
        /* Custom Canva ADS Theme for DataTables */
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

        /* Clean Table Header */
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

        /* Soft Rounded Glass Rows */
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

        /* Pagination & Info */
        .dataTables_wrapper .dataTables_info {
            font-size: 10px;
            color: #64748b;
            padding-top: 14px;
            font-weight: 500;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 10px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 10px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            background: rgba(255, 255, 255, 0.8) !important;
            color: #475569 !important;
            padding: 4px 10px !important;
            margin: 0 2px !important;
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #eef2ff !important;
            border-color: #6366f1 !important;
            color: #4f46e5 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 3px 8px rgba(79, 70, 229, 0.25);
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#paymentsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search payment records..."
                }
            });
        });

        function openModalFromBtn(btn) {
            const user = btn.getAttribute('data-user');
            const pkg = btn.getAttribute('data-pkg');
            const amount = btn.getAttribute('data-amount');
            const method = btn.getAttribute('data-method');
            const sender = btn.getAttribute('data-sender');
            const trx = btn.getAttribute('data-trx');
            const date = btn.getAttribute('data-date');
            const screenshot = btn.getAttribute('data-screenshot');
            const status = btn.getAttribute('data-status');
            const id = btn.getAttribute('data-id');

            document.getElementById('modalUser').innerText = user;
            document.getElementById('modalPackage').innerText = pkg;
            document.getElementById('modalAmount').innerText = 'Rs ' + amount;
            document.getElementById('modalMethod').innerText = method;
            document.getElementById('modalSender').innerText = sender || 'N/A';
            document.getElementById('modalTrx').innerText = trx || 'N/A';
            document.getElementById('modalDate').innerText = date;

            const proofBox = document.getElementById('modalProofBox');
            if (screenshot && screenshot.length > 0) {
                proofBox.innerHTML = '<a href="/storage/' + screenshot + '" target="_blank" class="block mt-2">' +
                    '<img src="/storage/' + screenshot + '" class="max-h-48 rounded-2xl mx-auto border border-slate-200/80 shadow-md hover:scale-[1.02] transition" alt="Proof">' +
                    '<span class="block text-center text-[10px] text-indigo-600 font-bold mt-1.5">Click to preview full proof &rarr;</span>' +
                    '</a>';
            } else {
                proofBox.innerHTML = '<div class="p-3 bg-slate-100/70 rounded-xl text-center text-slate-400 italic text-[11px]">No screenshot attached.</div>';
            }

            const actionsBox = document.getElementById('modalActions');
            if (status === 'pending') {
                actionsBox.innerHTML = '<div class="flex space-x-2.5 pt-3 border-t border-slate-200/60">' +
                    '<form action="/admin/payments/' + id + '/approve" method="POST" class="flex-1">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<button type="submit" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);" class="w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-md hover:opacity-95 transition">Approve & Activate</button>' +
                    '</form>' +
                    '<form action="/admin/payments/' + id + '/reject" method="POST" class="flex-1">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-2.5 rounded-xl text-xs shadow-md transition">Reject</button>' +
                    '</form>' +
                    '</div>';
            } else {
                actionsBox.innerHTML = '<div class="text-center pt-2 text-xs font-bold text-slate-500">Status: <span class="capitalize text-slate-800">' + status + '</span></div>';
            }

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
@endpush

@section('content')
<div class="space-y-4 pt-1">
    <!-- Header Summary -->
    <div class="flex items-center justify-between px-1">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Payment Requests</h2>
            <p class="text-[11px] text-slate-500">Review payment proofs & approve packages</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 bg-white/80 shadow-sm border border-indigo-100 text-indigo-700 rounded-full">
            {{ $payments->count() }} Records
        </span>
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

    <!-- Themed Table Container -->
    <div class="glass-card-item rounded-3xl p-4 overflow-x-auto shadow-md">
        <table id="paymentsTable" class="w-full text-left display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $paymentItem)
                    <tr>
                        <td class="font-mono text-slate-400 font-bold">#{{ $paymentItem->id }}</td>
                        <td>
                            <div class="font-extrabold text-slate-800 text-xs">{{ $paymentItem->user->name }}</div>
                            <div class="text-[10px] text-slate-400 truncate max-w-[120px]">{{ $paymentItem->user->email }}</div>
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $paymentItem->package->name }}
                            </span>
                        </td>
                        <td class="font-extrabold text-slate-900">
                            Rs {{ number_format($paymentItem->amount, 0) }}
                        </td>
                        <td>
                            @if($paymentItem->status === 'approved')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                </span>
                            @elseif($paymentItem->status === 'pending')
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
                            <button type="button"
                                    data-id="{{ $paymentItem->id }}"
                                    data-user="{{ $paymentItem->user->name }} ({{ $paymentItem->user->email }})"
                                    data-pkg="{{ $paymentItem->package->name }}"
                                    data-amount="{{ number_format($paymentItem->amount, 0) }}"
                                    data-method="{{ $paymentItem->payment_method }}"
                                    data-sender="{{ $paymentItem->sender_number }}"
                                    data-trx="{{ $paymentItem->trx_id }}"
                                    data-date="{{ $paymentItem->created_at->format('Y-m-d H:i') }}"
                                    data-screenshot="{{ $paymentItem->screenshot ?? '' }}"
                                    data-status="{{ $paymentItem->status }}"
                                    onclick="openModalFromBtn(this)"
                                    class="px-3 py-1.5 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:opacity-90 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1">
                                <span>Review</span>
                                <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Frosted Glass Modal for Payment Review -->
<div id="detailsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
    <div class="glass-card-item bg-white/95 rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3.5 relative border border-white">
        <!-- Close Button -->
        <button type="button" onclick="closeModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div>
            <h3 class="text-sm font-black text-slate-900">Payment Verification</h3>
            <p class="text-[10px] text-slate-400">Review transaction details before activation</p>
        </div>

        <div class="space-y-1.5 text-xs text-slate-700 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100">
            <div class="flex justify-between"><span class="text-slate-400">User:</span> <strong id="modalUser" class="text-slate-900"></strong></div>
            <div class="flex justify-between"><span class="text-slate-400">Plan:</span> <strong id="modalPackage" class="text-indigo-600 font-bold"></strong></div>
            <div class="flex justify-between"><span class="text-slate-400">Amount:</span> <strong id="modalAmount" class="text-slate-900 font-extrabold"></strong></div>
            <div class="flex justify-between"><span class="text-slate-400">Method:</span> <span id="modalMethod" class="font-semibold text-slate-800"></span></div>
            <div class="flex justify-between"><span class="text-slate-400">Sender:</span> <span id="modalSender" class="font-mono text-slate-800"></span></div>
            <div class="flex justify-between"><span class="text-slate-400">TRX ID:</span> <span id="modalTrx" class="font-mono font-bold text-indigo-600"></span></div>
            <div class="flex justify-between"><span class="text-slate-400">Date:</span> <span id="modalDate" class="text-[10px] text-slate-500"></span></div>
        </div>

        <div id="modalProofBox" class="text-center"></div>

        <div id="modalActions"></div>
    </div>
</div>
@endsection
