@extends('layouts.admin')

@php
    $title = 'User Details — ' . $user->name;
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
            padding: 6px 14px;
            font-size: 11px;
            outline: none;
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
            $('.detail-datatable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search records..."
                }
            });
        });

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-[#3b49df]', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            });

            const activeContent = document.getElementById(tabId);
            const activeBtn = document.getElementById('btn-' + tabId);

            if (activeContent) activeContent.classList.remove('hidden');
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200');
                activeBtn.classList.add('bg-[#3b49df]', 'text-white', 'shadow-md');
            }
        }
    </script>
@endpush

@section('admin_content')
    <div class="space-y-6 pb-12">

        <!-- Flash Alerts -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ================= 1. PRIMARY PROFILE BANNER ================= -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <!-- Left: Avatar & Identity -->
                <div class="flex items-center space-x-4">
                    <div
                        class="w-20 h-20 rounded-full p-1 bg-gradient-to-tr from-cyan-400 via-indigo-500 to-purple-600 shadow-md flex-shrink-0">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                            class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">{{ $user->name }}
                            </h1>

                            <!-- Account Status Badge -->
                            @if ($accountStatus === 'Approved')
                                <span
                                    class="px-3 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 inline-flex items-center">
                                    <i class="fa-solid fa-check text-[9px] mr-1"></i> Approved
                                </span>
                            @elseif($accountStatus === 'Pending')
                                <span
                                    class="px-3 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-700 border border-amber-200 inline-flex items-center animate-pulse">
                                    <i class="fa-solid fa-clock text-[9px] mr-1"></i> Pending Payment
                                </span>
                            @elseif($accountStatus === 'Rejected')
                                <span
                                    class="px-3 py-0.5 rounded-full text-[11px] font-black bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center">
                                    <i class="fa-solid fa-xmark text-[9px] mr-1"></i> Rejected
                                </span>
                            @else
                                <span
                                    class="px-3 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500">
                                    Inactive
                                </span>
                            @endif

                            <!-- Referral Level -->
                            <span
                                class="px-3 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 inline-flex items-center">
                                <i class="fa-solid fa-layer-group text-[9px] mr-1 text-indigo-500"></i> Level
                                {{ $user->referral_level }}
                            </span>
                        </div>

                        <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1 pt-0.5">
                            <span class="inline-flex items-center"><i
                                    class="fa-regular fa-envelope text-slate-400 mr-1.5"></i>{{ $user->email }}</span>
                            <span class="inline-flex items-center"><i
                                    class="fa-solid fa-phone text-slate-400 mr-1.5"></i>{{ $user->phone ?? 'No phone' }}</span>
                            <span class="inline-flex items-center"><i
                                    class="fa-regular fa-calendar text-slate-400 mr-1.5"></i>Joined:
                                {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Admin Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Edit User -->
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                        class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-xl text-xs shadow-sm flex items-center space-x-1.5 transition">
                        <i class="fa-solid fa-user-pen text-xs"></i>
                        <span>Edit User</span>
                    </a>

                    <!-- Login As User -->
                    <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" class="inline-block">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Log in as {{ $user->name }}? You will be redirected to their dashboard.');"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-user-secret"></i>
                            <span>Login as User</span>
                        </button>
                    </form>

                    <!-- Adjust Balance Modal Trigger -->
                    <button type="button" onclick="document.getElementById('balanceModal').classList.remove('hidden')"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-sm flex items-center space-x-1.5 transition">
                        <i class="fa-solid fa-circle-plus text-xs"></i>
                        <span>Adjust Balance</span>
                    </button>

                    @if ($latestPayment && $latestPayment->status === 'pending')
                        <form action="{{ route('admin.payments.approve', $latestPayment->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-xl text-xs shadow-sm transition">
                                Approve Plan
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-extrabold rounded-xl text-xs shadow-sm transition">
                            Reject
                        </button>
                    @endif
                </div>

            </div>
        </div>

        <!-- ================= 2. HIERARCHY & ACCOUNT METADATA GRID ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">

            <!-- Referral Code -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Referral Code</span>
                <div class="text-sm font-mono font-black text-indigo-600 mt-1">{{ $user->referral_code }}</div>
            </div>

            <!-- Direct Upline Sponsor -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Direct Upline
                    (Sponsor)</span>
                @if ($user->referrer)
                    <a href="{{ route('admin.users.show', $user->referrer->id) }}"
                        class="text-sm font-bold text-slate-800 hover:text-indigo-600 hover:underline mt-1 truncate block"
                        title="{{ $user->referrer->name }} ({{ $user->referrer->email }})">
                        {{ $user->referrer->name }} &rarr;
                    </a>
                @else
                    <span class="text-sm text-slate-400 italic mt-1 block">Root / Direct</span>
                @endif
            </div>

            <!-- Selected Package -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Selected Package</span>
                <div class="text-sm font-black text-slate-800 mt-1">
                    {{ $user->activePackage?->package?->name ?? 'No Active Plan' }}</div>
            </div>

            <!-- Package Expiry -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Package Expiry</span>
                <div class="text-sm font-bold text-slate-800 mt-1">
                    {{ $user->activePackage ? $user->activePackage->expires_at->format('d M, Y') : 'N/A' }}
                </div>
            </div>

            <!-- Team Members Count -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Team Members</span>
                <div class="text-sm font-black text-slate-800 mt-1">{{ count($teamMembers) }} Direct</div>
            </div>

            <!-- Task Lock Status -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Task Eligibility</span>
                <div class="text-sm font-black mt-1 {{ $eligibility['unlocked'] ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $eligibility['unlocked'] ? 'Unlocked' : 'Locked' }}
                </div>
            </div>

        </div>

        <!-- Rejection Note Alert (if rejected) -->
        @if ($latestPayment && $latestPayment->status === 'rejected' && $latestPayment->admin_note)
            <div
                class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 flex items-start space-x-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 text-sm"></i>
                <div>
                    <strong>Rejection Reason on Record:</strong>
                    <p class="font-mono mt-1 text-slate-700 bg-white/80 p-2.5 rounded-xl border border-rose-100">
                        "{{ $latestPayment->admin_note }}"</p>
                </div>
            </div>
        @endif

        <!-- ================= 3. 5 FINANCIAL METRIC CARDS ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

            <!-- Main Balance -->
            <div class="bg-[#3b49df] text-white rounded-3xl p-5 shadow-sm flex flex-col justify-between min-h-[110px]">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-100">Main Balance</span>
                <div class="text-2xl font-black mt-1">Rs {{ number_format($wallet->withdrawable_balance ?? 0, 2) }}</div>
                <span class="text-[10px] text-indigo-200 font-medium">Withdrawable</span>
            </div>

            <!-- Rewards Balance -->
            <div class="bg-[#7c3aed] text-white rounded-3xl p-5 shadow-sm flex flex-col justify-between min-h-[110px]">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-100">Rewards Balance</span>
                <div class="text-2xl font-black mt-1">Rs {{ number_format($wallet->rewards_balance ?? 0, 2) }}</div>
                <span class="text-[10px] text-purple-200 font-medium">Non-withdrawable</span>
            </div>

            <!-- Total Deposits -->
            <div
                class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm flex flex-col justify-between min-h-[110px]">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Total Deposits</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rs {{ number_format($totalDeposited, 2) }}</div>
                <span class="text-[10px] text-emerald-600 font-bold">Approved Plans</span>
            </div>

            <!-- Total Withdrawals -->
            <div
                class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm flex flex-col justify-between min-h-[110px]">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Total Withdrawals</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rs {{ number_format($totalWithdrawn, 2) }}</div>
                <span class="text-[10px] text-rose-500 font-bold">Disbursed</span>
            </div>

            <!-- Referral Earnings -->
            <div
                class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm col-span-2 sm:col-span-1 flex flex-col justify-between min-h-[110px]">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Referral Earnings</span>
                <div class="text-2xl font-black text-slate-900 mt-1">Rs
                    {{ number_format($wallet->referral_earnings ?? 0, 2) }}</div>
                <span class="text-[10px] text-indigo-600 font-bold">Team Bonuses</span>
            </div>

        </div>

        <!-- ================= 4. TAB NAVIGATION ================= -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200/80 pb-3 text-xs font-bold pt-2">
            <button type="button" id="btn-tab-payments" onclick="switchTab('tab-payments')"
                class="tab-btn px-4 py-2.5 rounded-2xl transition bg-[#3b49df] text-white shadow-md">
                <i class="fa-solid fa-credit-card mr-1.5"></i> Payment & Receipts ({{ count($payments) }})
            </button>

            <button type="button" id="btn-tab-team" onclick="switchTab('tab-team')"
                class="tab-btn px-4 py-2.5 rounded-2xl transition bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                <i class="fa-solid fa-users mr-1.5"></i> Team Members ({{ count($teamMembers) }})
            </button>

            <button type="button" id="btn-tab-commissions" onclick="switchTab('tab-commissions')"
                class="tab-btn px-4 py-2.5 rounded-2xl transition bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                <i class="fa-solid fa-receipt mr-1.5"></i> Referral Commissions ({{ count($referralCommissions) }})
            </button>

            <button type="button" id="btn-tab-tasks" onclick="switchTab('tab-tasks')"
                class="tab-btn px-4 py-2.5 rounded-2xl transition bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                <i class="fa-solid fa-list-check mr-1.5"></i> Task Activity ({{ count($taskLogs) }})
            </button>

            <button type="button" id="btn-tab-transactions" onclick="switchTab('tab-transactions')"
                class="tab-btn px-4 py-2.5 rounded-2xl transition bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Full Ledger ({{ count($transactions) }})
            </button>
        </div>

        <!-- ================= TAB 1: PAYMENTS & RECEIPTS ================= -->
        <div id="tab-payments" class="tab-content space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-receipt mr-2 text-indigo-600"></i> Package Payment Submissions & Receipts
                </h3>

                @forelse($payments as $p)
                    <div
                        class="mb-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col md:flex-row md:items-start justify-between gap-5">
                        <div class="space-y-2 text-xs flex-1">
                            <div class="flex items-center space-x-2.5">
                                <span
                                    class="font-black text-base text-slate-900">{{ $p->package?->name ?? 'Package' }}</span>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                {{ $p->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($p->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                                <span class="text-slate-400 text-[10px] uppercase font-mono">Type:
                                    {{ $p->type }}</span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-2">
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">Amount</span>
                                    <strong class="text-slate-900">Rs {{ number_format($p->amount, 0) }}</strong>
                                </div>
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">Gateway</span>
                                    <span class="font-bold text-slate-800">{{ $p->payment_method }}</span>
                                </div>
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">Sender
                                        Number</span> <span
                                        class="font-mono font-bold text-slate-700">{{ $p->sender_number }}</span></div>
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">TRX ID</span> <span
                                        class="font-mono font-black text-indigo-600">{{ $p->trx_id }}</span></div>
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">Submitted</span>
                                    <span>{{ $p->created_at->format('d M, Y H:i') }}</span>
                                </div>
                                <div><span class="text-slate-400 block text-[10px] font-bold uppercase">Reviewed</span>
                                    <span>{{ $p->reviewed_at ? $p->reviewed_at->format('d M, Y H:i') : 'Pending' }}</span>
                                </div>
                                <div class="col-span-2 truncate"><span
                                        class="text-slate-400 block text-[10px] font-bold uppercase">Reviewer</span>
                                    <span>{{ $p->reviewer?->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if ($p->admin_note)
                                <div
                                    class="text-xs text-rose-700 bg-rose-50 p-3 rounded-xl mt-2 border border-rose-100 font-mono">
                                    <strong>Admin Note:</strong> {{ $p->admin_note }}
                                </div>
                            @endif
                        </div>

                        <!-- Receipt Preview -->
                        <div class="flex-shrink-0 text-center">
                            @if ($p->screenshot)
                                @php
                                    $cleanReceipt = preg_replace('/^payments\//', '', $p->screenshot);
                                    $receiptUrl = route('receipts.show', $cleanReceipt);
                                @endphp
                                <a href="{{ $receiptUrl }}" target="_blank" class="block group">
                                    <img src="{{ $receiptUrl }}" alt="Receipt"
                                        class="h-32 w-28 object-cover rounded-2xl border-2 border-slate-300 shadow-md mx-auto group-hover:opacity-90 transition">
                                    <span class="text-[11px] text-indigo-600 font-bold block mt-1.5 hover:underline">
                                        <i class="fa-solid fa-up-right-and-down-left-from-center mr-1"></i> View Full
                                    </span>
                                </a>
                                <a href="{{ $receiptUrl }}" download="receipt-{{ $p->id }}.jpg"
                                    class="mt-1 inline-block text-[11px] text-slate-500 hover:text-slate-800 underline">
                                    <i class="fa-solid fa-download mr-1"></i> Download
                                </a>
                            @else
                                <div
                                    class="h-28 w-28 rounded-2xl bg-slate-200 border border-slate-300 flex items-center justify-center text-xs text-slate-400 italic">
                                    No Receipt
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">
                        No payment submissions on record for this user.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= TAB 2: TEAM MEMBERS ================= -->
        <div id="tab-team" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 overflow-x-auto">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-users mr-2 text-indigo-600"></i> Direct Referral Team Members
                </h3>

                <table class="detail-datatable w-full text-left text-xs" style="width:100%">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider">
                            <th>Member Details</th>
                            <th>Mobile</th>
                            <th>Package</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Commission Generated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teamMembers as $m)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $m->id) }}"
                                        class="font-black text-slate-900 hover:text-indigo-600 hover:underline">
                                        {{ $m->name }}
                                    </a>
                                    <span class="block text-[11px] text-slate-400">{{ $m->email }}</span>
                                </td>
                                <td class="font-mono text-slate-600">{{ $m->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="font-bold text-indigo-700">
                                        {{ $m->activePackage?->package?->name ?? 'No Plan' }}
                                    </span>
                                </td>
                                <td>{{ $m->created_at->format('d M, Y') }}</td>
                                <td>
                                    @if ($m->activePackage)
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Approved</span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">Unverified</span>
                                    @endif
                                </td>
                                <td class="font-black text-emerald-600">
                                    Rs {{ number_format($m->commission_generated ?? 0, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $m->id) }}"
                                        class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                        Profile &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= TAB 3: REFERRAL COMMISSIONS ================= -->
        <div id="tab-commissions" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 overflow-x-auto">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-receipt mr-2 text-indigo-600"></i> Referral & Joining Commission Ledger
                </h3>

                <table class="detail-datatable w-full text-left text-xs" style="width:100%">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider">
                            <th>Date & Time</th>
                            <th>Commission Receiver</th>
                            <th>Description / Origin</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($referralCommissions as $tx)
                            <tr>
                                <td class="font-mono text-slate-500">{{ $tx->created_at->format('d M, Y H:i') }}</td>
                                <td class="font-bold text-slate-800">
                                    {{ $tx->user_id === $user->id ? $user->name . ' (This User)' : 'Upline Sponsor' }}
                                </td>
                                <td class="text-slate-700 font-medium">{{ $tx->description }}</td>
                                <td class="font-black text-emerald-600">+Rs {{ number_format($tx->amount, 2) }}</td>
                                <td>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        Credited
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= TAB 4: TASK ACTIVITY ================= -->
        <div id="tab-tasks" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Task Activity & Package Limits</h3>
                        <p class="text-xs text-slate-500">Live eligibility engine & verified ad completions</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-indigo-600">Daily Limit:
                            {{ $user->effective_daily_task_limit }} Ads/Day</span>
                        <span class="block text-[10px] text-slate-400">{{ $tasksCompletedToday }} watched today •
                            {{ $totalTasksCompleted }} lifetime</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="detail-datatable w-full text-left text-xs" style="width:100%">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider">
                                <th>Date / Timestamp</th>
                                <th>Ad Title</th>
                                <th>Watch Duration</th>
                                <th>Reward Credited</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taskLogs as $log)
                                <tr>
                                    <td class="font-mono text-slate-500">{{ $log->created_at->format('d M, Y H:i:s') }}
                                    </td>
                                    <td class="font-bold text-slate-800">{{ $log->task?->title ?? 'Advertising Task' }}
                                    </td>
                                    <td class="text-slate-600">{{ $log->task?->duration_seconds ?? 15 }}s</td>
                                    <td class="font-black text-emerald-600">+Rs
                                        {{ number_format($log->reward_earned, 2) }}</td>
                                    <td>
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                            Verified
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= TAB 5: COMPLETE FINANCIAL LEDGER ================= -->
        <div id="tab-transactions" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 overflow-x-auto">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-list-check mr-2 text-indigo-600"></i> Complete Financial Audit Ledger
                </h3>

                <table class="detail-datatable w-full text-left text-xs" style="width:100%">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider">
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Entry Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td class="font-mono text-slate-500">{{ $t->created_at->format('d M, Y H:i') }}</td>
                                <td>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ in_array($t->type, ['task_reward', 'referral_bonus', 'manual_add', 'transfer_in']) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ str_replace('_', ' ', $t->type) }}
                                    </span>
                                </td>
                                <td class="text-slate-800 font-medium">{{ $t->description }}</td>
                                <td
                                    class="font-black {{ $t->balance_type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $t->balance_type === 'credit' ? '+' : '-' }}Rs {{ number_format($t->amount, 2) }}
                                </td>
                                <td class="font-mono text-[10px] uppercase text-slate-400">
                                    {{ $t->balance_type }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ================= MODALS ================= -->

    <!-- 1. Balance Adjustment Modal -->
    <div id="balanceModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl space-y-4 relative border border-slate-100">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black text-slate-900">Manage Balance — {{ $user->name }}</h3>
                <button type="button" onclick="document.getElementById('balanceModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.adjustBalance', $user->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Action Type</label>
                    <select name="type"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
                        <option value="add">Add Balance (+)</option>
                        <option value="subtract">Deduct Balance (-)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Wallet Target</label>
                    <select name="wallet_type"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
                        <option value="main">Main Balance (Withdrawable)</option>
                        <option value="rewards">Rewards Balance (Non-withdrawable)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Amount (PKR)</label>
                    <input type="number" step="0.5" min="1" name="amount" required
                        placeholder="Enter amount..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Remark / Reason</label>
                    <input type="text" name="remark" placeholder="e.g. Special bonus adjustment..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow transition">
                    Apply Balance Adjustment
                </button>
            </form>
        </div>
    </div>

    <!-- 2. Rejection Modal -->
    @if ($latestPayment && $latestPayment->status === 'pending')
        <div id="rejectModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl space-y-4 relative border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-black text-rose-600">Reject Account Payment</h3>
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('admin.payments.reject', $latestPayment->id) }}" method="POST"
                    class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Rejection Reason</label>
                        <textarea name="admin_note" rows="3" required
                            placeholder="Enter clear reason (e.g. Invalid TID, blurred screenshot)..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-xs shadow transition">
                        Confirm Rejection
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
