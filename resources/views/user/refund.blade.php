@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-4 space-y-4 pb-20">
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-600">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </a>
        <h1 class="text-lg font-black text-slate-800">Apply for Refund</h1>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold space-y-1">
            @foreach ($errors->all() as $error)
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- STATE 1: PENDING --}}
    @if($latestRefund && $latestRefund->status === 'pending')
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center space-y-3">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-3xl mx-auto flex items-center justify-center text-2xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h2 class="text-base font-black text-slate-800">Refund Under Review</h2>
            <p class="text-xs text-slate-500">
                Your refund request of <strong class="text-slate-800">Rs {{ number_format($latestRefund->amount, 2) }}</strong> was submitted on {{ $latestRefund->created_at->format('d M, Y') }}.
            </p>
            <div class="p-3 bg-amber-50 text-amber-700 rounded-2xl text-xs font-bold border border-amber-200">
                Decision will be made within 30 days (by {{ $latestRefund->decision_deadline ? $latestRefund->decision_deadline->format('d M, Y') : '30 Days' }}).
            </div>
        </div>

    {{-- STATE 2: APPROVED (Account marked rejected) --}}
    @elseif($latestRefund && $latestRefund->status === 'approved')
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center space-y-3">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-3xl mx-auto flex items-center justify-center text-2xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="text-base font-black text-slate-800">Refund Approved</h2>
            <p class="text-xs text-slate-500">
                Your refund of <strong>Rs {{ number_format($latestRefund->amount, 2) }}</strong> was approved and disbursed.
            </p>
            <div class="p-3 bg-slate-50 text-slate-600 rounded-2xl text-xs font-bold border border-slate-100">
                Account has been closed as the plan fee was refunded.
            </div>
        </div>

    {{-- STATE 3: REJECTED (Account remains normal, show admin remark) --}}
    @elseif($latestRefund && $latestRefund->status === 'rejected')
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-base font-black">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800">Refund Request Declined</h2>
                    <span class="text-[11px] text-slate-400">Your account remains active and in good standing.</span>
                </div>
            </div>

            <div class="p-4 bg-rose-50/70 border border-rose-100 rounded-2xl space-y-1">
                <span class="text-[10px] uppercase font-black tracking-wider text-rose-500 block">Admin Feedback / Reason</span>
                <p class="text-xs font-bold text-slate-800">
                    {{ $latestRefund->admin_remark ?? 'Your refund request does not meet the necessary criteria.' }}
                </p>
            </div>

            <div class="p-3.5 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center space-x-2 text-xs font-bold text-emerald-700">
                <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
                <span>You can continue earning and watching ads normally.</span>
            </div>
        </div>

    {{-- EXPIRED: More than 7 days since registration --}}
    @elseif(!$isEligible)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center space-y-3">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-3xl mx-auto flex items-center justify-center text-2xl">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h2 class="text-base font-black text-slate-800">Refund Window Expired</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Refund requests are only accepted <strong>within 7 days</strong> of account registration.
            </p>
            <div class="p-3 bg-slate-50 rounded-2xl text-xs font-bold text-slate-500 border border-slate-100">
                Your 7-day refund window closed on {{ $refundExpiry->format('d M, Y') }}.
            </div>
        </div>

    {{-- ELIGIBLE: Can submit a refund --}}
    @else
        <!-- 7-Day Guarantee Banner -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-4 text-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-100 block">7-Day Money-Back Guarantee</span>
                <p class="text-xs font-bold text-white">You are eligible to request a refund.</p>
            </div>
            <span class="px-3 py-1 bg-white/20 rounded-xl text-xs font-extrabold backdrop-blur-sm">
                @if($daysRemaining > 0)
                    {{ $daysRemaining }} day(s) left
                @else
                    {{ $hoursRemaining }} hour(s) left
                @endif
            </span>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-4">
            <div>
                <h2 class="text-base font-black text-slate-800">Submit Refund Application</h2>
                <p class="text-xs text-slate-400">Fill in your payment credentials to receive your plan refund.</p>
            </div>

            <form method="POST" action="{{ route('refund.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1">Gateway</label>
                    <select name="gateway_name" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                        <option value="EasyPaisa">EasyPaisa</option>
                        <option value="JazzCash">JazzCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1">Account Title / Name</label>
                    <input type="text" name="account_name" required placeholder="Account Holder Name"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1">Account / Mobile Number</label>
                    <input type="text" name="account_number" required placeholder="03XXXXXXXXX"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1">Reason for Refund</label>
                    <textarea name="reason" rows="3" required placeholder="Please explain why you are requesting a refund..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl text-[11px] text-slate-500 font-semibold flex items-center space-x-2 border border-slate-100">
                    <i class="fa-solid fa-shield-halved text-emerald-500 flex-shrink-0"></i>
                    <span>Admin will review and approve/reject your request within 30 days.</span>
                </div>

                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-200 transition active:scale-95">
                    Submit Refund Request
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
