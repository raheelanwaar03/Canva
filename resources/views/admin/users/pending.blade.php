@extends('layouts.admin')

@php
    $title = 'Pending Users';
@endphp

@section('admin_content')

<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">New User Requests</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Applicants with submitted payment receipts awaiting your verification.</p>
        </div>
        <span class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black rounded-2xl shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-clock text-amber-500"></i>
            <span>{{ $users->count() }} Pending Requests</span>
        </span>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-black shadow-inner">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Pending Verification Queue</h3>
                    <p class="text-[11px] font-semibold text-slate-400">Review payment screenshots and approve or reject accounts.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">#ID</th>
                        <th class="px-5 py-3.5">Applicant Details</th>
                        <th class="px-5 py-3.5">Requested Plan</th>
                        <th class="px-5 py-3.5">Sponsor</th>
                        <th class="px-5 py-3.5">Submitted Date</th>
                        <th class="px-5 py-3.5 text-right">Review Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php $latestPayment = $user->packagePayments()->latest()->first(); @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 font-mono font-black text-slate-400">#{{ $user->id }}</td>
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-800 text-sm">{{ $user->name }}</div>
                                <div class="text-[11px] font-semibold text-amber-600">{{ $user->email }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-[11px] font-black border border-indigo-100">
                                    {{ $latestPayment->package->name ?? 'Package Plan' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-700">{{ $user->referrer->name ?? 'Direct' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $user->referrer->email ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-400 font-semibold text-[11px]">
                                {{ $user->created_at->format('d M, Y h:i A') }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition inline-flex items-center space-x-1 shadow-sm">
                                    <span>Verify Payment</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-bold">
                                No pending applicant requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
