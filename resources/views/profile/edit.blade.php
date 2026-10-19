@extends('layouts.app')

@php
    $headerTitle = 'Account Settings';
    $currentLvl = auth()->user()->referral_level;
@endphp

@section('content')
    <div class="space-y-4 pt-1 pb-6">

        <!-- Profile Avatar & Details Header -->
        <div class="glass-card-item rounded-3xl p-5 text-center relative overflow-hidden shadow-md">
            <div
                class="w-20 h-20 rounded-full p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-md mx-auto mb-3">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Avatar"
                    class="w-full h-full rounded-full object-cover">
            </div>
            <h2 class="text-lg font-black text-slate-900 leading-tight">{{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>

            <!-- Read-Only Status Badges: Membership, Referral Level & Code -->
            <div class="mt-3 flex flex-wrap justify-center items-center gap-1.5">
                <span
                    class="px-3 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-700 border border-purple-200">
                    <i class="fa-solid fa-crown text-[9px] mr-1 text-purple-600"></i>
                    {{ auth()->user()->activePackage?->package?->name ?? 'Basic' }}
                </span>
                <span
                    class="px-3 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700 border border-indigo-200">
                    <i class="fa-solid fa-layer-group text-[9px] mr-1 text-indigo-600"></i>
                    Level {{ $currentLvl }}
                </span>
                <span
                    class="px-3 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    Code: {{ auth()->user()->referral_code }}
                </span>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>Profile details updated successfully!</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>Password updated successfully!</span>
            </div>
        @endif

        <!-- Update Personal Info & DP Form -->
        <div class="glass-card-item rounded-3xl p-5 shadow-md space-y-3.5">
            <div>
                <h3 class="text-sm font-black text-slate-900">Personal Information & DP</h3>
                <p class="text-[10px] text-slate-400">Update your account profile picture, name, and email</p>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('patch')

                <!-- Profile Picture (DP) Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Profile Picture (DP)</label>
                    <div class="flex items-center space-x-3.5">
                        <div
                            class="w-14 h-14 rounded-full p-[2px] bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 shadow flex-shrink-0">
                            <img id="avatarPreview" src="{{ auth()->user()->profile_photo_url }}" alt="Avatar Preview"
                                class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                onchange="previewAvatar(event)"
                                class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <span class="text-[9px] text-slate-400 block mt-1">PNG, JPG, or WEBP up to 2MB</span>
                        </div>
                    </div>
                    @if ($errors->has('avatar'))
                        <span class="text-[10px] text-rose-500 mt-1 block">{{ $errors->first('avatar') }}</span>
                    @endif
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                    @if ($errors->has('name'))
                        <span class="text-[10px] text-rose-500 mt-1 block">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-[10px] text-rose-500 mt-1 block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Read-Only Referral Level Display (Non-Editable) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Referral Level (System Assigned)</label>
                    <div
                        class="flex items-center bg-slate-100/80 border border-slate-200 rounded-2xl px-3.5 py-2.5 cursor-not-allowed">
                        <div
                            class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-2 text-xs flex-shrink-0">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="text" readonly
                            value="Level {{ $currentLvl }} ({{ auth()->user()->approvedDirectReferralsCount() }} approved referrals)"
                            class="w-full bg-transparent border-0 text-xs text-slate-600 font-bold focus:outline-none p-0 cursor-not-allowed">
                    </div>
                    <span class="text-[9px] text-slate-400 block mt-1">This level increases automatically based on your
                        approved direct referrals.</span>
                </div>

                <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-md hover:opacity-95 transition mt-2">
                    Save Changes & Upload DP
                </button>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="glass-card-item rounded-3xl p-5 shadow-md space-y-3.5">
            <div>
                <h3 class="text-sm font-black text-slate-900">Security & Password</h3>
                <p class="text-[10px] text-slate-400">Update your account login password</p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-3">
                @csrf
                @method('put')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Current Password</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="password" name="current_password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                    @if ($errors->updatePassword->has('current_password'))
                        <span
                            class="text-[10px] text-rose-500 mt-1 block">{{ $errors->updatePassword->first('current_password') }}</span>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                    @if ($errors->updatePassword->has('password'))
                        <span
                            class="text-[10px] text-rose-500 mt-1 block">{{ $errors->updatePassword->first('password') }}</span>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                    <div
                        class="flex items-center bg-white/90 border border-slate-200 rounded-2xl px-3.5 py-2 focus-within:ring-2 focus-within:ring-indigo-400">
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full bg-transparent border-0 text-xs text-slate-800 font-semibold focus:outline-none p-0">
                    </div>
                </div>

                <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-md hover:opacity-95 transition">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Sign Out Button -->
        <div class="pt-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-3 rounded-2xl text-xs border border-rose-200 shadow-sm transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Sign Out from Canva ADS</span>
                </button>
            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            function previewAvatar(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('avatarPreview').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>
    @endpush
@endsection
