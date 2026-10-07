{{-- Intelligence (engine): scattered data streams converge into one trustworthy view of growth. --}}
@php $sources = [['GA4', 'chart', 12], ['Search Console', 'search', 34], ['Ad platforms', 'megaphone', 56], ['CRM', 'database', 78]]; @endphp
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        @foreach ($sources as $i => [$label, $icon, $y])
            <path id="cv-{{ $i }}" d="M30 {{ $y }} C 46 {{ $y }}, 46 48, 60 48" stroke="rgba(255,255,255,.12)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
            @foreach ([0, 1.1] as $k)
                <circle r="1.1" fill="{{ ['#22D3EE', '#60A5FA', '#A78BFA', '#16A34A'][$i] }}"><animateMotion dur="2.2s" begin="-{{ $i * .3 + $k }}s" repeatCount="indefinite"><mpath href="#cv-{{ $i }}"/></animateMotion></circle>
            @endforeach
        @endforeach
    </svg>
    @foreach ($sources as [$label, $icon, $y])
        <div class="absolute left-0 flex w-[30%] -translate-y-1/2 items-center gap-1.5 rounded-lg border border-white/10 bg-navy-900/90 px-2 py-1.5" style="top: {{ $y }}%">
            <x-glyph :name="$icon" class="size-3.5 text-signal-400" /><span class="truncate font-semibold text-white">{{ $label }}</span>
        </div>
    @endforeach
    <div class="absolute top-[48%] right-0 w-[40%] -translate-y-1/2 rounded-xl border border-brand-400/40 bg-navy-800 p-2.5 shadow-[0_0_34px_rgb(37_99_235/.4)]">
        <p class="font-bold text-white">One growth view</p>
        <ul class="mt-1.5 space-y-1">
            @foreach (['Traffic', 'Leads', 'Qualified', 'Pipeline', 'Revenue'] as $r => $row)
                <li class="flex items-center gap-1.5">
                    <span class="w-14 text-[10px] text-navy-200">{{ $row }}</span>
                    <span class="h-1.5 flex-1 rounded bg-white/10"><span class="scn-grow-x block h-1.5 rounded {{ $loop->last ? 'bg-growth-600' : 'bg-brand-500' }}" style="width: {{ 95 - $r * 15 }}%; --t:7s; --d:{{ $r * .25 }}s"></span></span>
                </li>
            @endforeach
        </ul>
    </div>
    <p class="absolute right-0 bottom-0 text-[9px] text-navy-300">Decisions on evidence, not habit</p>
</div>
