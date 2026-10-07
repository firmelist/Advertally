{{-- Meta Ads: a phone playing Stories — progress bars, your creative, and a tap-through. --}}
<div class="relative grid h-full place-items-center">
    <div class="relative h-full max-h-[21rem] w-[11.5rem] overflow-hidden rounded-[1.8rem] border-4 border-navy-700 bg-gradient-to-br from-ai-600 via-brand-600 to-signal-500 shadow-[0_20px_60px_rgb(124_58_237/.45)]">
        <div class="absolute inset-x-2 top-2 flex gap-1">
            @foreach ([0, 1, 2] as $s)
                <span class="h-0.5 flex-1 overflow-hidden rounded bg-white/30"><span class="scn-grow-x block h-full bg-white" style="--t:9s; --d:{{ $s * 3 }}s"></span></span>
            @endforeach
        </div>
        <div class="absolute top-5 left-3 flex items-center gap-1.5 text-[10px] font-semibold text-white">
            <span class="size-5 rounded-full border border-white/70 bg-navy-900"></span> {{ $d['brand'] }} <span class="text-white/70">· Sponsored</span>
        </div>
        @foreach ($d['frames'] as $i => $frame)
            <div class="scn-seq absolute inset-x-4 top-1/2 -translate-y-1/2 text-center" style="--t:9s; --d:{{ $i * 3 }}s; animation-name: scn-story">
                <p class="text-lg leading-tight font-extrabold text-white drop-shadow">{{ $frame }}</p>
            </div>
        @endforeach
        <div class="absolute inset-x-3 bottom-4">
            <div class="scn-glow rounded-full bg-white px-3 py-2 text-center text-[11px] font-bold text-navy-900">{{ $d['cta'] }}</div>
            <p class="mt-1.5 text-center text-[9px] text-white/80">↑ Swipe up</p>
        </div>
    </div>
    <div class="scn-pop absolute top-6 right-2 rounded-xl border border-white/10 bg-navy-900/95 px-3 py-2 text-[10px] text-navy-100 sm:right-6" style="--t:9s; --d:2s">
        <p class="font-bold text-white">Audience</p><p>{{ $d['audience'] }}</p>
    </div>
    <div class="scn-pop absolute bottom-8 left-2 rounded-xl border border-white/10 bg-navy-900/95 px-3 py-2 text-[10px] text-navy-100 sm:left-6" style="--t:9s; --d:5s">
        <p class="font-bold text-growth-100">Retargeting pool</p><p>Engaged viewers saved</p>
    </div>
</div>
<style>@keyframes scn-story { 0% { opacity: 0; transform: translateY(-40%) scale(.9); } 4% { opacity: 1; transform: translateY(-50%) scale(1); } 30% { opacity: 1; transform: translateY(-50%); } 34%, 100% { opacity: 0; transform: translateY(-60%); } }</style>
