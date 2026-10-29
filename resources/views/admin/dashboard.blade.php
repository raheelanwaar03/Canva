@extends('layouts.admin')

@section('admin_content')
    <div class="space-y-6">

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-xs text-slate-500">Welcome to Canva ADS Administration Command Center</p>
        </div>

        <!-- MAIN METRIC CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

            <!-- 1. Total Users (Blue Primary Box) -->
            <div
                class="bg-[#3b49df] text-white rounded-2xl p-5 shadow-lg shadow-indigo-500/10 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
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
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-white/10 text-6xl pointer-events-none">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- 2. Total Deposited (Green Box) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Deposited</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">
                                Rs {{ number_format($totalApprovedRevenue ?? 0, 2) }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.payments.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 border border-emerald-300 hover:bg-emerald-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-emerald-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>

            <!-- 3. Today Approved Users (Orange Box - Daily PKT Reset) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Approved Users</span>
                            <div class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($activeSubscribers ?? 0) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-amber-600 border border-amber-300 hover:bg-amber-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-amber-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>

            <!-- 4. Today Received Balance (Teal Box - Daily PKT Reset) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Received Balance</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">Rs {{ number_format($todayReceivedBalance ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.payments.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-teal-600 border border-teal-300 hover:bg-teal-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-teal-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>

            <!-- 5. Total Withdrawn (Slate Box) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Withdrawn</span>
                            <div class="text-lg font-black text-slate-800 mt-0.5">Rs {{ number_format($totalPaidWithdrawals ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.withdrawals.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-slate-600 border border-slate-300 hover:bg-slate-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-slate-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- 6. Pending User Requests (Amber Badge Card) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-amber-200 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Pending User Requests</span>
                            <div class="text-2xl font-black text-amber-600 mt-0.5">{{ $pendingPaymentsCount ?? 0 }}</div>
                            <span class="text-[10px] text-slate-400">Rs {{ number_format($pendingPaymentsAmount ?? 0) }} queued</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.payments.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-amber-600 border border-amber-300 hover:bg-amber-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-amber-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- 7. Pending Withdrawals (Rose Badge Card) -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-rose-200 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Pending Withdrawals</span>
                            <div class="text-2xl font-black text-rose-600 mt-0.5">{{ $pendingWithdrawCount ?? 0 }}</div>
                            <span class="text-[10px] text-slate-400">Rs {{ number_format($pendingWithdrawAmount ?? 0) }} queued</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.withdrawals.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-rose-600 border border-rose-300 hover:bg-rose-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-rose-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>

            <!-- 8. Net Balance Manual -->
            <div
                class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between z-10">
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg flex-shrink-0">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Net Manual Balance</span>
                            <div class="text-lg font-black text-indigo-600 mt-0.5">Rs
                                {{ number_format(($totalApprovedRevenue ?? 0) - ($totalPaidWithdrawals ?? 0), 2) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-indigo-600 border border-indigo-300 hover:bg-indigo-50 shadow-sm transition">
                        View All
                    </a>
                </div>
                <!-- Watermark Icon Background -->
                <div class="absolute -bottom-2 -right-2 text-indigo-500/5 text-6xl pointer-events-none">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>

        </div>

        <!-- SECTION: EARNINGS BREAKDOWN -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-black text-slate-800">Earnings Breakdown</h2>
                    <p class="text-xs text-slate-400">Each source is counted on its own. Plan upgrade payments are not included here.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- 1. Task Earnings Card (Indigo) -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[130px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Task Earnings — Overall</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format(($tasksCompletedToday ?? 0) * 7, 2) }}</div>
                                <span class="text-[10px] font-bold text-indigo-600">{{ $tasksCompletedToday ?? 0 }} verified watches today</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.tasks.index') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-indigo-600 border border-indigo-200 hover:bg-indigo-50 shadow-sm transition">
                            View Details
                        </a>
                    </div>
                    <!-- Watermark Background -->
                    <div class="absolute -bottom-2 -right-2 text-indigo-500/5 text-6xl pointer-events-none">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>

                <!-- 2. Manual Balance Add Card (Green) -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[130px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-circle-plus"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manual Balance Add — Overall</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format($totalApprovedRevenue ?? 0, 2) }}</div>
                                <span class="text-[10px] font-bold text-emerald-600">Admin Approved Funds</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 border border-emerald-200 hover:bg-emerald-50 shadow-sm transition">
                            View Details
                        </a>
                    </div>
                    <!-- Watermark Background -->
                    <div class="absolute -bottom-2 -right-2 text-emerald-500/5 text-6xl pointer-events-none">
                        <i class="fa-solid fa-circle-plus"></i>
                    </div>
                </div>

                <!-- 3. Manual Subtractions / Payouts (Rose) -->
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 relative overflow-hidden flex flex-col justify-between min-h-[130px]">
                    <div class="flex items-start justify-between z-10">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm">
                                <i class="fa-solid fa-circle-minus"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manual Subtractions — Overall</span>
                                <div class="text-lg font-black text-slate-800 mt-0.5">Rs
                                    {{ number_format($totalPaidWithdrawals ?? 0, 2) }}</div>
                                <span class="text-[10px] font-bold text-rose-600">Disbursed Withdrawals</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.withdrawals.index') }}"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-rose-600 border border-rose-200 hover:bg-rose-50 shadow-sm transition">
                            View Details
                        </a>
                    </div>
                    <!-- Watermark Background -->
                    <div class="absolute -bottom-2 -right-2 text-rose-500/5 text-6xl pointer-events-none">
                        <i class="fa-solid fa-circle-minus"></i>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
