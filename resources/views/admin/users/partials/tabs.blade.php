<div class="flex items-center space-x-2 my-4">
    <!-- Approved Users Tab -->
    <a href="{{ route('admin.users.approved') }}"
       class="px-5 py-2.5 rounded-full text-xs font-bold transition {{ ($activeTab ?? '') === 'approved' ? 'bg-[#00875a] text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Approved Users
    </a>

    <!-- Pending Requests Tab -->
    <a href="{{ route('admin.users.pending') }}"
       class="px-5 py-2.5 rounded-full text-xs font-bold transition {{ ($activeTab ?? '') === 'pending' ? 'bg-[#00875a] text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Pending Requests
    </a>

    <!-- Rejected Users Tab -->
    <a href="{{ route('admin.users.rejected') }}"
       class="px-5 py-2.5 rounded-full text-xs font-bold transition {{ ($activeTab ?? '') === 'rejected' ? 'bg-[#00875a] text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        Rejected Users
    </a>
</div>
