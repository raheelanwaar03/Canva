@extends('layouts.app')

@php
    $headerTitle = 'Admin Portal';
@endphp

@section('content')
<div class="space-y-4 pt-1">

    <!-- Admin Welcome Header -->
    <div class="flex items-center justify-between px-1">
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Admin Overview</h2>
            <p class="text-[11px] text-slate-500">Live platform stats & pending approvals</p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700">
            <i class="fa-solid fa-shield-halved mr-1.5 text-indigo-600"></i> Super Admin
        </span>
    </div>

    <!-- Quick Navigation Toolbar -->
    <div class="grid grid-cols-4 gap-1.5 text-center text-[10px] font-bold">
        <a href="{{ route('admin.payments.index') }}" class="p-2.5 rounded-2xl bg-white/80 hover:bg-white text-slate-700 shadow-sm transition border border-white">
            <i class="fa-solid fa-credit-card block text-indigo-600 text-sm mb-1"></i>
            Payments
            @if($pendingPaymentsCount > 0)
                <span class="block text-[9px] text-amber-600 font-extrabold">({{ $pendingPaymentsCount }})</span>
            @endif
        </a>
        <a href="{{ route('admin.withdrawals.index') }}" class="p-2.5 rounded-2xl bg-white/80 hover:bg-white text-slate-700 shadow-sm transition border border-white">
            <i class="fa-solid fa-money-bill-transfer block text-fuchsia-600 text-sm mb-1"></i>
            Payouts
            @if($pendingWithdrawCount > 0)
                <span class="block text-[9px] text-amber-600 font-extrabold">({{ $pendingWithdrawCount }})</span>
            @endif
        </a>
        <a href="{{ route('admin.packages.index') }}" class="p-2.5 rounded-2xl bg-white/80 hover:bg-white text-slate-700 shadow-sm transition border border-white">
            <i class="fa-solid fa-crown block text-amber-500 text-sm mb-1"></i>
            Packages
        </a>
        <a href="{{ route('admin.tasks.index') }}" class="p-2.5 rounded-2xl bg-white/80 hover:bg-white text-slate-700 shadow-sm transition border border-white">
            <i class="fa-solid fa-bullhorn block text-cyan-500 text-sm mb-1"></i>
            Ad Tasks
        </a>
    </div>

    <!-- Primary Financial Summary Cards -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Approved Revenue -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 text-white rounded-3xl p-4 shadow-md flex flex-col justify-between">
            <div>
                <span class="text-[10px] text-indigo-200 font-bold uppercase tracking-wider">Gross Inflow</span>
                <div class="text-lg font-black mt-0.5">Rs {{ number_format($totalApprovedRevenue, 0) }}</div>
            </div>
            <div class="text-[10px] text-indigo-200 mt-2 flex items-center">
                <i class="fa-solid fa-arrow-trend-up mr-1 text-emerald-300"></i> Approved Packages
            </div>
        </div>

        <!-- Paid Withdrawals -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-3xl p-4 shadow-md flex flex-col justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Paid Out</span>
                <div class="text-lg font-black mt-0.5">Rs {{ number_format($totalPaidWithdrawals, 0) }}</div>
            </div>
            <div class="text-[10px] text-slate-400 mt-2 flex items-center">
                <i class="fa-solid fa-arrow-up-from-bracket mr-1 text-rose-400"></i> Withdrawals
            </div>
        </div>
    </div>

    <!-- Secondary 4 Metrics Grid -->
    <div class="grid grid-cols-2 gap-2.5">
        <div class="glass-card-item rounded-2xl p-3">
            <div class="text-[10px] text-slate-400 font-bold">Total Members</div>
            <div class="text-base font-black text-slate-900 mt-0.5">{{ $totalUsers }} Users</div>
            <div class="text-[9px] text-emerald-600 font-bold mt-1">{{ $activeSubscribers }} Active Plans</div>
        </div>

        <div class="glass-card-item rounded-2xl p-3">
            <div class="text-[10px] text-slate-400 font-bold">Tasks Done Today</div>
            <div class="text-base font-black text-slate-900 mt-0.5">{{ $tasksCompletedToday }} Ads</div>
            <div class="text-[9px] text-indigo-600 font-bold mt-1">Verified watches</div>
        </div>

        <div class="glass-card-item rounded-2xl p-3 border-l-4 border-amber-400">
            <div class="text-[10px] text-slate-400 font-bold">Pending Payments</div>
            <div class="text-base font-black text-amber-600 mt-0.5">{{ $pendingPaymentsCount }} Requests</div>
            <div class="text-[9px] text-slate-500 font-medium mt-1">Rs {{ number_format($pendingPaymentsAmount, 0) }} queued</div>
        </div>

        <div class="glass-card-item rounded-2xl p-3 border-l-4 border-rose-400">
            <div class="text-[10px] text-slate-400 font-bold">Pending Payouts</div>
            <div class="text-base font-black text-rose-600 mt-0.5">{{ $pendingWithdrawCount }} Requests</div>
            <div class="text-[9px] text-slate-500 font-medium mt-1">Rs {{ number_format($pendingWithdrawAmount, 0) }} queued</div>
        </div>
    </div>

    <!-- Recent Pending Payments Quick List -->
    <div class="space-y-2 pt-1">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-600">Pending Payments</h3>
            <a href="{{ route('admin.payments.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">View All &rarr;</a>
        </div>

        @forelse($recentPayments->where('status', 'pending') as $p)
            <div class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-slate-800">{{ $p->user->name }}</div>
                    <div class="text-[10px] text-slate-400">{{ $p->package->name }} • Rs {{ number_format($p->amount, 0) }} via {{ $p->payment_method }}</div>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="px-2.5 py-1 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-xl text-[10px] font-bold shadow-sm">
                    Review
                </a>
            </div>
        @empty
            <div class="glass-card-item rounded-2xl p-4 text-center text-xs text-slate-400">
                No pending payments waiting for approval.
            </div>
        @endforelse
    </div>

    <!-- Recent Payout Requests Quick List -->
    <div class="space-y-2 pt-1">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-600">Pending Withdrawals</h3>
            <a href="{{ route('admin.withdrawals.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">View All &rarr;</a>
        </div>

        @forelse($recentWithdrawals->where('status', 'pending') as $w)
            <div class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-slate-800">{{ $w->user->name }}</div>
                    <div class="text-[10px] text-slate-400">Rs {{ number_format($w->amount, 0) }} • {{ $w->method }}</div>
                </div>
                <a href="{{ route('admin.withdrawals.index') }}" class="px-2.5 py-1 bg-rose-500 text-white rounded-xl text-[10px] font-bold shadow-sm">
                    Process
                </a>
            </div>
        @empty
            <div class="glass-card-item rounded-2xl p-4 text-center text-xs text-slate-400">
                No pending withdrawals waiting.
            </div>
        @endforelse
    </div>

</div>
@endsection
