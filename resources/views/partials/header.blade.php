<header class="px-5 pt-5 pb-3 flex items-center justify-between z-20">
    @if(request()->routeIs('dashboard'))
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
        <div class="flex items-center space-x-3">
            <button class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-slate-600 hover:text-indigo-600 transition relative">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-pink-500 rounded-full ring-2 ring-white"></span>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-pink-500 hover:bg-pink-50 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    @else
        <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-slate-700 hover:bg-white transition">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>
        <div class="font-bold text-slate-800 text-base">
            {{ $headerTitle ?? '' }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Logout" class="w-10 h-10 rounded-full bg-white/80 shadow flex items-center justify-center text-pink-500 hover:bg-pink-50 transition">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </form>
    @endif
</header>
