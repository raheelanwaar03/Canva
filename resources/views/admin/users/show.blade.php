@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Action -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">User Detail — {{ $user->name }}</h1>
            <p class="text-xs text-slate-500">Member since {{ $user->created_at->format('d M, Y') }}</p>
        </div>
        <form action="{{ route('admin.users.loginAs', $user->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-[#3b49df] hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow flex items-center space-x-1.5">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Login as User</span>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: 4 WALLET & METRICS CARDS (Exact match to Client Image) -->
        <div class="space-y-3">

            <!-- Balance Card (Blue) -->
            <div class="bg-[#3b49df] text-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Main Balance</span>
                    <div class="text-xl font-black mt-0.5">Rs {{ number_format($wallet->withdrawable_balance, 2) }}</div>
                </div>
                <i class="fa-solid fa-wallet text-2xl text-indigo-300"></i>
            </div>

            <!-- Deposits Card (Dark Blue) -->
            <div class="bg-[#1e293b] text-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Deposits</span>
                    <div class="text-xl font-black mt-0.5">Rs {{ number_format($totalDeposited, 2) }}</div>
                </div>
                <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400"></i>
            </div>

            <!-- Withdrawals Card (Teal) -->
            <div class="bg-[#059669] text-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-teal-200 uppercase tracking-wider">Total Withdrawals</span>
                    <div class="text-xl font-black mt-0.5">Rs {{ number_format($totalWithdrawn, 2) }}</div>
                </div>
                <i class="fa-solid fa-arrow-up-from-bracket text-2xl text-teal-300"></i>
            </div>

            <!-- Transactions Card (Dark Slate) -->
            <div class="bg-[#0f172a] text-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Transactions</span>
                    <div class="text-xl font-black mt-0.5">{{ $transactionsCount }} Total</div>
                </div>
                <i class="fa-solid fa-arrow-right-arrow-left text-2xl text-slate-400"></i>
            </div>

            <!-- Action Buttons Grid (Client image exact buttons) -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 space-y-3">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Admin Controls</h4>

                <!-- Adjust Balance Modal Trigger -->
                <button type="button" onclick="document.getElementById('balanceModal').classList.remove('hidden')" class="w-full py-2.5 px-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>Manage Balance (+ / -)</span>
                </button>
            </div>
        </div>

        <!-- RIGHT COLUMN: INFORMATION OF USER FORM (Matches Client Screenshot) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
            <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3">Information of {{ $user->name }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block text-slate-500 font-bold mb-1">Full Name</label>
                    <input type="text" readonly value="{{ $user->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-500 font-bold mb-1">Email Address</label>
                    <input type="text" readonly value="{{ $user->email }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-500 font-bold mb-1">Mobile Number</label>
                    <input type="text" readonly value="{{ $user->phone ?? 'Not provided' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-mono focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-500 font-bold mb-1">Referral Level</label>
                    <input type="text" readonly value="Level {{ $user->referral_level }}" class="w-full bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2 text-indigo-700 font-black focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-500 font-bold mb-1">Total Direct Team</label>
                    <input type="text" readonly value="{{ $user->referrals->count() }} Members" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-500 font-bold mb-1">Direct Upline (Sponsor)</label>
                    <input type="text" readonly value="{{ $user->referrer ? $user->referrer->name . ' (' . $user->referrer->email . ')' : 'None (Root User)' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-semibold focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-slate-500 font-bold mb-1">Referral Link</label>
                    <div class="flex space-x-2">
                        <input type="text" readonly value="{{ url('/register?ref=' . $user->referral_code) }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-mono text-xs focus:outline-none">
                        <button type="button" onclick="navigator.clipboard.writeText('{{ url('/register?ref=' . $user->referral_code) }}'); alert('Copied!');" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Adjust Balance -->
<div id="balanceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl space-y-4 relative">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-sm font-black text-slate-900">Manage Balance — {{ $user->name }}</h3>
            <button type="button" onclick="document.getElementById('balanceModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.users.adjustBalance', $user->id) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Action Type</label>
                <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
                    <option value="add">Add Balance (+)</option>
                    <option value="subtract">Deduct Balance (-)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Wallet Target</label>
                <select name="wallet_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
                    <option value="main">Main Balance (Withdrawable)</option>
                    <option value="rewards">Rewards Balance (Non-withdrawable)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Amount (PKR)</label>
                <input type="number" step="1" name="amount" required placeholder="Enter amount..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Remark / Reason</label>
                <input type="text" name="remark" placeholder="e.g. Compensation bonus..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
            </div>

            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow transition">
                Apply Balance Adjustment
            </button>
        </form>
    </div>
</div>
@endsection
