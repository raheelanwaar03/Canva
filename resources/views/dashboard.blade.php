@extends('layouts.app')

@section('content')
<div class="space-y-3.5 pt-1 relative">

    <!-- 1. HERO LIQUID GLASS PROFILE CARD (Exact Match to user.jpeg) -->
    <div class="hero-fluid-card p-6 text-white text-center relative overflow-hidden">
        <!-- Ambient Glowing Core -->
        <div class="absolute -top-12 -right-12 w-36 h-36 bg-cyan-300/30 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-fuchsia-400/30 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Centered Canva Circular Badge with Glowing Ring -->
        <div class="w-20 h-20 rounded-full p-[3px] bg-gradient-to-tr from-cyan-400 via-indigo-400 to-purple-500 mx-auto shadow-2xl mb-3 flex items-center justify-center">
            <div class="w-full h-full rounded-full bg-[#001042] flex items-center justify-center shadow-inner">
                <span class="canva-brand-script text-white text-2xl font-bold tracking-tight">Canva</span>
            </div>
        </div>

        <!-- User Name -->
        <h2 class="text-2xl font-black tracking-tight text-white drop-shadow">{{ auth()->user()->name }}</h2>

        <!-- Action Links: Show Wallet & Copy Refer Link (Exact styling from user.jpeg) -->
        <div class="mt-4 flex flex-col items-center space-y-2.5">
            <!-- Show Wallet Action -->
            <button type="button" onclick="openModal('walletModal')"
                    class="inline-flex items-center space-x-2 text-white/95 text-xs font-bold bg-white/20 hover:bg-white/30 px-5 py-2 rounded-full backdrop-blur-md transition border border-white/30 shadow-sm active:scale-95">
                <span>Show Wallet</span>
                <i class="fa-regular fa-eye text-xs"></i>
            </button>

            <!-- Copy Refer Link Action (Reliable on HTTP & HTTPS) -->
            <button type="button" onclick="copyReferralLinkUniversal('{{ url('/register?ref=' . auth()->user()->referral_code) }}')"
                    class="inline-flex items-center space-x-2 text-white/95 text-xs font-bold bg-white/20 hover:bg-white/30 px-5 py-2 rounded-full backdrop-blur-md transition border border-white/30 shadow-sm active:scale-95">
                <span>Copy Refer Link</span>
                <i class="fa-regular fa-copy text-xs"></i>
            </button>
        </div>
    </div>

    <!-- 2. DASHBOARD TITLE RIBBON (Exact Match to user.jpeg) -->
    <div class="rounded-2xl px-4 py-2.5 flex items-center space-x-2.5 text-white shadow-md" style="background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);">
        <div class="w-6 h-6 rounded-lg bg-white/25 flex items-center justify-center text-xs shadow-inner">
            <i class="fa-solid fa-table-cells-large"></i>
        </div>
        <span class="text-xs font-black tracking-wider uppercase">Dashboard</span>
    </div>

    <!-- 3. 3-COLUMN ACTION GRID WITH 3D CUSTOM ILLUSTRATIONS (Exact Match to user.jpeg) -->
    <div class="grid grid-cols-3 gap-2.5 relative">

        <!-- 1. Withdraw -->
        <a href="{{ route('withdraw.create') }}" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Glossy Purple Wallet with Money Outflow -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="wGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8b5cf6"/>
                            <stop offset="100%" stop-color="#4f46e5"/>
                        </linearGradient>
                    </defs>
                    <rect x="8" y="16" width="48" height="36" rx="8" fill="url(#wGrad)"/>
                    <path d="M14 16 L22 8 L42 8 L50 16 Z" fill="#a78bfa" opacity="0.6"/>
                    <rect x="36" y="28" width="20" height="12" rx="4" fill="#c4b5fd"/>
                    <circle cx="44" cy="34" r="2.5" fill="#ffffff"/>
                    <path d="M26 40 L26 26 M20 32 L26 26 L32 32" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Withdraw</span>
        </a>

        <!-- 2. Team -->
        <a href="{{ route('team') }}" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Team Community Icon -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="teamGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6366f1"/>
                            <stop offset="100%" stop-color="#3b82f6"/>
                        </linearGradient>
                    </defs>
                    <circle cx="32" cy="22" r="9" fill="url(#teamGrad)"/>
                    <path d="M16 46 C16 37, 23 35, 32 35 C41 35, 48 37, 48 46 Z" fill="url(#teamGrad)"/>
                    <circle cx="16" cy="26" r="6" fill="#818cf8" opacity="0.75"/>
                    <path d="M6 46 C6 40, 11 39, 18 39" stroke="#818cf8" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <circle cx="48" cy="26" r="6" fill="#818cf8" opacity="0.75"/>
                    <path d="M58 46 C58 40, 53 39, 46 39" stroke="#818cf8" stroke-width="3" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Team</span>
        </a>

        <!-- 3. Guide -->
        <button type="button" onclick="openModal('guideModal')" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Open Book Guide -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="bookGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3b82f6"/>
                            <stop offset="100%" stop-color="#4f46e5"/>
                        </linearGradient>
                    </defs>
                    <path d="M32 46 C24 40, 10 40, 8 42 L8 16 C10 14, 24 14, 32 20 Z" fill="url(#bookGrad)"/>
                    <path d="M32 46 C40 40, 54 40, 56 42 L56 16 C54 14, 40 14, 32 20 Z" fill="#60a5fa"/>
                    <path d="M32 20 L32 46" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Guide</span>
        </button>

        <!-- 4. Official Channel -->
        <button type="button" onclick="openModal('channelModal')" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Paper Airplane / Telegram Wave -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="planeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#00c6ff"/>
                            <stop offset="100%" stop-color="#0072ff"/>
                        </linearGradient>
                    </defs>
                    <path d="M10 32 L54 12 L38 52 L28 38 Z" fill="url(#planeGrad)"/>
                    <path d="M28 38 L38 52 L36 34 Z" fill="#0052cc"/>
                    <path d="M54 12 L28 38" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800 leading-tight">Official Channel</span>
        </button>

        <!-- 5. Rewards -->
        <a href="{{ route('rewards') }}" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Gift Box with Ribbon -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="giftGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#ec4899"/>
                            <stop offset="100%" stop-color="#a855f7"/>
                        </linearGradient>
                    </defs>
                    <rect x="12" y="24" width="40" height="28" rx="5" fill="url(#giftGrad)"/>
                    <rect x="9" y="17" width="46" height="9" rx="3" fill="#f472b6"/>
                    <rect x="28" y="17" width="8" height="35" fill="#fef08a"/>
                    <circle cx="27" cy="14" r="5" fill="#facc15" stroke="#eab308" stroke-width="1.5"/>
                    <circle cx="37" cy="14" r="5" fill="#facc15" stroke="#eab308" stroke-width="1.5"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Rewards</span>
        </a>

        <!-- 6. Work (Daily Ads Tasks) -->
        <a href="{{ route('tasks.index') }}" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Clipboard with Checklist -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="clipGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8b5cf6"/>
                            <stop offset="100%" stop-color="#6366f1"/>
                        </linearGradient>
                    </defs>
                    <rect x="12" y="12" width="40" height="44" rx="8" fill="url(#clipGrad)"/>
                    <rect x="23" y="7" width="18" height="8" rx="3" fill="#c4b5fd"/>
                    <line x1="22" y1="26" x2="42" y2="26" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <line x1="22" y1="34" x2="42" y2="34" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                    <line x1="22" y1="42" x2="36" y2="42" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Work</span>
        </a>

        <!-- 7. About Us -->
        <button type="button" onclick="openModal('aboutModal')" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Circular Info Badge -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="infoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#a855f7"/>
                            <stop offset="100%" stop-color="#4f46e5"/>
                        </linearGradient>
                    </defs>
                    <circle cx="32" cy="32" r="22" fill="url(#infoGrad)"/>
                    <circle cx="32" cy="22" r="3.5" fill="#ffffff"/>
                    <rect x="29" y="29" width="6" height="15" rx="3" fill="#ffffff"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">About Us</span>
        </button>

        <!-- 8. Contact Us -->
        <button type="button" onclick="openModal('supportModal')" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Headset Support Icon -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="headsetGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#38bdf8"/>
                            <stop offset="100%" stop-color="#6366f1"/>
                        </linearGradient>
                    </defs>
                    <path d="M12 34 A20 20 0 0 1 52 34" fill="none" stroke="url(#headsetGrad)" stroke-width="5" stroke-linecap="round"/>
                    <rect x="8" y="32" width="9" height="15" rx="4.5" fill="#38bdf8"/>
                    <rect x="47" y="32" width="9" height="15" rx="4.5" fill="#6366f1"/>
                    <path d="M50 42 C50 49, 44 52, 38 52 L34 52" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="33" cy="52" r="2.5" fill="#38bdf8"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Contact Us</span>
        </button>

        <!-- 9. FBR (Government Banking Institution Pediment) -->
        <button type="button" onclick="openModal('fbrModal')" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- Official Neoclassical Building with "FBR" on Top -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="fbrGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3730a3"/>
                            <stop offset="100%" stop-color="#1e1b4b"/>
                        </linearGradient>
                    </defs>
                    <!-- Pediment / Triangle -->
                    <path d="M32 10 L8 24 L56 24 Z" fill="url(#fbrGrad)"/>
                    <text x="32" y="21" fill="#ffffff" font-size="7" font-weight="900" font-family="Arial" text-anchor="middle" letter-spacing="1">FBR</text>
                    <!-- Architrave -->
                    <rect x="8" y="24" width="48" height="4" fill="#4338ca"/>
                    <!-- 4 Columns -->
                    <rect x="13" y="28" width="5" height="18" rx="1" fill="#4f46e5"/>
                    <rect x="23" y="28" width="5" height="18" rx="1" fill="#4f46e5"/>
                    <rect x="36" y="28" width="5" height="18" rx="1" fill="#4f46e5"/>
                    <rect x="46" y="28" width="5" height="18" rx="1" fill="#4f46e5"/>
                    <!-- Base Foundation -->
                    <rect x="6" y="46" width="52" height="6" rx="2" fill="url(#fbrGrad)"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">FBR</span>
        </button>

        <!-- 10. Wallet -->
        <a href="{{ route('wallet') }}" class="glass-action-card rounded-2xl p-3 flex flex-col items-center text-center justify-center space-y-2 aspect-square">
            <div class="w-12 h-12 flex items-center justify-center">
                <!-- 3D Indigo/Violet Wallet Icon -->
                <svg viewBox="0 0 64 64" class="w-11 h-11 drop-shadow-md">
                    <defs>
                        <linearGradient id="walletGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6366f1"/>
                            <stop offset="100%" stop-color="#8b5cf6"/>
                        </linearGradient>
                    </defs>
                    <rect x="10" y="16" width="44" height="34" rx="8" fill="url(#walletGrad2)"/>
                    <rect x="34" y="27" width="20" height="12" rx="4" fill="#e0e7ff"/>
                    <circle cx="42" cy="33" r="2.5" fill="#4338ca"/>
                </svg>
            </div>
            <span class="text-[11px] font-bold text-slate-800">Wallet</span>
        </a>

    </div>

    <!-- Botanical Silhouette Accents at Bottom-Right (Matches user.jpeg) -->
    <div class="pt-4 flex justify-end pr-2 pointer-events-none opacity-40">
        <svg viewBox="0 0 100 80" class="w-24 h-20 text-indigo-400 fill-current">
            <path d="M90 75 C70 65, 50 40, 60 10 C68 25, 78 40, 90 75 Z"/>
            <path d="M85 75 C60 55, 30 50, 20 30 C35 35, 55 50, 85 75 Z"/>
            <path d="M88 75 C80 50, 70 30, 80 5 C88 20, 92 45, 88 75 Z"/>
        </svg>
    </div>

