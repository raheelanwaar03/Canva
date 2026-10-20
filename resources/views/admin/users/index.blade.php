@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">All Users</h1>
            <p class="text-xs text-slate-500">Manage all registered platform accounts & view profiles</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 bg-white shadow-sm border border-slate-200 text-indigo-700 rounded-full">
            {{ $users->total() }} Members
        </span>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase font-extrabold text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-4">Email / Phone</th>
                    <th class="py-3 px-4">Upline</th>
                    <th class="py-3 px-4">Active Plan</th>
                    <th class="py-3 px-4">Main Balance</th>
                    <th class="py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $u)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $u->name }}
                            <span class="block text-[10px] text-indigo-600 font-mono">Code: {{ $u->referral_code }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-slate-600 block">{{ $u->email }}</span>
                            <span class="text-slate-400 text-[10px] block font-mono">{{ $u->phone ?? 'No phone' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($u->referrer)
                                <a href="{{ route('admin.users.show', $u->referrer->id) }}" class="font-bold text-indigo-600 hover:underline">
                                    {{ $u->referrer->name }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">Direct / Root</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($u->activePackage)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    {{ $u->activePackage->package->name }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-400">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-extrabold text-slate-900">
                            Rs {{ number_format($u->wallet?->withdrawable_balance ?? 0, 2) }}
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.users.show', $u->id) }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 font-bold hover:bg-indigo-600 hover:text-white transition">
                                Profile &rarr;
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection
