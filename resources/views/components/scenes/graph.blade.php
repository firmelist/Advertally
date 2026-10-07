{{-- Entity SEO: your company becomes a well-connected entity machines can trust. --}}
@php
    $nodes = [['Founder', 'user-search', 50, 9], ['Services', 'layers', 88, 30], ['LinkedIn', 'linkedin', 86, 74], ['Reviews', 'award', 50, 91], ['Location', 'map-pin', 14, 74], ['Schema', 'code', 12, 30]];
@endphp
<div class="relative h-full">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        @foreach ($nodes as $i => [$label, $icon, $x, $y])
            <line x1="50" y1="50" x2="{{ $x }}" y2="{{ $y }}" stroke="url(#graph-g)" stroke-width=".6" class="scn-draw" style="--len:60; --t:8s; --d:{{ .4 * $i }}s" vector-effect="non-scaling-stroke"/>
            @php [$nx, $ny] = [$nodes[($i + 1) % 6][2], $nodes[($i + 1) % 6][3]]; @endphp
            <line x1="{{ $x }}" y1="{{ $y }}" x2="{{ $nx }}" y2="{{ $ny }}" stroke="rgba(255,255,255,.12)" stroke-width=".4" stroke-dasharray="1 2" vector-effect="non-scaling-stroke"/>
        @endforeach
        <defs><linearGradient id="graph-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#22D3EE"/><stop offset="1" stop-color="#A78BFA"/></linearGradient></defs>
    </svg>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <span class="scn-pulse absolute inset-0 rounded-2xl bg-ai-500/40"></span>
        <div class="relative grid place-items-center rounded-2xl border border-white/20 bg-gradient-to-br from-navy-700 to-navy-950 px-4 py-3 text-center shadow-[0_0_40px_rgb(124_58_237/.5)]">
            <x-glyph name="building" class="mx-auto size-6 text-brand-300" />
            <span class="mt-1 text-[12px] font-bold text-white">{{ $d['brand'] }}</span>
            <span class="scn-pop mt-1 inline-flex items-center gap-1 rounded-full bg-growth-600 px-2 py-0.5 text-[9px] font-bold text-white" style="--t:8s; --d:2.8s"><x-glyph name="check" class="size-2.5" stroke="3" /> Entity verified</span>
        </div>
    </div>
    @foreach ($nodes as $i => [$label, $icon, $x, $y])
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="scn-pop flex items-center gap-1.5 rounded-full border border-white/15 bg-navy-900/90 px-2.5 py-1 text-[11px] font-semibold whitespace-nowrap text-white" style="--t:8s; --d:{{ .4 * $i + .5 }}s">
                <x-glyph :name="$icon" class="size-3.5 text-signal-400" /> {{ $label }}
            </div>
        </div>
    @endforeach
</div>
