{{-- SEO: your result climbs the search results page. --}}
<div class="flex h-full flex-col">
    <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/[0.07] px-4 py-2.5 text-[13px] text-white">
        <span class="flex gap-0.5"><span class="size-2 rounded-full bg-brand-400"></span><span class="size-2 rounded-full bg-red-400"></span><span class="size-2 rounded-full bg-amber-300"></span><span class="size-2 rounded-full bg-growth-600"></span></span>
        <span class="truncate"><span class="scn-type" style="--t:7s">{{ $d['query'] }}</span><span class="scn-blink text-signal-400">|</span></span>
        <x-glyph name="search" class="ml-auto size-4 text-navy-200" />
    </div>
    <div class="relative mt-3 flex-1">
        @foreach ([1, 2, 3] as $row)
            <div class="absolute inset-x-0 h-[3.4rem] rounded-xl border border-white/5 bg-white/[0.03] p-2.5" style="top: {{ $row * 3.9 }}rem">
                <div class="h-1.5 w-24 rounded bg-white/15"></div>
                <div class="mt-1.5 h-2.5 rounded bg-brand-300/30" style="width: {{ [70, 58, 64][$row - 1] }}%"></div>
                <div class="mt-1.5 h-1.5 w-[85%] rounded bg-white/10"></div>
            </div>
        @endforeach
        <div class="scn-climb absolute inset-x-0 top-0 z-10 h-[3.4rem] rounded-xl border border-brand-400/60 bg-navy-800 p-2.5 shadow-[0_0_30px_rgb(37_99_235/.45)]" style="--t:7s; --from:300%">
            <div class="flex items-center gap-1.5 text-[10px] text-navy-200"><span class="size-3 rounded-full bg-gradient-to-br from-signal-400 to-ai-500"></span>{{ $d['domain'] }} › {{ $d['path'] }}</div>
            <div class="mt-1 truncate text-[13px] font-bold text-brand-200">{{ $d['title'] }}</div>
            <div class="mt-1 h-1.5 w-[80%] rounded bg-white/15"></div>
            <span class="scn-pop absolute -top-2 -right-2 rounded-full bg-growth-600 px-2 py-0.5 text-[10px] font-bold text-white" style="--t:7s; --d:3.1s">Top result</span>
        </div>
    </div>
</div>
