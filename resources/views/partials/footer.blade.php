@php
    $isAdmin = request()->routeIs('admin.*') || request()->is('admin*') || (auth()->check() && auth()->user()->role === 'admin');
@endphp

<nav class="fixed bottom-3 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[398px] bottom-nav-glass rounded-3xl py-2 px-3 flex justify-between items-center z-30 shadow-xl border border-white/90">

    @if($isAdmin)
        {{-- ================= ADMIN BOTTOM NAVIGATION ================= --}}

        <!-- Admin Overview / Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('admin.dashboard*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-chart-pie text-base"></i>
            <span class="text-[9px] mt-0.5">Overview</span>
        </a>

        <!-- Payment Approvals -->
        <a href="{{ route('admin.payments.index') }}" class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('admin.payments.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-credit-card text-base"></i>
            <span class="text-[9px] mt-0.5">Payments</span>
        </a>

        <!-- Withdrawal Payouts -->
        <a href="{{ route('admin.withdrawals.index') }}" class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('admin.withdrawals.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-money-bill-transfer text-base"></i>
            <span class="text-[9px] mt-0.5">Payouts</span>
        </a>

        <!-- Package Rules & Limits -->
        <a href="{{ route('admin.packages.index') }}" class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('admin.packages.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-crown text-base"></i>
            <span class="text-[9px] mt-0.5">Plans</span>
        </a>

        <!-- Advertising Tasks -->
        <a href="{{ route('admin.tasks.index') }}" class="flex flex-col items-center py-1 px-2.5 {{ request()->routeIs('admin.tasks.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-bullhorn text-base"></i>
            <span class="text-[9px] mt-0.5">Ads</span>
        </a>

    @else
        {{-- ================= USER BOTTOM NAVIGATION ================= --}}

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[10px] mt-0.5">Dashboard</span>
        </a>

        <!-- Notifications -->
        <a href="#" class="flex flex-col items-center py-1 px-3 text-slate-400 hover:text-slate-600">
            <i class="fa-regular fa-bell text-lg"></i>
            <span class="text-[10px] mt-0.5">Notifications</span>
        </a>

        <!-- Tasks -->
        <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('tasks.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-clipboard-check text-lg"></i>
            <span class="text-[10px] mt-0.5">Tasks</span>
        </a>

        <!-- Wallet -->
        <a href="{{ route('wallet') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('wallet') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-wallet text-lg"></i>
            <span class="text-[10px] mt-0.5">Wallet</span>
        </a>

        <!-- Account -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('profile.*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-regular fa-user text-lg"></i>
            <span class="text-[10px] mt-0.5">Account</span>
        </a>
    @endif

</nav>
