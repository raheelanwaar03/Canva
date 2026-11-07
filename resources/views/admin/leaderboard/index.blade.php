@extends('layouts.admin')

@php
    $title = 'Platform Leaderboard';
@endphp

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#adminLeaderboardTable').DataTable({
                responsive: true,
                pageLength: 20,
                order: [[3, 'desc']], // Sorted by team count
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search top users..."
                }
            });
        });
    </script>
@endpush

@section('admin_content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Top Performance Leaderboard</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Real-time performance ranking based on approved team size and withdrawals.</p>
        </div>
        <span class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
            {{ count($leaderboard) }} Verified Users
        </span>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <div class="overflow-x-auto">
            <table id="adminLeaderboardTable" class="w-full text-left text-xs text-slate-600" style="width:100%">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">User Details</th>
                        <th class="px-4 py-3">Active Plan</th>
                        <th class="px-4 py-3">Total Team Size</th>
                        <th class="px-4 py-3">Today Earnings</th>
                        <th class="px-4 py-3">Total Withdrawn</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($leaderboard as $index => $u)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3.5 font-black text-slate-800">
                                @if($index === 0)
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-400 text-white font-extrabold text-[11px]"><i class="fa-solid fa-crown text-[9px] mr-1"></i>#1</span>
                                @elseif($index === 1)
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-300 text-slate-800 font-extrabold text-[11px]">#2</span>
                                @elseif($index === 2)
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-600 text-white font-extrabold text-[11px]">#3</span>
                                @else
                                    <span class="text-slate-400 font-mono">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-extrabold text-slate-900">{{ $u->name }}</div>
                                <div class="text-[11px] text-indigo-600">{{ $u->email }}</div>
                                <span class="text-[10px] font-bold text-slate-400">Level {{ $u->referral_level }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-black text-[10px]">
                                    {{ $u->activePackage->package->name ?? 'None' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-black text-slate-800">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    {{ number_format($u->team_count ?? 0) }} Members
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-extrabold text-indigo-600">
                                Rs {{ number_format($u->today_task_earnings ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-3.5 font-black text-slate-800">
                                Rs {{ number_format($u->total_withdrawn ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.users.impersonate', $u->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" title="Login as User" class="px-2.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] transition">
                                        <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                                        <span>Login</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.users.show', $u->id) }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 font-bold hover:bg-indigo-600 hover:text-white transition">
                                    Profile →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
