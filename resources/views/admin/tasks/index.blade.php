@extends('layouts.admin')

@php
    $title = 'Daily Ads Inventory';
@endphp

@section('admin_content')
    <div class="space-y-6">

        <!-- Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daily Ads Inventory</h1>
                <p class="text-xs font-semibold text-slate-400 mt-1">Manage the video, banner, and website ads available for
                    member watch tasks.</p>
            </div>
            <div>
                <button type="button" onclick="openCreateModal()"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-600/20 active:scale-95 transition inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add New Ad</span>
                </button>
            </div>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold space-y-1">
                @foreach ($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <!-- Main Container Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">

            <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black shadow-inner">
                        <i class="fa-solid fa-rectangle-ad"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Active Task Catalog</h3>
                        <p class="text-[11px] font-semibold text-slate-400">Ads currently in rotation for registered
                            members.</p>
                    </div>
                </div>
                <span class="px-3.5 py-1.5 bg-slate-100 text-slate-700 text-xs font-black rounded-xl">
                    {{ count($tasks ?? []) }} Ads
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">#ID</th>
                            <th class="px-5 py-3.5">Title / Ad Name</th>
                            <th class="px-5 py-3.5">Duration</th>
                            <th class="px-5 py-3.5">Destination Link</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 font-mono font-black text-slate-400">#{{ $task->id }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-800 text-sm">{{ $task->title }}</div>
                                    @if ($task->description)
                                        <div class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ $task->description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-bold text-slate-600">
                                    <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-[11px]">
                                        <i
                                            class="fa-regular fa-clock mr-1 text-slate-400"></i>{{ $task->duration_seconds ?? 15 }}s
                                    </span>
                                </td>
                                <td class="px-5 py-4 max-w-xs truncate font-mono text-[11px] text-indigo-600 font-semibold">
                                    @if ($task->ad_url)
                                        <a href="{{ $task->ad_url }}" target="_blank"
                                            class="hover:underline flex items-center space-x-1">
                                            <span class="truncate">{{ $task->ad_url }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] flex-shrink-0"></i>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">None</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($task->is_active)
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-xl text-[11px] font-extrabold border border-emerald-100 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-check text-[10px] text-emerald-500"></i>
                                            <span>Active</span>
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 rounded-xl text-[11px] font-extrabold border border-slate-200 inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-pause text-[10px] text-slate-400"></i>
                                            <span>Paused</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                    <button type="button"
                                        onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->duration_seconds ?? 15 }}', '{{ addslashes($task->ad_url) }}', '{{ $task->is_active ? 1 : 0 }}')"
                                        class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-bold text-xs transition inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                        <span>Edit</span>
                                    </button>

                                    <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}"
                                        class="inline-block" onsubmit="return confirm('Delete this ad from inventory?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs border border-rose-200 transition inline-flex items-center space-x-1">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-bold">
                                    No ads found in inventory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($tasks, 'links') && $tasks->hasPages())
                <div class="pt-4 border-t border-slate-100">
                    {{ $tasks->links() }}
                </div>
            @endif

        </div>
    </div>

    <!-- ================= 1. ADD NEW AD MODAL ================= -->
    <div id="createModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-800">Add New Ad</h3>
                </div>
                <button type="button" onclick="closeCreateModal()"
                    class="text-slate-400 hover:text-slate-600 text-xl font-bold">
                    ×
                </button>
            </div>

            <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Ad Title / Name</label>
                    <input type="text" name="title" required placeholder="e.g. Canva Brand Kit Promotion"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Short Description</label>
                    <input type="text" name="description" placeholder="Brief description of the ad task..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Duration (Seconds)</label>
                        <input type="number" min="5" name="duration_seconds" value="15" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Status</label>
                        <select name="is_active"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                            <option value="1">Active</option>
                            <option value="0">Paused</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Ad Website URL (`ad_url`)</label>
                    <input type="url" name="ad_url" required placeholder="https://example.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Video / Ad URL (`ad_url`)</label>
                    <input type="url" name="ad_url" required
                        placeholder="https://www.youtube.com/watch?v=... or direct MP4 link"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    <p class="text-[10px] text-slate-400 mt-1">Accepts YouTube links, Vimeo, or raw .mp4 video URLs.</p>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-2xl text-xs font-bold hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-md shadow-indigo-600/20 transition">
                        Publish Ad
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= 2. EDIT AD MODAL ================= -->
    <div id="editModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-800">Edit Ad</h3>
                </div>
                <button type="button" onclick="closeEditModal()"
                    class="text-slate-400 hover:text-slate-600 text-xl font-bold">
                    ×
                </button>
            </div>

            <form id="editForm" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Ad Title / Name</label>
                    <input type="text" id="edit_title" name="title" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Short Description</label>
                    <input type="text" id="edit_description" name="description"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Duration (Seconds)</label>
                        <input type="number" min="5" id="edit_duration_seconds" name="duration_seconds" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Status</label>
                        <select id="edit_is_active" name="is_active"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                            <option value="1">Active</option>
                            <option value="0">Paused</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Ad Website URL (`ad_url`)</label>
                    <input type="url" id="edit_ad_url" name="ad_url" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-2xl text-xs font-bold hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-md shadow-indigo-600/20 transition">
                        Update Ad
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(id, title, description, duration_seconds, ad_url, is_active) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_duration_seconds').value = duration_seconds;
            document.getElementById('edit_ad_url').value = ad_url;
            document.getElementById('edit_is_active').value = is_active;

            document.getElementById('editForm').action = "/admin/tasks/" + id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
