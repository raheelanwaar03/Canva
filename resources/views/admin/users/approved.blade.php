@extends('layouts.admin')

@php
    $title = 'Approved Users';
@endphp

@section('admin_content')

<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Approved Members</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Users with an active or upgraded package plan who have full access.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-2xl shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>{{ $users->count() }} Approved Accounts</span>
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black shadow-inner">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Active Member Directory</h3>
                    <p class="text-[11px] font-semibold text-slate-400">Manage balances, packages, uplines, and impersonation.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">#ID</th>
                        <th class="px-5 py-3.5">User Details</th>
                        <th class="px-5 py-3.5">Direct Upline</th>
                        <th class="px-5 py-3.5">Active Plan</th>
                        <th class="px-5 py-3.5">Main Balance</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 font-mono font-black text-slate-400">#{{ $user->id }}</td>
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-800 text-sm">{{ $user->name }}</div>
                                <div class="text-[11px] font-semibold text-indigo-600">{{ $user->email }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->referrer)
                                    <div class="font-bold text-slate-700">{{ $user->referrer->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $user->referrer->email }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Direct (No Upline)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-extrabold border border-emerald-100 inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-crown text-[10px] text-emerald-500"></i>
                                    <span>{{ $user->activePackage->package->name ?? 'Basic' }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 font-black text-slate-800 text-sm">
                                Rs {{ number_format($user->wallet->main_balance ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-bold text-xs transition inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                                        <span>Login</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-bold">
                                No approved members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
