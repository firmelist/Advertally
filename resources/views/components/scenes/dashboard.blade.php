{{-- Dashboards: one live view from traffic to revenue — tiles, a trend line drawing itself and the channel mix. --}}
<div class="flex h-full flex-col gap-2.5 text-[11px]">
    <div class="grid grid-cols-4 gap-2">
        @foreach ($d['kpis'] as $i => $kpi)
            <div class="rounded-lg border border-white/10 bg-white/[0.05] p-2">
                <p class="truncate text-[9px] text-navy-300">{{ $kpi }}</p>
                <div class="mt-1.5 h-1.5 rounded bg-white/10"><div class="scn-grow-x h-1.5 rounded {{ $loop->last ? 'bg-growth-600' : 'bg-brand-500' }}" style="width: {{ [70, 55, 62, 80][$i % 4] }}%; --t:8s; --d:{{ $i * .2 }}s"></div></div>
            </div>
        @endforeach
    </div>
    <div class="grid flex-1 grid-cols-[1.6fr_1fr] gap-2.5">
        <div class="relative rounded-xl border border-white/10 bg-white/[0.04] p-2.5">
            <p class="text-[10px] font-bold text-navy-200">Traffic → leads → revenue</p>
            <svg class="absolute inset-x-2.5 bottom-2.5 h-[72%] w-[calc(100%-1.25rem)]" viewBox="0 0 100 50" preserveAspectRatio="none" fill="none">
                @foreach ([12, 25, 37] as $y)<line x1="0" x2="100" y1="{{ $y }}" y2="{{ $y }}" stroke="rgba(255,255,255,.06)"/>@endforeach
                <path d="M0 42 C 12 40, 18 34, 28 35 S 46 26, 56 24 S 76 14, 100 8" stroke="#3B82F6" stroke-width="1.6" class="scn-draw" style="--len:130; --t:8s" vector-effect="non-scaling-stroke"/>
                <path d="M0 46 C 14 45, 22 42, 32 41 S 52 36, 62 33 S 82 26, 100 22" stroke="#16A34A" stroke-width="1.6" class="scn-draw" style="--len:130; --t:8s; --d:.6s" vector-effect="non-scaling-stroke"/>
            </svg>
        </div>
        <div class="flex flex-col items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] p-2">
            <svg class="scn-spin size-20" style="--t:16s" viewBox="0 0 36 36">
                @foreach ([[0, 35, '#2563EB'], [35, 25, '#7C3AED'], [60, 22, '#06B6D4'], [82, 18, '#16A34A']] as [$off, $len, $c])
                    <circle cx="18" cy="18" r="14" fill="none" stroke="{{ $c }}" stroke-width="5" pathLength="100" stroke-dasharray="{{ $len - 1 }} {{ 101 - $len }}" stroke-dashoffset="-{{ $off }}"/>
                @endforeach
            </svg>
            <p class="mt-1.5 text-[10px] font-semibold text-navy-100">Channel mix</p>
        </div>
    </div>
    <p class="text-center text-[9px] text-navy-300">Auto-refreshed from GA4, ad platforms and CRM · sample layout</p>
</div>
