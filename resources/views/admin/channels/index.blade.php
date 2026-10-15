@extends('layouts.app')

@php
    $headerTitle = 'Manage Channels';
@endphp

@section('content')
<div class="space-y-4 pt-1 pb-6">
    <div>
        <h2 class="text-lg font-black text-slate-900 tracking-tight">Community Channels</h2>
        <p class="text-[11px] text-slate-500">Manage links for Canva Official and Extra Bonus & Rewards</p>
    </div>

    <!-- Admin Sub-Nav -->
    <div class="grid grid-cols-5 gap-1.5 text-center text-[9px] font-bold">
        <a href="{{ route('admin.payments.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payments</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payouts</a>
        <a href="{{ route('admin.packages.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Packages</a>
        <a href="{{ route('admin.tasks.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Ads</a>
        <a href="{{ route('admin.channels.index') }}" class="p-2 rounded-xl bg-indigo-600 text-white shadow-md">Channels</a>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Add New Channel Form -->
    <div class="glass-card-item rounded-3xl p-4 space-y-3 shadow-md">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-700">Add New Official Link</h3>
        <form action="{{ route('admin.channels.store') }}" method="POST" class="space-y-2.5">
            @csrf
            <div>
                <label class="block text-[10px] text-slate-500 font-bold mb-1">Channel Title</label>
                <input type="text" name="title" required placeholder="e.g. Canva Extra Bonus & Rewards"
                       class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label class="block text-[10px] text-slate-500 font-bold mb-1">Target URL</label>
                <input type="url" name="url" required placeholder="https://chat.whatsapp.com/... or https://t.me/..."
                       class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label class="block text-[10px] text-slate-500 font-bold mb-1">Platform Icon (FontAwesome)</label>
                <select name="icon" class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="fa-brands fa-whatsapp">WhatsApp</option>
                    <option value="fa-brands fa-telegram">Telegram</option>
                    <option value="fa-brands fa-youtube">YouTube</option>
                    <option value="fa-solid fa-paper-plane">General Channel</option>
                </select>
            </div>
            <button type="submit"
                    style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-bold py-2.5 rounded-xl text-xs shadow-md hover:opacity-95 transition mt-1">
                + Save Channel Link
            </button>
        </form>
    </div>

    <!-- Channels List -->
    <div class="space-y-2.5">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Configured Channels ({{ $channels->count() }})</h3>
        @forelse($channels as $ch)
            <div class="glass-card-item rounded-3xl p-4 shadow-sm space-y-3">
                <form action="{{ route('admin.channels.update', $ch->id) }}" method="POST" class="space-y-2">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="{{ $ch->icon }} text-indigo-600 text-sm"></i>
                            <input type="text" name="title" value="{{ old('title', $ch->title) }}" required
                                   class="bg-white/80 border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold text-slate-800 focus:outline-none">
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ch->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $ch->is_active ? 'Active' : 'Paused' }}
                        </span>
                    </div>

                    <div>
                        <input type="url" name="url" value="{{ old('url', $ch->url) }}" required
                               class="w-full bg-white/80 border border-slate-200 rounded-lg px-2.5 py-1 text-xs text-slate-600 font-mono focus:outline-none">
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <button type="submit" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-lg transition">
                            Update
                        </button>
                </form>

                        <div class="flex items-center space-x-1.5">
                            <form action="{{ route('admin.channels.toggle', $ch->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $ch->is_active ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $ch->is_active ? 'Pause' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.channels.destroy', $ch->id) }}" method="POST" onsubmit="return confirm('Delete this channel?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
            </div>
        @empty
            <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                No official channels configured.
            </div>
        @endforelse
    </div>
</div>
@endsection
