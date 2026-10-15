@extends('layouts.app')

@section('content')
    <div class="space-y-4 pt-1">

        <!-- Profile Header Section -->
        <div class="flex items-center space-x-3.5 px-1 pt-1">
            <div class="relative">
                <div
                    class="w-16 h-16 rounded-full p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-md">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&bold=true"
                        alt="Avatar" class="w-full h-full rounded-full object-cover">
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="absolute bottom-0 right-0 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px] shadow border border-white hover:scale-110 transition">
                    <i class="fa-solid fa-pen"></i>
                </a>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 leading-tight">{{ auth()->user()->name }}</h2>
                <span
                    class="inline-flex items-center px-3 py-0.5 mt-1 rounded-full text-xs font-semibold bg-white/80 text-purple-700 shadow-sm border border-purple-100">
                    <i class="fa-solid fa-crown text-[10px] mr-1.5 text-purple-600"></i>
                    {{ auth()->user()->activePackage?->package?->name ?? 'Basic' }} Member
                </span>
            </div>
        </div>

        <!-- Referral Link Box (Exact Match to Client Reference) -->
        <div class="ref-gradient-card rounded-2xl p-4 text-white relative overflow-hidden">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold text-white tracking-wide">Your Refer Link</span>
                <button type="button" onclick="copyRefLink()"
                    class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1 rounded-xl backdrop-blur-md transition flex items-center shadow-sm">
                    <i class="fa-regular fa-copy mr-1.5 text-[11px]"></i> Copy Link
                </button>
            </div>
            <div
                class="flex items-center bg-white/25 rounded-xl px-3.5 py-2.5 backdrop-blur-md border border-white/30 shadow-inner">
                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center mr-2.5 flex-shrink-0">
                    <i class="fa-solid fa-link text-white text-xs"></i>
                </div>
                <input type="text" id="referralUrlInput" readonly
                    value="{{ url('/register?ref=' . auth()->user()->referral_code) }}"
                    class="bg-transparent border-0 text-white text-xs font-medium w-full focus:outline-none p-0 truncate select-all">
            </div>
        </div>

        <!-- 2-Column Action Grid (All 12 items linked) -->
        <div class="grid grid-cols-2 gap-3">

            <!-- 1. Dashboard -->
            <a href="{{ route('dashboard') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-cyan-400 to-blue-500 text-white">
                        <i class="fa-solid fa-house text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Dashboard</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 2. Wallet -->
            <a href="{{ route('wallet') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-blue-500 to-indigo-600 text-white">
                        <i class="fa-solid fa-wallet text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Wallet</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 3. Tasks -->
            <a href="{{ route('tasks.index') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-cyan-400 to-teal-500 text-white">
                        <i class="fa-solid fa-clipboard-check text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Tasks</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 4. Rewards (Links to Wallet Ledger / Breakdown) -->
            <a href="{{ route('rewards') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-pink-400 to-rose-500 text-white">
                        <i class="fa-solid fa-gift text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Rewards</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 5. Withdraw -->
            <a href="{{ route('withdraw.create') }}"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-fuchsia-400 to-purple-600 text-white">
                        <i class="fa-solid fa-arrow-up-from-bracket text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Withdraw</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 6. Plan Upgrade -->
            <a href="{{ route('packages.upgrade') }}"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-blue-600 to-indigo-700 text-white">
                        <i class="fa-solid fa-crown text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Plan Upgrade</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 7. Team -->
            <a href="{{ route('team') }}" class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-indigo-400 to-blue-600 text-white">
                        <i class="fa-solid fa-users text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Team</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 8. Guide (Modal Popup) -->
            <button type="button" onclick="openModal('guideModal')"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between text-left w-full">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-purple-500 to-indigo-600 text-white">
                        <i class="fa-solid fa-book-open text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Guide</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </button>

            <!-- 9. Official Channel (Telegram/WhatsApp Popup) -->
            <button type="button" onclick="openModal('channelModal')"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between text-left w-full">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-sky-400 to-blue-600 text-white">
                        <i class="fa-solid fa-paper-plane text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Official Channel</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </button>

            <!-- 10. Contact Us / Support (Modal Popup) -->
            <button type="button" onclick="openModal('supportModal')"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between text-left w-full">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-sky-400 to-indigo-500 text-white">
                        <i class="fa-solid fa-headset text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Contact Us</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </button>

            <!-- 11. Work (Daily Ads direct route) -->
            <a href="{{ route('tasks.index') }}"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-indigo-500 to-purple-600 text-white">
                        <i class="fa-solid fa-briefcase text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Work</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

            <!-- 12. Account Settings -->
            <a href="{{ route('profile.edit') }}"
                class="glass-card-item rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="icon-box-3d bg-gradient-to-tr from-indigo-400 to-purple-500 text-white">
                        <i class="fa-solid fa-user-gear text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Account</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[11px] text-slate-400"></i>
            </a>

        </div>

        <!-- Upgrade Promo Banner at Bottom -->
        <div class="ref-gradient-card rounded-2xl p-4 text-white shadow-lg flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-gem text-lg text-white"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-xs">Upgrade Your Plan</h4>
                    <p class="text-[10px] text-white/80">Get more daily ads & higher rewards.</p>
                </div>
            </div>
            <a href="{{ route('packages.upgrade') }}"
                class="bg-white text-indigo-700 font-bold text-xs px-3 py-1.5 rounded-xl shadow hover:bg-slate-50 transition whitespace-nowrap ml-2">
                Upgrade &rarr;
            </a>
        </div>

    </div>

    <!-- Guide Information Modal -->
    <div id="guideModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="glass-card-item bg-white/95 rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
            <button type="button" onclick="closeModal('guideModal')"
                class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 class="text-sm font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-book-open mr-2 text-indigo-600"></i> Canva ADS User Guide
            </h3>
            <div class="space-y-2 text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-2xl">
                <p><strong>1. First 15 Days:</strong> Watch daily ads and earn rewards without any referral requirement.</p>
                <p><strong>2. After Day 15:</strong> Bring 1 approved direct referral every 7 days to keep tasks unlocked.
                </p>
                <p><strong>3. Withdrawals:</strong> Withdraw your balance daily to JazzCash or Easypaisa after reaching Rs
                    50.</p>
            </div>
            <button type="button" onclick="closeModal('guideModal')"
                class="w-full py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow">
                Understood
            </button>
        </div>
    </div>

    <!-- Official Channel Dynamic Modal -->
    <div id="channelModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="glass-card-item bg-white/95 rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
            <button type="button" onclick="closeModal('channelModal')"
                class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 class="text-sm font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-paper-plane mr-2 text-sky-500"></i> Official Community Channels
            </h3>
            <p class="text-xs text-slate-500">Join our verified official channels for daily updates, bonus alerts, and
                payment proofs:</p>

            <div class="space-y-2.5 pt-1">
                @forelse($channels ?? [] as $channel)
                    <a href="{{ $channel->url }}" target="_blank"
                        class="flex items-center justify-between w-full py-3 px-3.5 bg-gradient-to-r from-slate-50 to-indigo-50/50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl transition shadow-sm">
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center text-base text-indigo-600">
                                <i class="{{ $channel->icon }}"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800">{{ $channel->title }}</span>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                    </a>
                @empty
                    <div class="p-3 bg-slate-100 rounded-xl text-center text-xs text-slate-400">
                        Channels will be announced shortly.
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    <!-- Contact Us / Support Modal -->
    <div id="supportModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="glass-card-item bg-white/95 rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
            <button type="button" onclick="closeModal('supportModal')"
                class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 class="text-sm font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-headset mr-2 text-rose-500"></i> Customer Support
            </h3>
            <p class="text-xs text-slate-600">Need help with package activation or withdrawal?</p>
            <div class="space-y-1.5 text-xs text-slate-700 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                <div><span class="text-slate-400">Support Hours:</span> <strong>9:00 AM – 10:00 PM</strong></div>
                <div><span class="text-slate-400">Email:</span> <strong>support@canvaads.com</strong></div>
                <div><span class="text-slate-400">WhatsApp:</span> <strong class="font-mono">+92 300 1234567</strong>
                </div>
            </div>
            <a href="https://wa.me/923001234567" target="_blank"
                class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold text-center shadow">
                Chat on WhatsApp &rarr;
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyRefLink() {
                const input = document.getElementById('referralUrlInput');
                navigator.clipboard.writeText(input.value).then(() => {
                    alert('Referral link copied to clipboard!');
                });
            }

            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        </script>
    @endpush
@endsection