</div>

<!-- ================= MODALS ================= -->

<!-- 1. Quick Wallet Modal (Triggered by "Show Wallet 👁") -->
<div id="walletModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3.5 relative border border-white">
        <button type="button" onclick="closeModal('walletModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-sm font-black text-slate-900 flex items-center">
            <i class="fa-solid fa-wallet mr-2 text-indigo-600"></i> My Quick Balances
        </h3>
        <div class="space-y-2 text-xs">
            <div class="p-3 bg-indigo-50/80 rounded-2xl border border-indigo-100 flex justify-between items-center">
                <span class="text-slate-600 font-semibold">Main Balance (Withdrawable):</span>
                <strong class="text-indigo-700 text-sm font-black">Rs {{ number_format(auth()->user()->wallet?->withdrawable_balance ?? 0, 2) }}</strong>
            </div>
            <div class="p-3 bg-purple-50/80 rounded-2xl border border-purple-100 flex justify-between items-center">
                <span class="text-slate-600 font-semibold">Rewards Balance:</span>
                <strong class="text-purple-700 text-sm font-black">Rs {{ number_format(auth()->user()->wallet?->rewards_balance ?? 0, 2) }}</strong>
            </div>
        </div>
        <a href="{{ route('wallet') }}" class="block text-center py-2.5 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-xl text-xs font-bold shadow-md">
            View Full Wallet & Withdraw &rarr;
        </a>
    </div>
