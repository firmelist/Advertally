{{-- Marketing automation: a nurture sequence that branches on what each buyer actually does. --}}
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path id="sq-main" d="M8 50 H40" stroke="rgba(255,255,255,.18)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        <path id="sq-up" d="M40 50 C 52 50, 52 22, 64 22 H92" stroke="rgba(34,211,238,.35)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        <path id="sq-down" d="M40 50 C 52 50, 52 78, 64 78 H92" stroke="rgba(167,139,250,.35)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        @foreach ([0, 1.4, 2.8] as $k)
            <g><animateMotion dur="4.2s" begin="-{{ $k }}s" repeatCount="indefinite"><mpath href="#sq-main"/></animateMotion><rect x="-2" y="-1.4" width="4" height="2.8" rx=".4" fill="#fff"/></g>
        @endforeach
        <g><animateMotion dur="4.2s" begin="-.7s" repeatCount="indefinite"><mpath href="#sq-up"/></animateMotion><rect x="-2" y="-1.4" width="4" height="2.8" rx=".4" fill="#22D3EE"/></g>
        <g><animateMotion dur="4.2s" begin="-2.8s" repeatCount="indefinite"><mpath href="#sq-down"/></animateMotion><rect x="-2" y="-1.4" width="4" height="2.8" rx=".4" fill="#A78BFA"/></g>
    </svg>
    <div class="absolute top-1/2 left-0 w-[24%] -translate-y-1/2 rounded-xl border border-white/15 bg-navy-800 p-2 text-center">
        <x-glyph name="mail" class="mx-auto size-4 text-brand-300" /><p class="mt-1 font-bold text-white">{{ $d['trigger'] }}</p>
    </div>
    <div class="absolute top-1/2 left-[40%] -translate-x-1/2 -translate-y-1/2 rounded-full border border-amber-300/50 bg-amber-300/15 px-2.5 py-1 text-[10px] font-bold whitespace-nowrap text-amber-100">Opened &amp; clicked?</div>
    @foreach ([[22, 'Yes', 'text-signal-200 border-signal-400/40 bg-signal-500/15', $d['yes']], [78, 'Not yet', 'text-ai-200 border-ai-400/40 bg-ai-500/15', $d['no']]] as [$y, $label, $tone, $steps])
        <div class="absolute right-0 w-[40%] -translate-y-1/2 rounded-xl border p-2 {{ $tone }}" style="top: {{ $y }}%">
            <p class="font-bold">{{ $label }}</p>
            <ol class="mt-1 space-y-1 text-[10px] text-navy-100">
                @foreach ($steps as $s => $step)
                    <li class="scn-seq flex items-center gap-1.5" style="--t:8s; --d:{{ 1 + $s * .7 + ($y > 50 ? .4 : 0) }}s"><span class="size-1.5 rounded-full bg-current"></span>{{ $step }}</li>
                @endforeach
            </ol>
        </div>
    @endforeach
</div>
