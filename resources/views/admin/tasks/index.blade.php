@extends('layouts.app')

@php
    $headerTitle = 'Manage Ad Tasks';
@endphp

@section('content')
<div class="space-y-4 pt-1">
    <div>
        <h2 class="text-lg font-black text-slate-900 tracking-tight">Advertising Tasks</h2>
        <p class="text-[11px] text-slate-500">Create new ad campaigns & manage active ads</p>
    </div>

    <!-- Admin Sub-Nav -->
    <div class="grid grid-cols-4 gap-1.5 text-center text-[10px] font-bold">
        <a href="{{ route('admin.payments.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Payments</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Withdrawals</a>
        <a href="{{ route('admin.packages.index') }}" class="p-2 rounded-xl bg-white/70 text-slate-600 hover:bg-white shadow-sm">Packages</a>
        <a href="{{ route('admin.tasks.index') }}" class="p-2 rounded-xl bg-indigo-600 text-white shadow-md">Ads</a>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-semibold shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Add New Ad Form -->
    <div class="glass-card-item rounded-3xl p-4 space-y-3 shadow-md">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-700">Add New Ad Campaign</h3>
        <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-2.5">
            @csrf
            <div>
                <input type="text" name="title" required placeholder="Ad Title (e.g. Canva Pro Features)"
                       class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <input type="url" name="ad_url" required placeholder="Target Link (https://...)"
                       class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" name="duration_seconds" value="15" min="5" max="120" required placeholder="Duration (seconds)"
                       class="w-full bg-white/90 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <button type="submit"
                        style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                        class="w-full text-white font-bold py-2 rounded-xl text-xs shadow-md hover:opacity-95 transition">
                    + Add Ad Task
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Ads List -->
    <div class="space-y-2.5">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 px-1">Active Ad Inventory ({{ $tasks->count() }})</h3>
        @forelse($tasks as $t)
            <div class="glass-card-item rounded-2xl p-3.5 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $t->title }}</h4>
                    <div class="text-[10px] text-slate-400 mt-0.5 flex items-center space-x-2">
                        <span><i class="fa-regular fa-clock mr-1"></i>{{ $t->duration_seconds }}s</span>
                        <span>•</span>
                        <a href="{{ $t->ad_url }}" target="_blank" class="text-indigo-600 underline truncate max-w-[140px]">{{ $t->ad_url }}</a>
                    </div>
                </div>

                <div class="flex items-center space-x-1.5">
                    <form action="{{ route('admin.tasks.toggle', $t->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $t->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $t->is_active ? 'Active' : 'Paused' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this ad?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-6 h-6 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center text-[10px]">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card-item rounded-2xl p-6 text-center text-xs text-slate-400">
                No ads in inventory.
            </div>
        @endforelse
    </div>
</div>
@endsection