</div>

<!-- 2. FBR Registration Certificate Modal -->
<div id="fbrModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3.5 relative border border-white">
        <button type="button" onclick="closeModal('fbrModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-indigo-900 text-white flex items-center justify-center text-base shadow">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900">FBR Registered Entity</h3>
                <span class="text-[10px] text-emerald-600 font-bold">NTN Verified Taxpayer</span>
            </div>
        </div>
        <div class="space-y-2 text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <p><strong>Registration Status:</strong> Active & Compliant</p>
            <p><strong>Entity Type:</strong> Digital Marketing & Advertising Services</p>
            <p class="text-[11px] text-slate-500">
                Canva ADS operates under registered digital advertisement tax compliance rules in accordance with official national tax regulations.
            </p>
        </div>
        <button type="button" onclick="closeModal('fbrModal')" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow">
            Close Verification
        </button>
    </div>
</div>

<!-- 3. Guide Modal -->
<div id="guideModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
        <button type="button" onclick="closeModal('guideModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-sm font-black text-slate-900 flex items-center">
            <i class="fa-solid fa-book-open mr-2 text-indigo-600"></i> Canva ADS User Guide
        </h3>
        <div class="space-y-2 text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-2xl">
            <p><strong>1. First 15 Days:</strong> Watch daily ads and earn rewards without any referral requirement.</p>
            <p><strong>2. After Day 15:</strong> Bring 1 approved direct referral every 7 days to keep tasks unlocked.</p>
            <p><strong>3. Rewards:</strong> Earn Rs 7 to Main Balance and Rs 200 to Rewards Balance on every verified ad.</p>
            <p><strong>4. Withdrawals:</strong> 1st and 2nd withdrawals minimum is Rs 30 each; 3rd withdrawal onwards minimum is Rs 500.</p>
        </div>
        <button type="button" onclick="closeModal('guideModal')" class="w-full py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow">
            Got It
        </button>
    </div>
