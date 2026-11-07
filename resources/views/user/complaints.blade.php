@extends('layouts.app')

@php
    $headerTitle = 'Complaint & Help';
@endphp

@section('content')
    <div class="space-y-4 pt-1 pb-16">

        <!-- Header Ribbon -->
        <div class="rounded-2xl px-4 py-2.5 flex items-center space-x-2 text-white shadow-md"
            style="background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);">
            <a href="{{ route('dashboard') }}"
                class="w-6 h-6 rounded-lg bg-white/25 flex items-center justify-center text-xs hover:bg-white/40 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <span class="text-xs font-black tracking-wider uppercase">Submit Issue / Suggestion</span>
        </div>

        @if (session('success'))
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Complaint Form Card -->
        <div class="glass-action-card bg-white rounded-3xl p-5 shadow-sm border border-slate-100 space-y-3.5">
            <div>
                <h2 class="text-sm font-black text-slate-800">Submit Your Problem / Feedback</h2>
                <p class="text-[11px] text-slate-400">Describe your issue in your own words. Our support team will answer
                    promptly.</p>
            </div>

            <form action="{{ route('complaints.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject (Optional)</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                        placeholder="e.g. Deposit TID inquiry, Ad issue, etc."
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Explain Your Issue <span
                            class="text-rose-500">*</span></label>
                    <textarea name="message" rows="4" required placeholder="Write your problem or suggestion clearly here..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500">{{ old('message') }}</textarea>
                </div>

                <button type="submit" style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                    class="w-full text-white font-black py-3 px-6 rounded-2xl shadow-lg hover:opacity-95 transition text-xs">
                    Submit Complaint →
                </button>
            </form>
        </div>

        <!-- User Past Complaints History -->
        <div class="space-y-2.5 pt-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-600 px-1">My Submitted Complaints
                ({{ $complaints->total() }})</h3>

            @forelse($complaints as $c)
                <div class="glass-action-card bg-white rounded-2xl p-4 border border-slate-100 space-y-2 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800">{{ $c->subject }}</span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase
                        {{ $c->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : ($c->status === 'in_progress' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ str_replace('_', ' ', $c->status) }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium">
                        {{ $c->message }}
                    </p>

                    @if ($c->admin_reply)
                        <div class="p-3 bg-indigo-50/70 rounded-xl border border-indigo-100 space-y-0.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 flex items-center">
                                <i class="fa-solid fa-headset mr-1"></i> Admin Response:
                            </span>
                            <p class="text-xs font-semibold text-slate-800">{{ $c->admin_reply }}</p>
                        </div>
                    @endif

                    <span class="text-[10px] text-slate-400 block pt-0.5">Submitted on
                        {{ $c->created_at->format('d M, Y • h:i A') }}</span>
                </div>
            @empty
                <div class="glass-action-card rounded-2xl p-5 text-center text-xs text-slate-400 bg-white">
                    You have not submitted any complaints yet.
                </div>
            @endforelse

            @if ($complaints->hasPages())
                <div class="pt-2">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
