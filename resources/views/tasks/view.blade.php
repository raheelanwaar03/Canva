@extends('layouts.app')

@php
    $headerTitle = 'Watch & Earn';
@endphp

@section('content')
<div class="space-y-4 pt-1 max-w-lg mx-auto">

    <!-- Top Info & Timer Bar -->
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-sm font-black text-slate-800">{{ $task->title }}</h2>
            <p class="text-[11px] text-slate-400">Keep video visible until timer finishes</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex flex-col items-center justify-center">
                <span id="countdown" class="text-base font-black text-indigo-600">{{ $task->duration_seconds ?? 15 }}</span>
                <span class="text-[9px] font-bold text-slate-400 -mt-1">sec</span>
            </div>
        </div>
    </div>

    <!-- Video Embed Screen -->
    <div class="bg-black rounded-3xl overflow-hidden aspect-video shadow-lg relative border border-slate-800 flex items-center justify-center">
        @if(str_contains($task->embed_url, 'youtube.com/embed') || str_contains($task->embed_url, 'vimeo.com'))
            <iframe id="adFrame"
                    class="w-full h-full"
                    src="{{ $task->embed_url }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        @elseif(str_ends_with(strtolower($task->ad_url), '.mp4'))
            <video id="adVideo" class="w-full h-full object-cover" autoplay controls playsinline>
                <source src="{{ $task->ad_url }}" type="video/mp4">
                Your browser does not support HTML video.
            </video>
        @else
            <!-- External Web Page fallback inside frame -->
            <iframe id="adFrame" class="w-full h-full bg-white" src="{{ $task->ad_url }}"></iframe>
        @endif
    </div>

    <!-- Claim Reward Form (Locked until timer hits 0) -->
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 text-center space-y-3">
        <div id="rewardStatus" class="text-xs font-bold text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 mr-1.5"></i>
            <span>Watching in progress... Reward will unlock shortly.</span>
        </div>

        <form id="completeTaskForm" action="{{ route('tasks.complete', $task->id) }}" method="POST">
            @csrf
            <button id="claimBtn" type="submit" disabled
                    style="background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%);"
                    class="w-full py-3.5 px-6 rounded-2xl text-xs font-black text-white shadow-md opacity-40 cursor-not-allowed transition duration-200">
                Complete Task & Claim Reward
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let timeLeft = {{ $task->duration_seconds ?? 15 }};
        const countdownEl = document.getElementById('countdown');
        const claimBtn = document.getElementById('claimBtn');
        const rewardStatus = document.getElementById('rewardStatus');

        const timer = setInterval(() => {
            timeLeft--;
            if (timeLeft >= 0) {
                countdownEl.innerText = timeLeft;
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownEl.innerText = "0";

                // Unlock Claim Button
                claimBtn.disabled = false;
                claimBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                claimBtn.classList.add('hover:opacity-95', 'active:scale-95');

                rewardStatus.innerHTML = `
                    <span class="text-emerald-600 font-black inline-flex items-center space-x-1">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span>Video Completed! Tap below to claim your earnings.</span>
                    </span>
                `;
            }
        }, 1000);
    });
</script>
@endpush
@endsection
