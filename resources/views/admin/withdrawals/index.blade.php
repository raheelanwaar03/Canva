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

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Input -->
                    <form method="GET" action="" class="relative">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search username or email..."
                            class="bg-slate-50 border border-slate-200 rounded-2xl pl-9 pr-4 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </form>

                    <!-- Status Filter Tabs -->
                    @php $currentStatus = request('status', 'all'); @endphp
                    <a href="{{ route('admin.withdrawals.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        All
                    </a>
                    <a href="{{ route('admin.withdrawals.index', array_merge(request()->query(), ['status' => 'pending'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.withdrawals.index', array_merge(request()->query(), ['status' => 'approved'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        Approved
                    </a>
                    <a href="{{ route('admin.withdrawals.index', array_merge(request()->query(), ['status' => 'rejected'])) }}"
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
                            <th class="px-5 py-3.5">Method & Account (Title & Number)</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($withdrawals as $withdrawal)
                            @php
                                // Retrieve the user's submitted withdrawal account details
                                $rawDetails =
                                    $withdrawal->account_details ??
                                    ($withdrawal->account_number ??
                                        ($withdrawal->account ??
                                            (($withdrawal->account_name
                                                ? $withdrawal->account_name .
                                                    ' - ' .
                                                    ($withdrawal->account_number ?? '')
                                                : null) ??
                                                'N/A')));

                                $userTeamCount = optional($withdrawal->user)->referrals
                                    ? $withdrawal->user->referrals()->count()
                                    : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 font-mono font-black text-slate-400">
                                    #{{ $withdrawal->id }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-800 text-sm">
                                        {{ $withdrawal->user->name ?? 'User #' . $withdrawal->user_id }}
                                    </div>
                                    <div class="text-[11px] font-semibold text-indigo-600">
                                        {{ $withdrawal->user->email ?? 'No email' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $withdrawal->user->phone ?? 'No phone' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-black text-slate-800 text-sm">
                                        Rs {{ number_format($withdrawal->amount, 2) }}
                                    </div>
                                    @if (isset($withdrawal->charge) && $withdrawal->charge > 0)
                                        <div class="text-[10px] text-slate-400">Fee: Rs
                                            {{ number_format($withdrawal->charge, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-700 flex items-center space-x-1.5">
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 rounded text-[10px] uppercase font-black text-slate-600">
                                            {{ $withdrawal->method ?? ($withdrawal->payment_gateway ?? 'Easypaisa') }}
                                        </span>
                                    </div>
                                    <!-- Displays the account title and number submitted in account_details -->
                                    <div
                                        class="font-mono font-extrabold text-indigo-700 text-xs mt-1 bg-indigo-50/60 border border-indigo-100 px-2.5 py-1 rounded-lg inline-block">
                                        <i class="fa-solid fa-address-card mr-1 text-[10px] text-indigo-400"></i>
                                        {{ $rawDetails }}
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
                                <td class="px-5 py-4 text-right space-x-1.5 whitespace-nowrap">
                                    <!-- View Details Button -->
                                    <button type="button"
                                        onclick="openWithdrawalDetailsModal({{ json_encode([
                                            'id' => $withdrawal->id,
                                            'name' => $withdrawal->user->name ?? 'User #' . $withdrawal->user_id,
                                            'email' => $withdrawal->user->email ?? 'N/A',
                                            'phone' => $withdrawal->user->phone ?? 'N/A',
                                            'team_count' => $userTeamCount,
                                            'amount' => 'Rs ' . number_format($withdrawal->amount, 2),
                                            'method' => $withdrawal->method ?? 'Easypaisa',
                                            'account_details' => $rawDetails,
                                            'date' => $withdrawal->created_at ? $withdrawal->created_at->format('d M, Y h:i A') : 'N/A',
                                            'impersonate_url' => optional($withdrawal->user)->id
                                                ? route('admin.users.impersonate', $withdrawal->user->id)
                                                : null,
                                        ]) }})"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                        <span>Details</span>
                                    </button>

                                    @if ($withdrawal->status === 'pending' || $withdrawal->status === 0)
                                        <!-- Approve Form -->
                                        <form method="POST"
                                            action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Confirm payout of Rs {{ number_format($withdrawal->amount, 2) }}?');"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition inline-flex items-center space-x-1">
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
                                                class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs border border-rose-200 transition inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
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

    <!-- ================= WITHDRAWAL DETAILS MODAL ================= -->
    <div id="withdrawalDetailsModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2.5">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-800">Withdrawal Applicant Details</h3>
                </div>
                <button type="button" onclick="closeWithdrawalDetailsModal()"
                    class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">User:</span>
                    <span id="wModalUserName" class="font-extrabold text-slate-800"></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">Email:</span>
                    <span id="wModalUserEmail" class="font-bold text-indigo-600"></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">Registered Phone:</span>
                    <span id="wModalUserPhone" class="font-mono text-slate-700"></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">Total Team Members:</span>
                    <span id="wModalTeamCount"
                        class="font-black text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-lg"></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">Requested Amount:</span>
                    <span id="wModalAmount" class="font-black text-slate-800 text-sm"></span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span class="text-slate-400 font-semibold">Payment Gateway:</span>
                    <span id="wModalMethod" class="font-extrabold uppercase text-slate-700"></span>
                </div>
                <div class="p-3 bg-indigo-50/60 border border-indigo-100 rounded-2xl">
                    <span class="text-slate-400 font-semibold block text-[10px] uppercase mb-1">Target Account Title &
                        Number:</span>
                    <div id="wModalAccountDetails" class="font-mono font-black text-indigo-800 text-sm"></div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <!-- Working Login as User Button -->
                <form id="wImpersonateForm" method="POST" action="">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black transition flex items-center space-x-1.5 shadow-sm active:scale-95">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Login as User</span>
                    </button>
                </form>

                <button type="button" onclick="closeWithdrawalDetailsModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts for Modal -->
    <script>
        function openWithdrawalDetailsModal(data) {
            document.getElementById('wModalUserName').innerText = data.name;
            document.getElementById('wModalUserEmail').innerText = data.email;
            document.getElementById('wModalUserPhone').innerText = data.phone;
            document.getElementById('wModalTeamCount').innerText = data.team_count + ' Members';
            document.getElementById('wModalAmount').innerText = data.amount;
            document.getElementById('wModalMethod').innerText = data.method;
            document.getElementById('wModalAccountDetails').innerText = data.account_details;

            if (data.impersonate_url) {
                document.getElementById('wImpersonateForm').action = data.impersonate_url;
                document.getElementById('wImpersonateForm').classList.remove('hidden');
            } else {
                document.getElementById('wImpersonateForm').classList.add('hidden');
            }

            document.getElementById('withdrawalDetailsModal').classList.remove('hidden');
        }

        function closeWithdrawalDetailsModal() {
            document.getElementById('withdrawalDetailsModal').classList.add('hidden');
        }
    </script>
@endsection
