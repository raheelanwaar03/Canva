@extends('layouts.admin')

@section('admin_content')
    <div class="space-y-6">

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-xs text-slate-500">Welcome to Canva ADS Administration Command Center</p>
        </div>

        <!-- SECTION 2: ALL-TIME PLATFORM OVERVIEW -->
        <div class="space-y-3 pt-2">
            <h2 class="text-xs font-black uppercase tracking-wider text-slate-500 flex items-center space-x-1.5">
                <i class="fa-solid fa-chart-pie"></i>
                <span>All-Time Totals & Queues</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4">

                <!-- 1. Total Users -->
                <div
                    class="bg-[#3b49df] text-white rounded-2xl p-5 shadow-lg shadow-indigo-500/10 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-100">Total Users</span>
                            <div class="text-2xl font-black mt-1">{{ number_format($totalUsers ?? 0) }}</div>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-white/20 hover:bg-white text-white hover:text-indigo-700 border border-white/30 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 2. Total Deposited -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total
                                    Deposited</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format($totalDeposited ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 border border-emerald-300 hover:bg-emerald-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 3. Total Withdrawn -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total
                                    Withdrawn</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format($totalWithdrawn ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.withdrawals.approved') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-slate-600 border border-slate-300 hover:bg-slate-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 4. All Pending User Requests -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-amber-200 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">All Pending
                                    Requests</span>
                                <div class="text-2xl font-black text-amber-600 mt-0.5">{{ $pendingPaymentsCount ?? 0 }}
                                </div>
                                <span class="text-[10px] text-slate-400">Rs
                                    {{ number_format($pendingPaymentsAmount ?? 0, 2) }} total queued</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-amber-600 border border-amber-300 hover:bg-amber-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 5. All Pending Withdrawals -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-rose-200 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-money-check-dollar"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">All Pending
                                    Withdrawals</span>
                                <div class="text-2xl font-black text-rose-600 mt-0.5">{{ $pendingWithdrawCount ?? 0 }}
                                </div>
                                <span class="text-[10px] text-slate-400">Rs
                                    {{ number_format($pendingWithdrawAmount ?? 0, 2) }} total queued</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.withdrawals.pending') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-rose-600 border border-rose-300 hover:bg-rose-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 6. Net Platform Balance -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-scale-balanced"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Net Platform
                                    Balance</span>
                                <div class="text-lg font-black text-indigo-600 mt-0.5">Rs
                                    {{ number_format($netBalance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.index') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-indigo-600 border border-indigo-300 hover:bg-indigo-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 1: TODAY'S LIVE ACTIVITY (PKT) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-black uppercase tracking-wider text-indigo-600 flex items-center space-x-1.5">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>Today's Activity (PKT)</span>
                </h2>
                <span
                    class="text-[11px] font-bold text-slate-400">{{ \Carbon\Carbon::now('Asia/Karachi')->format('d M, Y') }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- 1. Today Approved Users -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-amber-200/80 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Approved
                                    Users</span>
                                <div class="text-2xl font-black text-slate-800 mt-0.5">
                                    {{ number_format($todayApprovedUsers ?? 0) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.todayApproved') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-amber-600 border border-amber-300 hover:bg-amber-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 2. Today Received Balance -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-teal-200/80 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Received
                                    Balance</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format($todayReceivedBalance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.todayApproved') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-teal-600 border border-teal-300 hover:bg-teal-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 3. Today Pending User Requests -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-orange-200 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-orange-600">Today Pending
                                    Requests</span>
                                <div class="text-2xl font-black text-orange-600 mt-0.5">
                                    {{ $todayPendingPaymentsCount ?? 0 }}</div>
                                <span class="text-[10px] text-slate-400">Rs
                                    {{ number_format($todayPendingPaymentsAmount ?? 0, 2) }} today</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.todayPending') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-orange-600 border border-orange-300 hover:bg-orange-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 4. Today Pending Withdrawals -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-rose-200 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Today Pending
                                    Withdrawals</span>
                                <div class="text-2xl font-black text-rose-600 mt-0.5">
                                    {{ $todayPendingWithdrawCount ?? 0 }}
                                </div>
                                <span class="text-[10px] text-slate-400">Rs
                                    {{ number_format($todayPendingWithdrawAmount ?? 0, 2) }} today</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.withdrawals.todayPending') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-rose-600 border border-rose-300 hover:bg-rose-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

                <!-- 5. Today Approved Withdrawals -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-emerald-200 relative overflow-hidden flex flex-col justify-between min-h-[135px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Today
                                    Approved
                                    Withdrawals</span>
                                <div class="text-2xl font-black text-emerald-600 mt-0.5">
                                    {{ $todayApprovedWithdrawCount ?? 0 }}
                                </div>
                                <span class="text-[10px] text-slate-400">Rs
                                    {{ number_format($todayApprovedWithdrawAmount ?? 0, 2) }} approved today</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.withdrawals.todayApproved') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 border border-emerald-300 hover:bg-emerald-50 shadow-sm transition">
                            View All
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: EARNINGS BREAKDOWN -->
        <div class="space-y-3 pt-2">
            <h2 class="text-xs font-black uppercase tracking-wider text-slate-500">Earnings Breakdown</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Today's Task Earnings -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Today's Task
                                Earnings</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                {{ number_format($taskEarningsToday ?? 0, 2) }}</div>
                            <span class="text-[10px] font-bold text-indigo-600">{{ $tasksCompletedToday ?? 0 }} verified
                                watches today</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.tasks.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-indigo-600 border border-indigo-200 hover:bg-indigo-50 transition">
                        View Details
                    </a>
                </div>

                <!-- Total Approved Deposits -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-circle-plus"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Approved
                                Deposits</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                {{ number_format($totalDeposited ?? 0, 2) }}</div>
                            <span class="text-[10px] font-bold text-emerald-600">Active Membership Revenue</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 border border-emerald-200 hover:bg-emerald-50 transition">
                        View Details
                    </a>
                </div>

                <!-- Total Disbursed Payouts -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-circle-minus"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Disbursed
                                Payouts</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                {{ number_format($totalWithdrawn ?? 0, 2) }}</div>
                            <span class="text-[10px] font-bold text-rose-600">Disbursed to Users</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.withdrawals.approved') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-rose-600 border border-rose-200 hover:bg-rose-50 transition">
                        View Details
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
