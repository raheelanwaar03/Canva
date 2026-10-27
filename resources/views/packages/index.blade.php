@extends('layouts.app')

@php
    $headerTitle = 'Membership Plans';
@endphp

@section('content')
<div class="space-y-4 pt-1">

    <!-- Header Banner -->
    <div class="text-center mb-3">
        <h2 class="text-xl font-extrabold text-slate-900">Choose Your Plan</h2>
        <p class="text-xs text-slate-500 mt-1">Upgrade or activate your package to unlock daily ads</p>
    </div>

    <!-- Active Package Alert -->
    @if($activePackage)
        <div class="glass-card-item rounded-2xl p-3.5 border-l-4 border-emerald-500 flex items-center justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Current Plan</span>
                <div class="text-sm font-bold text-slate-800">{{ $activePackage->package->name }}</div>
                <div class="text-[11px] text-slate-500">Expires: {{ $activePackage->expires_at->format('d M, Y') }}</div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
        </div>
    @endif

    <!-- 4 Packages Grid -->
    <div class="space-y-3.5">
        @foreach($packages as $pkg)
            @php
                $isCurrent = $activePackage && $activePackage->package_id === $pkg->id;
                $canUpgrade = $activePackage && $pkg->price > $activePackage->package->price;
                $priceToShow = $canUpgrade ? ($pkg->price - $activePackage->package->price) : $pkg->price;
            @endphp

            <div class="glass-card-item rounded-2xl p-4 relative overflow-hidden {{ $isCurrent ? 'ring-2 ring-indigo-500 bg-white/95' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ $pkg->name }}</h3>
                        <span class="text-xs text-indigo-600 font-bold">30 Days Validity</span>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-slate-900">Rs {{ number_format($priceToShow, 0) }}</div>
                        @if($canUpgrade)
                            <span class="text-[10px] text-emerald-600 font-bold block">Upgrade Difference</span>
                        @else
                            <span class="text-[10px] text-slate-400 block">Standard Price</span>
                        @endif
                    </div>
                </div>
                <!-- Action CTA Button with Explicit Linear Gradient -->
                @if($isCurrent)
                    <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3 rounded-xl text-xs cursor-not-allowed">
                        Current Active Plan
                    </button>
                @elseif($canUpgrade)
                    <a href="{{ route('packages.checkout', $pkg->id) }}"
                       style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                       class="block text-center text-white font-bold py-3 px-4 rounded-xl text-xs shadow-md hover:opacity-95 transition tracking-wide">
                        Upgrade for Rs {{ number_format($priceToShow, 0) }} &rarr;
                    </a>
                @elseif(!$activePackage)
                    <a href="{{ route('packages.checkout', $pkg->id) }}"
                       style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 50%, #9b51e0 100%);"
                       class="block text-center text-white font-bold py-3 px-4 rounded-xl text-xs shadow-md hover:opacity-95 transition tracking-wide">
                        Activate Plan &rarr;
                    </a>
                @else
                    <button disabled class="w-full bg-slate-50 text-slate-300 font-medium py-3 rounded-xl text-xs cursor-not-allowed">
                        Lower Plan
                    </button>
                @endif
            </div>
        @endforeach
    </div>

</div>
@endsection
