{{-- Revenue intelligence: pipeline by stage, a forecast cone ahead, and early warnings where deals stall. --}}
<div class="flex h-full flex-col gap-3 text-[11px]">
    <div class="relative flex-1 rounded-xl border border-white/10 bg-white/[0.04] p-3">
        <p class="flex justify-between text-[10px] font-bold text-navy-200"><span>Pipeline &amp; forecast</span><span class="text-navy-300">Illustrative</span></p>
        <svg class="absolute inset-x-3 bottom-3 h-[70%] w-[calc(100%-1.5rem)]" viewBox="0 0 100 60" preserveAspectRatio="none" fill="none">
            @foreach ([0, 1, 2, 3, 4, 5] as $q)
                @php $h = [18, 24, 22, 30, 34, 40][$q]; @endphp
                <rect x="{{ 4 + $q * 10 }}" y="{{ 60 - $h }}" width="6" height="{{ $h }}" rx="1" fill="rgba(37,99,235,.55)" class="scn-grow-y" style="--t:9s; --d:{{ $q * .25 }}s; transform-box: fill-box"/>
                <rect x="{{ 4 + $q * 10 }}" y="{{ 60 - $h }}" width="6" height="{{ $h * .4 }}" rx="1" fill="rgba(124,58,237,.75)" class="scn-grow-y" style="--t:9s; --d:{{ $q * .25 + .2 }}s; transform-box: fill-box"/>
            @endforeach
            <path d="M62 22 L96 6 L96 26 Z" fill="rgba(22,163,74,.18)" class="scn-seq" style="--t:9s; --d:2.4s"/>
            <path d="M62 22 L96 15" stroke="#16A34A" stroke-width="1.2" stroke-dasharray="2 2" class="scn-draw" style="--len:40; --t:9s; --d:2.4s" vector-effect="non-scaling-stroke"/>
            <line x1="61" x2="61" y1="0" y2="60" stroke="rgba(255,255,255,.25)" stroke-dasharray="1 1.5" vector-effect="non-scaling-stroke"/>
        </svg>
        <span class="absolute top-10 right-4 rounded bg-growth-600/25 px-1.5 text-[9px] font-semibold text-growth-100">forecast range</span>
    </div>
    <div class="grid grid-cols-3 gap-2">
        @foreach ([['Pipeline coverage', 'chart', 'text-brand-200', 3], ['Win rate trend', 'trending-up', 'text-growth-100', 3.4], ['Deals stalling', 'alert', 'text-amber-200', 3.8]] as [$label, $icon, $tone, $delay])
            <div class="scn-pop rounded-xl border border-white/10 bg-white/[0.05] p-2" style="--t:9s; --d:{{ $delay }}s">
                <x-glyph :name="$icon" class="size-4 {{ $tone }}" />
                <p class="mt-1 leading-tight font-semibold text-white">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</div>
