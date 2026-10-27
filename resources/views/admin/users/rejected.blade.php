@extends('layouts.admin')

@php
    $title = 'Rejected Users';
@endphp

@section('admin_content')

<div class="p-4 sm:p-8 space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Rejected Users</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Users whose package applications were declined. You can re-approve them at any time.</p>
        </div>
        <span class="px-4 py-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black rounded-2xl shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-ban text-rose-500"></i>
            <span>{{ $users->count() }} Rejected Accounts</span>
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
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-black shadow-inner">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Rejected Archive</h3>
                    <p class="text-[11px] font-semibold text-slate-400">Re-approve accounts to move them back into the pending verification queue.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">#ID</th>
                        <th class="px-5 py-3.5">Applicant Details</th>
                        <th class="px-5 py-3.5">Referral Code</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 font-mono font-black text-slate-400">#{{ $user->id }}</td>
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-800 text-sm">{{ $user->name }}</div>
                                <div class="text-[11px] font-semibold text-rose-600">{{ $user->email }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-indigo-600 text-xs">
                                {{ $user->referral_code }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-xl text-[11px] font-black border border-rose-200">
                                    Rejected
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <form method="POST" action="{{ route('admin.users.reapprove', $user->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Restore {{ $user->name }} back to Pending Requests?');"
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition inline-flex items-center space-x-1 shadow-sm">
                                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                        <span>Re-Approve</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition inline-block">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 font-bold">
                                No rejected accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
