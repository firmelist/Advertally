{{-- Local Search: your pin drops into the local results map. --}}
<div class="relative h-full overflow-hidden rounded-2xl bg-[#0d2240]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path d="M-5 70 Q30 55 55 62 T105 40" stroke="rgba(255,255,255,.14)" stroke-width="3"/>
        <path d="M20 -5 L35 105" stroke="rgba(255,255,255,.1)" stroke-width="2"/>
        <path d="M70 -5 Q62 40 80 105" stroke="rgba(255,255,255,.1)" stroke-width="2"/>
        <path d="M-5 25 L105 30" stroke="rgba(255,255,255,.07)" stroke-width="1.5"/>
        <rect x="40" y="8" width="16" height="12" rx="2" fill="rgba(22,163,74,.12)"/>
        <rect x="8" y="80" width="20" height="14" rx="2" fill="rgba(6,182,212,.1)"/>
    </svg>
    @foreach ([[22, 40], [76, 22], [84, 66]] as [$x, $y])
        <x-glyph name="map-pin" class="absolute size-6 -translate-x-1/2 -translate-y-full text-navy-300" style="left: {{ $x }}%; top: {{ $y }}%" />
    @endforeach
    <div class="absolute -translate-x-1/2 -translate-y-full" style="left: 50%; top: 46%">
        <span class="scn-pulse absolute -bottom-2 left-1/2 size-6 -translate-x-1/2 rounded-full bg-brand-400/50" style="--t:2s"></span>
        <div class="scn-drop" style="--t:8s">
            <svg class="size-11 drop-shadow-[0_6px_14px_rgb(37_99_235/.6)]" viewBox="0 0 24 24"><path d="M12 22s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 15.8 12 22 12 22z" fill="#2563EB" stroke="#fff" stroke-width="1.2"/><circle cx="12" cy="10" r="2.6" fill="#fff"/></svg>
        </div>
    </div>
    <div class="scn-seq absolute inset-x-3 bottom-3 rounded-xl border border-white/10 bg-navy-900/95 p-3 text-[11px] backdrop-blur" style="--t:8s; --d:2.2s">
        <div class="flex items-center justify-between">
            <span class="font-bold text-white">{{ $d['brand'] }}</span>
            <span class="font-semibold text-growth-100">Open now</span>
        </div>
        <div class="mt-1 flex items-center gap-0.5">
            @for ($s = 0; $s < 5; $s++)
                <svg class="scn-pop size-3.5" style="--t:8s; --d:{{ 2.6 + $s * .18 }}s" viewBox="0 0 24 24" fill="#FBBF24"><path d="M12 2l3 6.6 7 .7-5.3 4.7 1.6 7L12 17.3 5.7 21l1.6-7L2 9.3l7-.7z"/></svg>
            @endfor
            <span class="ml-1.5 text-navy-200">{{ $d['category'] }}</span>
        </div>
        <div class="mt-2 flex gap-1.5">
            @foreach (['Directions', 'Call', 'Website'] as $c)
                <span class="rounded-full bg-white/10 px-2 py-0.5 font-semibold text-brand-200">{{ $c }}</span>
            @endforeach
        </div>
    </div>
</div>
