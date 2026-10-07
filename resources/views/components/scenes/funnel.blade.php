{{-- Lead generation / funnels: many visitors in, unqualified filtered out, qualified leads drop into sales. --}}
@php $stages = $d['stages'] ?? ['Visitors', 'Leads', 'Qualified', 'Sales-ready']; @endphp
<div class="relative grid h-full grid-cols-[1fr_7.5rem] gap-3 text-[11px]">
    <div class="relative">
        <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
            <path d="M4 4 H96 L64 62 V96 H36 V62 Z" fill="url(#funnel-g)" stroke="rgba(255,255,255,.18)" stroke-width=".6" vector-effect="non-scaling-stroke"/>
            @foreach ([28, 50, 72] as $y)<line x1="0" x2="100" y1="{{ $y }}" y2="{{ $y }}" stroke="rgba(255,255,255,.06)" stroke-dasharray="2 2"/>@endforeach
            <defs><linearGradient id="funnel-g" x1="0" y1="0" x2="0" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#2563EB" stop-opacity=".25"/><stop offset="1" stop-color="#16A34A" stop-opacity=".35"/></linearGradient></defs>
        </svg>
        {{-- qualified particles fall through the neck --}}
        @foreach ([[47, 0], [53, .7], [50, 1.4], [46, 2.1], [54, 2.8], [50, 3.5]] as [$x, $delay])
            <span class="scn-fall absolute top-[4%] size-2.5 rounded-full bg-growth-600 shadow-[0_0_10px_rgb(22_163_74)]" style="left: {{ $x }}%; --t:4.2s; --d:{{ $delay }}s; --y:16.5rem"></span>
        @endforeach
        {{-- unqualified particles bounce off the walls --}}
        @foreach ([[12, .3], [86, 1], [20, 1.7], [80, 2.4], [28, 3.1], [72, 3.8]] as [$x, $delay])
            <span class="scn-fall absolute top-[4%] size-2 rounded-full bg-navy-300/70" style="left: {{ $x }}%; --t:2.6s; --d:{{ $delay }}s; --y:5.5rem; --end:0"></span>
        @endforeach
        <div class="absolute inset-x-[30%] bottom-0 rounded-lg bg-growth-600 py-1 text-center text-[10px] font-bold text-white">{{ end($stages) }}</div>
    </div>
    <div class="flex flex-col justify-between py-1">
        @foreach ($stages as $i => $stage)
            <div class="rounded-lg border border-white/10 bg-white/[0.05] px-2.5 py-2">
                <p class="text-[9px] text-navy-300">Stage {{ $i + 1 }}</p>
                <p class="font-bold {{ $loop->last ? 'text-growth-100' : 'text-white' }}">{{ $stage }}</p>
                <div class="mt-1 h-1 rounded bg-white/10"><div class="scn-grow-x h-1 rounded {{ $loop->last ? 'bg-growth-600' : 'bg-brand-500' }}" style="width: {{ 100 - $i * 22 }}%; --t:6s; --d:{{ $i * .4 }}s"></div></div>
            </div>
        @endforeach
    </div>
</div>
