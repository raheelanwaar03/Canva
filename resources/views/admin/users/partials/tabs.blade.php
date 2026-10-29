<!-- Users Management Header -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $pageTitle ?? 'Users Management' }}</h1>
        <p class="text-xs font-semibold text-slate-400 mt-1">Filter and instantly search users by username or email</p>
    </div>
    <span
        class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
        {{ $users->total() ?? count($users) }} Records
    </span>
</div>

<!-- Dedicated Route Filter Tabs -->
<div class="flex flex-wrap items-center gap-2 pt-2">
    <a href="{{ route('admin.users.approved') }}"
        class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.users.approved') ? 'bg-[#3b49df] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Approved Users
    </a>
    <a href="{{ route('admin.users.pending') }}"
        class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.users.pending') ? 'bg-[#3b49df] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Pending Requests
    </a>
    <a href="{{ route('admin.users.rejected') }}"
        class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.users.rejected') ? 'bg-[#3b49df] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Rejected Users
    </a>
</div>
