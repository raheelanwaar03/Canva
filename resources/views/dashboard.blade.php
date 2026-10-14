@extends('layouts.app')

@section('content')
<div class="space-y-4">

    <!-- Profile Header Section -->
    <div class="flex items-center space-x-3.5 px-1 pt-1">
        <div class="relative">
            <div class="w-16 h-16 rounded-full p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-md">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&bold=true"
                     alt="Avatar" class="w-full h-full rounded-full object-cover">
            </div>
            <span class="absolute bottom-0 right-0 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px] shadow border border-white">
                <i class="fa-solid fa-camera"></i>
            </span>
        </div>
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ auth()->user()->name }}</h2>
            <span class="inline-flex items-center px-3 py-0.5 mt-1 rounded-full text-xs font-semibold bg-white/80 text-purple-700 shadow-sm border border-purple-100">
                <i class="fa-solid fa-crown text-[10px] mr-1.5 text-purple-600"></i>
                {{ auth()->user()->activePackage?->package?->name ?? 'Basic' }} Member
            </span>
        </div>
    </div>

    <!-- Referral Link Box (Exact Match to Client Image) -->
    <div class="ref-gradient-card rounded-2xl p-4 text-white relative overflow-hidden">
        <div class="flex items-center justify-between mb-2.5">
            <span class="text-xs font-bold text-white tracking-wide">Your Refer Link</span>
            <button onclick="copyRefLink()" class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1 rounded-xl backdrop-blur-md transition flex items-center shadow-sm">
                <i class="fa-regular fa-copy mr-1.5 text-[11px]"></i> Copy Link
            </button>
        </div>
        <div class="flex items-center bg-white/25 rounded-xl px-3.5 py-2.5 backdrop-blur-md border border-white/30 shadow-inner">
            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center mr-2.5 flex-shrink-0">
                <i class="fa-solid fa-link text-white text-xs"></i>
            </div>
            <input type="text" id="referralUrlInput" readonly
                   value="{{ url('/register?ref=' . auth()->user()->referral_code) }}"
                   class="bg-transparent border-0 text-white text-xs font-medium w-full focus:outline-none p-0 truncate">
        </div>
    </div>

    <!-- 2-Column Action Grid (Client Mockup Exact Match) -->
    <div class="grid grid-cols-2 gap-3">

        <!-- 1. Dashboard -->
        <a href="{{ route('dashboard') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-cyan-400 to-blue-500 text-white">
                    <i class="fa-solid fa-house text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Dashboard</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 2. Wallet -->
        <a href="{{ route('wallet') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-blue-500 to-indigo-600 text-white">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Wallet</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 3. Tasks -->
        <a href="{{ route('tasks.index') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-cyan-400 to-teal-500 text-white">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Tasks</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 4. Rewards -->
        <a href="#" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-pink-400 to-rose-500 text-white">
                    <i class="fa-solid fa-gift text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Rewards</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 5. Withdraw -->
        <a href="{{ route('withdraw.create') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-fuchsia-400 to-purple-600 text-white">
                    <i class="fa-solid fa-arrow-up-from-bracket text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Withdraw</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 6. Plan Upgrade -->
        <a href="{{ route('packages.upgrade') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-blue-600 to-indigo-700 text-white">
                    <i class="fa-solid fa-crown text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Plan Upgrade</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 7. Team -->
        <a href="{{ route('team') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-indigo-400 to-blue-600 text-white">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Team</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 8. Guide -->
        <a href="#" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-purple-500 to-indigo-600 text-white">
                    <i class="fa-solid fa-book-open text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Guide</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 9. Official Channel -->
        <a href="#" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-sky-400 to-blue-600 text-white">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Official Channel</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 10. Contact Us -->
        <a href="#" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-sky-400 to-indigo-500 text-white">
                    <i class="fa-solid fa-headset text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Contact Us</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 11. Work (from client sidebar image) -->
        <a href="{{ route('tasks.index') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-indigo-500 to-purple-600 text-white">
                    <i class="fa-solid fa-briefcase text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Work</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

        <!-- 12. Account Settings -->
        <a href="{{ route('profile.edit') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="icon-box-3d bg-gradient-to-tr from-indigo-400 to-purple-500 text-white">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">Account</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
        </a>

    </div>

    <!-- Upgrade Promo Banner at Bottom -->
    <div class="ref-gradient-card rounded-2xl p-4 text-white shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-gem text-lg text-white"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-xs">Upgrade Your Plan</h4>
                <p class="text-[10px] text-white/80">Get more tasks & higher rewards.</p>
            </div>
        </div>
        <a href="{{ route('packages.upgrade') }}" class="bg-white text-indigo-700 font-bold text-xs px-3 py-1.5 rounded-xl shadow hover:bg-slate-50 transition whitespace-nowrap ml-2">
            Upgrade &rarr;
        </a>
    </div>

</div>

@push('scripts')
<script>
    function copyRefLink() {
        const input = document.getElementById('referralUrlInput');
        navigator.clipboard.writeText(input.value).then(() => {
            alert('Referral link copied!');
        });
    }
</script>
@endpush
@endsection
