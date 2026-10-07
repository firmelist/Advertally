{{-- Enterprise Search: a crawler sweeps a large site; issues turn into fixes, template by template. --}}
@php $sections = ['/solutions', '/industries', '/insights', '/locations']; @endphp
<div class="relative flex h-full flex-col text-[11px]">
    <div class="flex items-center justify-center">
        <span class="rounded-lg border border-brand-400/50 bg-brand-600/30 px-3 py-1 font-mono font-bold text-white">yourcompany.com/</span>
    </div>
    <svg class="h-6 w-full" viewBox="0 0 100 10" preserveAspectRatio="none" fill="none">
        @foreach ([12.5, 37.5, 62.5, 87.5] as $x)<path d="M50 0 V4 H{{ $x }} V10" stroke="rgba(255,255,255,.2)" stroke-width=".4" vector-effect="non-scaling-stroke"/>@endforeach
    </svg>
    <div class="grid flex-1 grid-cols-4 gap-2">
        @foreach ($sections as $c => $section)
            <div class="flex flex-col items-center gap-1.5">
                <span class="w-full truncate rounded-md bg-white/10 px-1 py-1 text-center font-mono text-[10px] text-navy-100">{{ $section }}</span>
                <div class="grid w-full grid-cols-3 gap-1">
                    @for ($p = 0; $p < 15; $p++)
                        <span class="relative h-3 rounded-sm bg-amber-400/50">
                            <span class="scn-pop absolute inset-0 rounded-sm bg-growth-600" style="--t:8s; --d:{{ 0.9 + intdiv($p, 3) * .55 + $c * .08 }}s"></span>
                        </span>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
    <div class="scn-fall pointer-events-none absolute inset-x-0 top-14 h-8 bg-gradient-to-b from-transparent via-signal-400/25 to-transparent" style="--t:8s; --y:13rem; --end:0"></div>
    <div class="mt-3 flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.05] px-3 py-2 text-navy-100">
        <span class="flex items-center gap-1.5"><span class="size-2 rounded-sm bg-amber-400/70"></span> Issue</span>
        <span class="flex items-center gap-1.5"><span class="size-2 rounded-sm bg-growth-600"></span> Fixed at template level</span>
        <span class="hidden font-semibold text-white sm:inline">Governed releases</span>
    </div>
</div>
