<nav class="fixed bottom-3 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[416px] glass-card rounded-3xl py-2 px-4 flex justify-between items-center z-30 shadow-xl border border-white/80">
    <!-- Home / Dashboard -->
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
        <i class="fa-solid fa-house text-lg mb-0.5"></i>
        <span class="text-[10px]">Dashboard</span>
    </a>

    <!-- Notifications -->
    <a href="#" class="flex flex-col items-center py-1 px-3 text-slate-400 hover:text-indigo-500">
        <i class="fa-regular fa-bell text-lg mb-0.5"></i>
        <span class="text-[10px]">Notifications</span>
    </a>

    <!-- Tasks -->
    <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('tasks.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
        <i class="fa-solid fa-clipboard-check text-lg mb-0.5"></i>
        <span class="text-[10px]">Tasks</span>
    </a>

    <!-- Wallet -->
    <a href="{{ route('wallet') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('wallet') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
        <i class="fa-solid fa-wallet text-lg mb-0.5"></i>
        <span class="text-[10px]">Wallet</span>
    </a>

    <!-- Account / Profile -->
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('profile.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-indigo-500' }}">
        <i class="fa-regular fa-user text-lg mb-0.5"></i>
        <span class="text-[10px]">Account</span>
    </a>
</nav>
