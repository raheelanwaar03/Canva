@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6">

    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Dashboard</h1>
        <p class="text-xs text-slate-500">Welcome to Canva ADS Administration Command Center</p>
    </div>

    <!-- TOP GRID OF STAT CARDS (PTCLab Style Exact Match) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        <!-- 1. Total Users (Blue Primary Box) -->
        <div class="bg-[#3b49df] text-white rounded-2xl p-5 shadow-lg shadow-indigo-500/10 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-100">Total Users</span>
                <div class="text-2xl font-black mt-1">{{ number_format($totalUsers) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- 2. Total Deposited (Green Box) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Deposited</span>
                <div class="text-xl font-black text-slate-800 mt-1">Rs {{ number_format($totalApprovedRevenue, 2) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>

        <!-- 3. Today Approved Users (Orange Box) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Approved Users</span>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ number_format($activeSubscribers) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

        <!-- 4. Today Received Balance (Emerald Box) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Today Received Balance</span>
                <div class="text-xl font-black text-slate-800 mt-1">Rs {{ number_format($totalApprovedRevenue, 2) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- 5. Total Withdrawn (Slate Box) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Withdrawn</span>
                <div class="text-xl font-black text-slate-800 mt-1">Rs {{ number_format($totalPaidWithdrawals, 2) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <!-- 6. Pending Payments (Amber Badge) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-200 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Pending User Requests</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingPaymentsCount }}</div>
                <span class="text-[10px] text-slate-400">Rs {{ number_format($pendingPaymentsAmount) }} queued</span>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xs font-bold hover:bg-amber-600 transition">
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- 7. Pending Withdrawals (Rose Badge) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-rose-200 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Pending Withdrawals</span>
                <div class="text-2xl font-black text-rose-600 mt-1">{{ $pendingWithdrawCount }}</div>
                <span class="text-[10px] text-slate-400">Rs {{ number_format($pendingWithdrawAmount) }} queued</span>
            </div>
            <a href="{{ route('admin.withdrawals.index') }}" class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-xs font-bold hover:bg-rose-600 transition">
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- 8. Net Balance Manual -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Net Manual Balance</span>
                <div class="text-xl font-black text-indigo-600 mt-1">Rs {{ number_format($totalApprovedRevenue - $totalPaidWithdrawals, 2) }}</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>

    </div>

    <!-- SECTION: EARNINGS BREAKDOWN (Matching client image exactly) -->
    <div class="space-y-4 pt-2">
        <div>
            <h2 class="text-base font-black text-slate-800">Earnings Breakdown</h2>
            <p class="text-xs text-slate-400">Each source is counted on its own. Plan upgrade payments are not included here.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Task Earnings Card (Indigo) -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Task Earnings — Overall</span>
                    <div class="text-lg font-black text-slate-800">Rs {{ number_format($tasksCompletedToday * 7, 2) }}</div>
                    <span class="text-[10px] font-bold text-indigo-600">{{ $tasksCompletedToday }} verified watches today</span>
                </div>
            </div>

            <!-- Manual Balance Add Card (Green) -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manual Balance Add — Overall</span>
                    <div class="text-lg font-black text-slate-800">Rs {{ number_format($totalApprovedRevenue, 2) }}</div>
                    <span class="text-[10px] font-bold text-emerald-600">Admin Approved Funds</span>
                </div>
            </div>

            <!-- Manual Subtractions / Payouts (Rose) -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-circle-minus"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manual Subtractions — Overall</span>
                    <div class="text-lg font-black text-slate-800">Rs {{ number_format($totalPaidWithdrawals, 2) }}</div>
                    <span class="text-[10px] font-bold text-rose-600">Disbursed Withdrawals</span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
