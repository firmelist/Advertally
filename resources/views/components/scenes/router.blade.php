{{-- Lead automation: every new lead is enriched, scored and routed to the right owner within minutes. --}}
@php $owners = $d['owners']; @endphp
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path id="rt-in" d="M4 50 H38" stroke="rgba(255,255,255,.18)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        @foreach ($owners as $i => $o)
            @php $y = 16 + $i * 34; @endphp
            <path id="rt-{{ $i }}" d="M58 50 C 68 50, 68 {{ $y }}, 78 {{ $y }}" stroke="rgba(255,255,255,.15)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
            <circle r="1.6" fill="{{ ['#22D3EE', '#A78BFA', '#16A34A'][$i] }}"><animateMotion dur="3s" begin="-{{ $i }}s" repeatCount="indefinite"><mpath href="#rt-{{ $i }}"/></animateMotion></circle>
        @endforeach
        @foreach ([0, 1, 2] as $k)
            <circle r="1.6" fill="#fff"><animateMotion dur="3s" begin="-{{ $k }}s" repeatCount="indefinite"><mpath href="#rt-in"/></animateMotion></circle>
        @endforeach
    </svg>
    <div class="absolute top-1/2 left-0 -translate-y-1/2 space-y-1.5">
        @foreach (['Form', 'Chat', 'Ads'] as $s)<span class="block rounded-md bg-white/10 px-2 py-0.5 text-[10px] font-semibold text-navy-100">{{ $s }}</span>@endforeach
    </div>
    <div class="absolute top-1/2 left-[48%] w-[22%] min-w-[6.5rem] -translate-x-1/2 -translate-y-1/2 rounded-xl border border-brand-400/50 bg-navy-800 p-2.5 shadow-[0_0_30px_rgb(37_99_235/.35)]">
        <p class="flex items-center gap-1 font-bold text-white"><x-glyph name="zap" class="size-3.5 text-amber-200" /> Router</p>
        <ul class="mt-1.5 space-y-1 text-[10px] text-navy-100">
            @foreach (['Enrich company', 'Score fit', 'Match rules'] as $r => $rule)
                <li class="scn-seq flex items-center gap-1" style="--t:6s; --d:{{ $r * .6 }}s"><x-glyph name="check" class="size-3 text-growth-100" stroke="3" />{{ $rule }}</li>
            @endforeach
        </ul>
    </div>
    @foreach ($owners as $i => [$name, $rule])
        <div class="absolute right-0 flex w-[24%] min-w-[5.5rem] -translate-y-1/2 items-center gap-1.5 rounded-xl border border-white/10 bg-white/[0.06] p-1.5" style="top: {{ 16 + $i * 34 }}%">
            <span class="grid size-6 shrink-0 place-items-center rounded-full text-[9px] font-bold text-white {{ ['bg-signal-500', 'bg-ai-500', 'bg-growth-600'][$i] }}">{{ mb_substr($name, 0, 1) }}</span>
            <span class="min-w-0"><span class="block truncate font-bold text-white">{{ $name }}</span><span class="block truncate text-[9px] text-navy-300">{{ $rule }}</span></span>
            <span class="scn-pulse absolute -top-1 -right-1 size-2.5 rounded-full bg-amber-300" style="--t:3s; --d:{{ $i }}s"></span>
        </div>
    @endforeach
</div>
