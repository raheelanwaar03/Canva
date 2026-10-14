@extends('layouts.app')

@php
    $headerTitle = 'Watching Ad';
@endphp

@section('content')
<div class="space-y-4 pt-1">

    <!-- Timer Header Card -->
    <div class="glass-card-item rounded-2xl p-4 text-center">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reward Verification</span>
        <div class="text-3xl font-black text-indigo-600 my-1 font-mono" id="countdownTimer">
            {{ $task->duration_seconds }}
        </div>
        <p class="text-xs text-slate-500" id="timerStatus">Please keep this window open until the countdown finishes...</p>

        <!-- Claim Reward Button (Disabled until timer ends) -->
        <button id="claimRewardBtn" onclick="claimReward()" disabled
                class="mt-3 w-full py-3 rounded-2xl text-xs font-bold text-white shadow-md bg-slate-300 cursor-not-allowed transition">
            Waiting for timer...
        </button>
    </div>

    <!-- Ad Preview Display -->
    <div class="glass-card-item rounded-2xl p-4 space-y-3">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">{{ $task->title }}</h3>
                <span class="text-[10px] text-indigo-600 font-semibold">+Rs {{ number_format($activeSub->package->reward_per_task, 2) }}</span>
            </div>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed">{{ $task->description }}</p>

        <!-- Simulated Ad Frame -->
        <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-900 text-white p-6 text-center shadow-inner">
            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-cyan-400 via-indigo-500 to-fuchsia-500 mx-auto flex items-center justify-center text-xl font-black mb-3">
                Canva
            </div>
            <h4 class="font-extrabold text-sm mb-1">Empowering the world to design</h4>
            <p class="text-[11px] text-slate-300 mb-4">Create stunning social media posts, presentations, and videos in minutes.</p>
            <a href="{{ $task->ad_url }}" target="_blank" class="inline-block px-4 py-1.5 bg-white text-indigo-700 rounded-xl text-xs font-bold">
                Visit Partner Site <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let timeLeft = {{ $task->duration_seconds }};
    const timerElem = document.getElementById('countdownTimer');
    const statusElem = document.getElementById('timerStatus');
    const claimBtn = document.getElementById('claimRewardBtn');

    const countdown = setInterval(() => {
        timeLeft--;
        timerElem.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerElem.innerText = "✓ Ready";
            timerElem.classList.remove('text-indigo-600');
            timerElem.classList.add('text-emerald-500');

            statusElem.innerText = "Ad complete! Click below to collect your reward.";
            claimBtn.disabled = false;
            claimBtn.innerText = "Claim Reward Now";
            claimBtn.classList.remove('bg-slate-300', 'cursor-not-allowed');
            claimBtn.style.background = "linear-gradient(90deg, #10b981 0%, #059669 100%)";
        }
    }, 1000);

    function claimReward() {
        claimBtn.disabled = true;
        claimBtn.innerText = "Processing Reward...";

        fetch("{{ route('tasks.complete', $task->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = "{{ route('tasks.index') }}";
            } else {
                alert(data.message);
                claimBtn.disabled = false;
                claimBtn.innerText = "Try Again";
            }
        })
        .catch(err => {
            alert("Something went wrong while crediting reward.");
            claimBtn.disabled = false;
        });
    }
</script>
@endpush
@endsection
