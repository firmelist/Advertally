{{-- CRM integration: website, ads and forms stay in sync with the CRM — both directions, nothing lost. --}}
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path id="sy-a" d="M30 38 C 45 30, 55 30, 70 38" stroke="rgba(255,255,255,.15)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        <path id="sy-b" d="M70 62 C 55 70, 45 70, 30 62" stroke="rgba(255,255,255,.15)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
        @foreach ([0, 1, 2] as $k)
            <g><animateMotion dur="3s" begin="-{{ $k }}s" repeatCount="indefinite"><mpath href="#sy-a"/></animateMotion><rect x="-3" y="-1.6" width="6" height="3.2" rx=".8" fill="#22D3EE"/></g>
            <g><animateMotion dur="3s" begin="-{{ $k + .5 }}s" repeatCount="indefinite"><mpath href="#sy-b"/></animateMotion><rect x="-3" y="-1.6" width="6" height="3.2" rx=".8" fill="#A78BFA"/></g>
        @endforeach
    </svg>
    <div class="absolute top-1/2 left-0 w-[30%] -translate-y-1/2 rounded-xl border border-white/10 bg-navy-800 p-2.5">
        <p class="font-bold text-white">{{ $d['left'] }}</p>
        <ul class="mt-1.5 space-y-1 text-[10px] text-navy-200">@foreach ($d['left_items'] as $it)<li>• {{ $it }}</li>@endforeach</ul>
    </div>
    <div class="absolute top-1/2 right-0 w-[30%] -translate-y-1/2 rounded-xl border border-brand-400/50 bg-navy-800 p-2.5 shadow-[0_0_30px_rgb(37_99_235/.35)]">
        <p class="flex items-center gap-1 font-bold text-white"><x-glyph name="database" class="size-3.5 text-brand-300" /> {{ $d['right'] }}</p>
        <div class="mt-1.5 space-y-1 text-[10px]">
            @foreach ($d['fields'] as $f => $field)
                <p class="flex justify-between gap-1 text-navy-200"><span class="truncate">{{ $field }}</span><span class="scn-pop text-growth-100" style="--t:6s; --d:{{ .8 + $f * .5 }}s">✓</span></p>
            @endforeach
        </div>
    </div>
    <span class="absolute top-[24%] left-1/2 -translate-x-1/2 text-[9px] font-semibold text-signal-200">leads &amp; events →</span>
    <span class="absolute bottom-[24%] left-1/2 -translate-x-1/2 text-[9px] font-semibold text-ai-200">← status &amp; revenue</span>
    <p class="absolute inset-x-0 bottom-0 text-center text-[9px] text-navy-300">Deduplicated · source kept · monitored with alerts</p>
</div>
