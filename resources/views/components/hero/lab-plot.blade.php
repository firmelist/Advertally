{{-- AI Search Lab hero: a research instrument — data points plot themselves, a trend line fits, an annotation appears. --}}
@php
    mt_srand(7);
    $points = collect(range(0, 26))->map(fn ($i) => [8 + $i * 3.3, max(10, min(88, 82 - $i * 2.3 + mt_rand(-12, 12)))]);
@endphp
<div {{ $attributes->merge(['class' => 'relative w-full']) }} aria-hidden="true">
    <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-5 backdrop-blur">
        <div class="flex items-center justify-between font-mono text-[11px] text-navy-200">
            <span>fig. 1 — AI answer inclusion vs. entity clarity</span>
            <span class="rounded bg-white/10 px-1.5 text-[10px]">illustrative</span>
        </div>
        <svg class="mt-4 h-56 w-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
            @foreach ([25, 50, 75] as $g)
                <line x1="0" x2="100" y1="{{ $g }}" y2="{{ $g }}" stroke="rgba(255,255,255,.07)" vector-effect="non-scaling-stroke"/>
                <line y1="0" y2="100" x1="{{ $g }}" x2="{{ $g }}" stroke="rgba(255,255,255,.05)" vector-effect="non-scaling-stroke"/>
            @endforeach
            <line x1="4" y1="86" x2="96" y2="18" stroke="#A78BFA" stroke-width="1.6" stroke-dasharray="3 2" class="scn-draw" style="--len:120; --t:10s; --d:3.4s" vector-effect="non-scaling-stroke"/>
        </svg>
        <div class="pointer-events-none absolute inset-x-5 top-[3.6rem] h-56">
            @foreach ($points as $i => [$x, $y])
                <span class="scn-pop absolute size-2 -translate-x-1/2 -translate-y-1/2 rounded-full {{ $i % 3 ? 'bg-signal-400' : 'bg-ai-400' }} shadow-[0_0_8px_currentColor]" style="left: {{ $x }}%; top: {{ $y }}%; --t:10s; --d:{{ $i * .1 }}s"></span>
            @endforeach
            <span class="scn-pop absolute top-[12%] right-[4%] rounded-lg border border-ai-400/40 bg-navy-900/90 px-2.5 py-1.5 font-mono text-[10px] text-ai-100" style="--t:10s; --d:4.6s">trend: clearer entities → more inclusion</span>
        </div>
        <div class="mt-3 grid grid-cols-3 gap-2 font-mono text-[10px] text-navy-200">
            <span class="rounded-lg bg-white/[0.05] px-2 py-1.5">method: published</span>
            <span class="rounded-lg bg-white/[0.05] px-2 py-1.5">sources: cited</span>
            <span class="rounded-lg bg-white/[0.05] px-2 py-1.5">limits: stated</span>
        </div>
    </div>
</div>
