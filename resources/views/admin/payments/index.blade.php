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

    <script>
        $(document).ready(function() {
            $('#paymentsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search by user, email, or TRX..."
                }
            });
        });

        function openModalFromBtn(btn) {
            // Applicant Information
            document.getElementById('modalUserName').innerText = btn.getAttribute('data-user-name');
            document.getElementById('modalUserEmail').innerText = btn.getAttribute('data-user-email');
            document.getElementById('modalUserPhone').innerText = btn.getAttribute('data-user-phone') || 'Not Provided';

            // Upline Information
            const hasUpline = btn.getAttribute('data-has-upline') === '1';
            const uplineSection = document.getElementById('modalUplineSection');

            if (hasUpline) {
                document.getElementById('modalUplineName').innerText = btn.getAttribute('data-upline-name');
                document.getElementById('modalUplineEmail').innerText = btn.getAttribute('data-upline-email');
                document.getElementById('modalUplineTeam').innerText = btn.getAttribute('data-upline-team') + ' Members';
                document.getElementById('impersonateForm').action = '/admin/users/' + btn.getAttribute('data-upline-id') +
                    '/login-as';
                uplineSection.classList.remove('hidden');
            } else {
                uplineSection.classList.add('hidden');
            }

            // Transaction Details
            document.getElementById('modalPackage').innerText = btn.getAttribute('data-pkg');
            document.getElementById('modalAmount').innerText = 'Rs ' + btn.getAttribute('data-amount');
            document.getElementById('modalMethod').innerText = btn.getAttribute('data-method');
            document.getElementById('modalSender').innerText = btn.getAttribute('data-sender') || 'N/A';
            document.getElementById('modalTrx').innerText = btn.getAttribute('data-trx') || 'N/A';
            document.getElementById('modalDate').innerText = btn.getAttribute('data-date');

            // Screenshot Preview
            const screenshot = btn.getAttribute('data-screenshot');
            const proofBox = document.getElementById('modalProofBox');
            if (screenshot && screenshot.trim().length > 0) {
                const cleanFileName = screenshot.replace(/^payments\//, '');
                const imgUrl = '/receipts/' + cleanFileName;
                proofBox.innerHTML = '<div class="mt-2 text-center">' +
                    '<span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Payment Receipt</span>' +
                    '<a href="' + imgUrl + '" target="_blank" class="block group">' +
                    '<img src="' + imgUrl +
                    '" class="max-h-52 w-auto mx-auto rounded-2xl border-2 border-slate-200 shadow-md object-contain bg-white hover:opacity-90 transition">' +
                    '<span class="inline-flex items-center text-[10px] text-indigo-600 font-bold mt-1.5 hover:underline">Click to view full image &rarr;</span>' +
                    '</a></div>';
            } else {
                proofBox.innerHTML =
                    '<div class="p-2.5 bg-slate-100 rounded-xl text-center text-slate-400 italic text-[11px]">No screenshot attached.</div>';
            }

            // Approval / Rejection Actions
            const status = btn.getAttribute('data-status');
            const id = btn.getAttribute('data-id');
            const actionsBox = document.getElementById('modalActions');

            if (status === 'pending') {
                actionsBox.innerHTML = `
                    <div class="pt-3 border-t border-slate-200/60 space-y-2.5">
                        <form action="/admin/payments/${id}/approve" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);" class="w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-md hover:opacity-95 transition">
                                Approve & Activate
                            </button>
                        </form>

                        <!-- Rejection Form with Reason Note -->
                        <form action="/admin/payments/${id}/reject" method="POST" class="space-y-1.5" onsubmit="return validateRejection(this);">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="text" name="admin_note" required placeholder="Enter rejection reason (e.g. Invalid TID)..."
                                   class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">
                            <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-2 rounded-xl text-xs shadow-sm transition">
                                Reject with Reason
                            </button>
                        </form>
                    </div>
                `;
            } else {
                actionsBox.innerHTML =
                    `<div class="text-center pt-2 text-xs font-bold text-slate-500">Status: <span class="capitalize text-slate-800">${status}</span></div>`;
            }

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
@endpush

@section('content')
    <div class="space-y-4 pt-1 pb-6">
        <div class="flex items-center justify-between px-1">
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Payment Verification</h2>
                <p class="text-[11px] text-slate-500">Review user, upline hierarchy & payment receipts</p>
            </div>
            <span
                class="text-xs font-bold px-3 py-1 bg-white/80 shadow-sm border border-indigo-100 text-indigo-700 rounded-full">
                {{ count($payments) }} Records
            </span>
        </div>

        <!-- Admin Sub-Nav -->
        <div class="grid grid-cols-5 gap-1.5 text-center text-[9px] font-bold">
            <a href="{{ route('admin.payments.index') }}"
                class="p-2 rounded-xl bg-indigo-600 text-white shadow-md">Payments</a>
            <a href="{{ route('admin.withdrawals.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payouts</a>
            <a href="{{ route('admin.packages.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Packages</a>
            <a href="{{ route('admin.tasks.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Ads</a>
            <a href="{{ route('admin.channels.index') }}"
                class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Channels</a>
        </div>

        @if (session('success'))
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="glass-card-item rounded-3xl p-4 overflow-x-auto shadow-md">
            <table id="paymentsTable" class="w-full text-left display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>User Details</th>
                        <th>Upline Sponsor</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $p)
                        @php
                            $userItem = $p->user;
                            $uplineItem = $userItem ? $userItem->referrer : null;
                            $teamCount = $uplineItem ? $uplineItem->referrals()->count() : 0;
                        @endphp
                        <tr>
                            <td class="font-mono text-slate-400 font-bold">#{{ $p->id }}</td>
                            <td>
                                <div class="font-extrabold text-slate-800 text-xs">
                                    {{ $userItem ? $userItem->name : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $userItem ? $userItem->email : 'N/A' }}</div>
                                <div class="text-[10px] text-slate-500 font-mono">
                                    {{ $userItem ? $userItem->phone : 'No phone' }}</div>
                            </td>
                            <td>
                                @if ($uplineItem)
                                    <div class="font-bold text-indigo-700 text-xs">{{ $uplineItem->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $uplineItem->email }}</div>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Direct / Admin</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $p->package ? $p->package->name : 'Plan' }}
                                </span>
                            </td>
                            <td class="font-extrabold text-slate-900">
                                Rs {{ number_format($p->amount, 0) }}
                            </td>
                            <td>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                {{ $p->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($p->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" data-id="{{ $p->id }}" data-status="{{ $p->status }}"
                                    data-user-name="{{ $userItem ? $userItem->name : 'N/A' }}"
                                    data-user-email="{{ $userItem ? $userItem->email : 'N/A' }}"
                                    data-user-phone="{{ $userItem ? $userItem->phone : '' }}"
                                    data-has-upline="{{ $uplineItem ? '1' : '0' }}"
                                    data-upline-id="{{ $uplineItem ? $uplineItem->id : '' }}"
                                    data-upline-name="{{ $uplineItem ? $uplineItem->name : '' }}"
                                    data-upline-email="{{ $uplineItem ? $uplineItem->email : '' }}"
                                    data-upline-team="{{ $teamCount }}"
                                    data-pkg="{{ $p->package ? $p->package->name : 'Plan' }}"
                                    data-amount="{{ number_format($p->amount, 0) }}"
                                    data-method="{{ $p->payment_method }}" data-sender="{{ $p->sender_number }}"
                                    data-trx="{{ $p->trx_id }}"
                                    data-date="{{ $p->created_at ? $p->created_at->format('Y-m-d H:i') : '' }}"
                                    data-screenshot="{{ $p->screenshot }}" onclick="openModalFromBtn(this)"
                                    class="px-3 py-1.5 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:opacity-90 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: User, Upline Info & Payment Proof -->
    <div id="detailsModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
        <div
            class="glass-card-item bg-white/95 rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button type="button" onclick="closeModal()"
                class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h3 class="text-sm font-black text-slate-900">Review Verification Details</h3>

            <!-- Applicant User Details -->
            <div class="space-y-1 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Applicant
                    Information</span>
                <div class="flex justify-between"><span class="text-slate-400">Full Name:</span> <strong id="modalUserName"
                        class="text-slate-800"></strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Email:</span> <span id="modalUserEmail"
                        class="text-slate-700 font-medium"></span></div>
                <div class="flex justify-between"><span class="text-slate-400">Phone:</span> <span id="modalUserPhone"
                        class="font-mono text-slate-700 font-semibold"></span></div>
            </div>

            <!-- Upline Sponsor & Impersonation Section -->
            <div id="modalUplineSection"
                class="space-y-1.5 text-xs bg-indigo-50/70 p-3 rounded-2xl border border-indigo-100">
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block mb-0.5">Direct Upline
                    (Sponsor)</span>
                <div class="flex justify-between"><span class="text-slate-400">Upline Name:</span> <strong
                        id="modalUplineName" class="text-indigo-900"></strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Upline Email:</span> <span
                        id="modalUplineEmail" class="text-indigo-700 font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-slate-400">Team Size:</span> <span id="modalUplineTeam"
                        class="font-bold text-slate-700"></span></div>

                <!-- Login As Upline Action -->
                <form id="impersonateForm" method="POST" class="pt-1.5">
                    @csrf
                    <button type="submit"
                        class="w-full bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold py-1.5 px-3 rounded-xl text-[11px] shadow-sm transition flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-right-to-bracket text-xs text-indigo-600"></i>
                        <span>Login as Upline</span>
                    </button>
                </form>
            </div>

            <!-- Payment Details -->
            <div class="space-y-1 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Transaction
                    Information</span>
                <div class="flex justify-between"><span class="text-slate-400">Package:</span> <strong id="modalPackage"
                        class="text-indigo-600 font-bold"></strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Amount:</span> <strong id="modalAmount"
                        class="text-slate-900 font-extrabold"></strong></div>
                <div class="flex justify-between"><span class="text-slate-400">Method:</span> <span id="modalMethod"
                        class="font-semibold text-slate-800"></span></div>
                <div class="flex justify-between"><span class="text-slate-400">Sender Number:</span> <span
                        id="modalSender" class="font-mono text-slate-800"></span></div>
                <div class="flex justify-between"><span class="text-slate-400">TRX ID:</span> <span id="modalTrx"
                        class="font-mono font-bold text-indigo-600"></span></div>
                <div class="flex justify-between"><span class="text-slate-400">Date:</span> <span id="modalDate"
                        class="text-[10px] text-slate-500"></span></div>
            </div>

            <!-- Screenshot Preview -->
            <div id="modalProofBox"></div>

            <!-- Actions -->
            <div id="modalActions"></div>
        </div>
    </div>
@endsection
