@extends('layouts.app')

@section('content')
<div class="space-y-4 pt-1 pb-16">
    <!-- Header Ribbon -->
    <div class="rounded-2xl px-4 py-2.5 flex items-center justify-between text-white shadow-md"
        style="background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);">
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('dashboard') }}" class="w-7 h-7 rounded-lg bg-white/25 flex items-center justify-center text-xs shadow-inner hover:bg-white/40 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <span class="text-xs font-black tracking-wider uppercase">Terms & Conditions</span>
        </div>
        <span class="text-[10px] font-bold text-indigo-100">Canva Platform</span>
    </div>

    <!-- Terms Body Card -->
    <div class="glass-action-card bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100 space-y-4 text-xs text-slate-600 leading-relaxed">
        <div>
            <h2 class="text-base font-black text-slate-800">Terms & Conditions – Canva Platform</h2>
            <p class="text-[11px] text-slate-400 mt-0.5">Please review the platform rules and code of conduct below.</p>
        </div>

        <div class="space-y-3.5 divide-y divide-slate-100">
            <div class="pt-2">
                <h3 class="text-xs font-black text-slate-800 mb-1">1. Welcome to Our Platform</h3>
                <p>Welcome! We aim to provide users with a clear and user-friendly experience. Please read these terms carefully before using the platform.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">2. Account Responsibility</h3>
                <p>Every user must provide accurate information and keep their login details secure. Users should not create fake accounts or misuse another person’s account.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">3. Tasks & Work</h3>
                <p>Users must complete available tasks according to the given instructions. Task availability and rewards depend on the platform’s rules and eligibility requirements.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">4. Packages & Validity</h3>
                <p>Before purchasing a package, users should carefully review its price, features, task limits, validity, and other conditions. Any package-related charges and benefits must be clearly explained before payment.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">5. Earnings & Withdrawals</h3>
                <p>Earnings must be displayed transparently in the user’s dashboard. Withdrawal requests will be processed according to the published withdrawal rules, limits, and processing times. No earnings or withdrawal amount should be guaranteed unless explicitly confirmed by the platform.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">6. Referrals & Team Building</h3>
                <p>Users may invite others through their referral links and build a team. Referral rewards, eligibility, and level requirements must follow the platform’s published rules. Users must not mislead others or promise guaranteed profits.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">7. Respectful & Honest Working</h3>
                <p>All users are expected to work honestly, follow the rules, and treat other members respectfully. Fake activity, fraudulent payments, and misuse of the platform are prohibited.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">8. Refund Policy</h3>
                <p>Refund requests will be handled according to the platform’s published refund policy. Eligible users may apply under the stated conditions, and the status of their request should be available in their dashboard.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">9. Account Restrictions</h3>
                <p>Accounts may be restricted for fraud, misuse, or serious violations of these terms. Any restriction should be handled fairly and communicated to the affected user.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">10. Support & Transparency</h3>
                <p>Users should have access to clear information about packages, tasks, rewards, withdrawals, and refunds. Any changes to important rules should be communicated clearly.</p>
            </div>

            <div class="pt-3">
                <h3 class="text-xs font-black text-slate-800 mb-1">11. Acceptance of Terms</h3>
                <p>By registering and using the platform, users confirm that they have read and understood these Terms & Conditions.</p>
            </div>
        </div>

        <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-center font-bold text-indigo-700 text-xs">
            Thank you for being part of our community. Work honestly, keep learning, build your team responsibly, and move forward with confidence. ❤️
        </div>
    </div>
</div>
@endsection