</div>

<!-- 4. Official Channels Modal -->
<div id="channelModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
        <button type="button" onclick="closeModal('channelModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-sm font-black text-slate-900 flex items-center">
            <i class="fa-solid fa-paper-plane mr-2 text-cyan-500"></i> Official Channels
        </h3>
        <p class="text-xs text-slate-500">Join our official verified channels for daily payment proofs and announcements:</p>

        <div class="space-y-2.5 pt-1">
            @forelse($channels ?? [] as $channel)
                <a href="{{ $channel->url }}" target="_blank"
                   class="flex items-center justify-between w-full py-3 px-3.5 bg-gradient-to-r from-slate-50 to-indigo-50/50 hover:bg-indigo-50 border border-slate-200/80 rounded-2xl transition shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center text-base text-indigo-600">
                            <i class="{{ $channel->icon }}"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $channel->title }}</span>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                </a>
            @empty
                <div class="p-3 bg-slate-100 rounded-xl text-center text-xs text-slate-400">
                    Official channels will be posted shortly.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- 5. About Us Modal -->
<div id="aboutModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3.5 relative border border-white">
        <button type="button" onclick="closeModal('aboutModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-400 to-blue-600 flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-circle-info text-base"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900">About Us</h3>
                <span class="text-[10px] text-slate-400 font-semibold">Canva Platform</span>
            </div>
        </div>
        <div class="space-y-2.5 text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 max-h-72 overflow-y-auto">
            <p class="font-bold text-slate-900 text-xs">About Us — Canva</p>
            <p>
                Canva is a well-known online design platform founded in 2013, with users around the world. Our platform is designed to provide members with a simple digital experience, featuring task activities, referral options, package information, and a user-friendly dashboard.
            </p>
            <p>
                Members can explore available tasks, track their account activity, manage their wallet, and invite others through the referral system. Our goal is to keep the platform organized, accessible, and easy to navigate.
            </p>
        </div>
        <button type="button" onclick="closeModal('aboutModal')" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow">
            Close
        </button>
    </div>
</div>

<!-- 6. Support Modal -->
<div id="supportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="glass-action-card bg-white rounded-3xl p-5 w-full max-w-sm shadow-2xl space-y-3 relative border border-white">
        <button type="button" onclick="closeModal('supportModal')" class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-sm font-black text-slate-900 flex items-center">
            <i class="fa-solid fa-headset mr-2 text-rose-500"></i> Contact Us & Support
        </h3>
        <p class="text-xs text-slate-500">Need help with package activation or withdrawal?</p>
        <div class="space-y-1.5 text-xs text-slate-700 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
            <div><span class="text-slate-400">Support Hours:</span> <strong>9:00 AM – 10:00 PM</strong></div>
            <div><span class="text-slate-400">Email:</span> <strong>support@canvaads.com</strong></div>
            <div><span class="text-slate-400">WhatsApp:</span> <strong class="font-mono">+92 300 1234567</strong></div>
        </div>
        <a href="https://wa.me/923001234567" target="_blank" class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold text-center shadow">
            Chat on WhatsApp &rarr;
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Robust Universal Copy Function (Works on HTTP, HTTPS, iOS, and Android)
    function copyReferralLinkUniversal(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Referral link copied to clipboard!");
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showToast("Referral link copied to clipboard!");
        } catch (err) {
            prompt("Copy your referral link below:", text);
        }
        document.body.removeChild(textArea);
    }

    function showToast(msg) {
        const toast = document.getElementById('copyToast');
        document.getElementById('toastMessage').innerText = msg;
        toast.classList.remove('-translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('-translate-y-20', 'opacity-0');
        }, 2200);
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
