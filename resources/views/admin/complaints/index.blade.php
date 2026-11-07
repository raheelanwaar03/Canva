@extends('layouts.admin')

@php
    $title = 'Complaint Management';
@endphp

@section('admin_content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Complaint Management</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Review user issues, update status, and respond directly.</p>
        </div>
        <span class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-black rounded-2xl shadow-sm">
            {{ $complaints->total() }} Total Complaints
        </span>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">

        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-4">
            @php $currentStatus = request('status', 'all'); @endphp
            <a href="{{ route('admin.complaints.index', ['status' => 'all']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                All
            </a>
            <a href="{{ route('admin.complaints.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Pending
            </a>
            <a href="{{ route('admin.complaints.index', ['status' => 'in_progress']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'in_progress' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                In Progress
            </a>
            <a href="{{ route('admin.complaints.index', ['status' => 'resolved']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Resolved
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">#ID</th>
                        <th class="px-5 py-3.5">User Details</th>
                        <th class="px-5 py-3.5">Subject & Message</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $c)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 font-mono font-bold text-slate-400">#{{ $c->id }}</td>
                            <td class="px-5 py-4">
                                <div class="font-extrabold text-slate-900">{{ $c->user->name ?? 'User #' . $c->user_id }}</div>
                                <div class="text-[11px] text-indigo-600">{{ $c->user->email ?? '' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $c->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-5 py-4 max-w-sm">
                                <div class="font-bold text-slate-800 text-xs">{{ $c->subject }}</div>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $c->message }}</p>
                                @if($c->admin_reply)
                                    <div class="mt-1 text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 font-semibold">
                                        Reply: "{{ $c->admin_reply }}"
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($c->status === 'resolved')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-xl text-[10px] font-black border border-emerald-100">Resolved</span>
                                @elseif($c->status === 'in_progress')
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-[10px] font-black border border-indigo-100">In Progress</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-xl text-[10px] font-black border border-amber-200">Pending</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-400 font-semibold text-[11px]">
                                {{ $c->created_at->format('d M, Y h:i A') }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <button type="button"
                                    onclick="openComplaintModal({{ json_encode([
                                        'id' => $c->id,
                                        'name' => $c->user->name ?? 'User',
                                        'email' => $c->user->email ?? '',
                                        'subject' => $c->subject,
                                        'message' => $c->message,
                                        'status' => $c->status,
                                        'admin_reply' => $c->admin_reply ?? '',
                                        'update_url' => route('admin.complaints.updateStatus', $c->id)
                                    ]) }})"
                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-bold text-xs transition">
                                    Manage
                                </button>

                                <form method="POST" action="{{ route('admin.users.impersonate', $c->user_id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-xs transition">
                                        Login
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-bold">
                                No complaints found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Manage Complaint Modal -->
<div id="complaintModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-800">Resolve Complaint #<span id="mCompId"></span></h3>
            <button type="button" onclick="closeComplaintModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
        </div>

        <div class="space-y-2 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div><span class="text-slate-400 font-bold">User:</span> <strong id="mCompUser" class="text-slate-800"></strong> (<span id="mCompEmail" class="text-indigo-600"></span>)</div>
            <div><span class="text-slate-400 font-bold">Subject:</span> <strong id="mCompSubject" class="text-slate-800"></strong></div>
            <div class="pt-1"><span class="text-slate-400 font-bold block mb-1">Message:</span>
                <p id="mCompMessage" class="text-slate-700 font-medium whitespace-pre-wrap"></p>
            </div>
        </div>

        <form id="complaintForm" method="POST" action="" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Update Status</label>
                <select id="mCompStatus" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Reply / Note to User</label>
                <textarea id="mCompReply" name="admin_reply" rows="3" placeholder="Provide feedback or solution to the user..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-800 focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeComplaintModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openComplaintModal(data) {
    document.getElementById('mCompId').innerText = data.id;
    document.getElementById('mCompUser').innerText = data.name;
    document.getElementById('mCompEmail').innerText = data.email;
    document.getElementById('mCompSubject').innerText = data.subject;
    document.getElementById('mCompMessage').innerText = data.message;
    document.getElementById('mCompStatus').value = data.status;
    document.getElementById('mCompReply').value = data.admin_reply;
    document.getElementById('complaintForm').action = data.update_url;
    document.getElementById('complaintModal').classList.remove('hidden');
}

function closeComplaintModal() {
    document.getElementById('complaintModal').classList.add('hidden');
}
</script>
@endsection
