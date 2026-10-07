{{-- Demand (engine): budget flows through channels and compounds into pipeline and revenue. --}}
@php $channels = [['Google', 'search', 18], ['LinkedIn', 'linkedin', 40], ['YouTube', 'youtube', 62], ['Meta', 'users', 84]]; @endphp
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        @foreach ($channels as $i => [$label, $icon, $y])
            <path id="rf-in-{{ $i }}" d="M14 50 C 26 50, 26 {{ $y }}, 38 {{ $y }}" stroke="rgba(255,255,255,.12)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
            <path id="rf-out-{{ $i }}" d="M50 {{ $y }} C 64 {{ $y }}, 64 50, 78 50" stroke="rgba(255,255,255,.12)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
        @endforeach
        {{-- coins travel the paths (SMIL keeps them on the curve at any size) --}}
        @foreach ($channels as $i => [$label, $icon, $y])
            @foreach ([0, 1.6] as $k)
                <circle r="1.1" fill="#FCD34D"><animateMotion dur="3.2s" begin="-{{ $i * .4 + $k }}s" repeatCount="indefinite"><mpath href="#rf-in-{{ $i }}"/></animateMotion></circle>
                <circle r="1.1" fill="#16A34A"><animateMotion dur="3.2s" begin="-{{ $i * .4 + $k + 1.6 }}s" repeatCount="indefinite"><mpath href="#rf-out-{{ $i }}"/></animateMotion></circle>
            @endforeach
        @endforeach
    </svg>
    <div class="absolute top-1/2 left-0 w-[14%] -translate-y-1/2 rounded-xl border border-amber-300/40 bg-amber-300/10 py-3 text-center">
        <x-glyph name="zap" class="mx-auto size-5 text-amber-200" /><p class="mt-1 text-[10px] font-bold text-amber-100">Budget</p>
    </div>
    @foreach ($channels as [$label, $icon, $y])
        <div class="absolute left-[38%] flex w-[12%] min-w-[4.6rem] -translate-y-1/2 items-center gap-1.5 rounded-lg border border-white/10 bg-navy-900/90 px-2 py-1.5" style="top: {{ $y }}%">
            <x-glyph :name="$icon" class="size-3.5 text-brand-300" /><span class="truncate font-semibold text-white">{{ $label }}</span>
        </div>
    @endforeach
    <div class="absolute top-1/2 right-0 w-[22%] -translate-y-1/2 rounded-xl border border-growth-600/50 bg-growth-600/15 p-2.5 text-center">
        <p class="text-[10px] text-navy-200">Pipeline</p>
        <div class="mx-auto mt-1.5 flex h-12 w-full items-end justify-center gap-1">
            @foreach ([35, 55, 75, 100] as $b => $h)<span class="scn-grow-y w-2.5 rounded-sm bg-growth-600" style="height: {{ $h }}%; --t:6s; --d:{{ 1 + $b * .3 }}s"></span>@endforeach
        </div>
        <p class="mt-1.5 text-[11px] font-bold text-growth-100">Revenue</p>
    </div>
</div>
