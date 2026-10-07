{{-- Google Ads: a high-intent search → your ad → a click → a qualified lead in the CRM. --}}
<div class="relative flex h-full flex-col gap-3 text-[12px]">
    <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/[0.07] px-4 py-2.5 text-white">
        <x-glyph name="search" class="size-4 text-navy-200" />
        <span class="truncate"><span class="scn-type" style="--t:8s">{{ $d['query'] }}</span><span class="scn-blink text-signal-400">|</span></span>
    </div>
    <div class="scn-seq relative rounded-xl border border-amber-300/40 bg-navy-800 p-3" style="--t:8s; --d:1.6s">
        <span class="rounded bg-amber-300/20 px-1.5 py-0.5 text-[9px] font-bold tracking-wide text-amber-200 uppercase">Sponsored</span>
        <p class="mt-1.5 text-[13px] font-bold text-brand-200">{{ $d['headline'] }}</p>
        <p class="mt-1 text-[11px] text-navy-200">{{ $d['description'] }}</p>
        <div class="mt-2 flex gap-1.5">
            @foreach ($d['sitelinks'] as $s)<span class="rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-semibold text-brand-200">{{ $s }}</span>@endforeach
        </div>
        {{-- the click --}}
        <span class="scn-cursor-b pointer-events-none absolute top-0 left-0 size-full">
            <svg class="size-5 drop-shadow" viewBox="0 0 24 24"><path d="M4 3l7 17 2.5-7.5L21 10z" fill="#fff" stroke="#0B1F3A" stroke-width="1.2"/></svg>
        </span>
    </div>
    <div class="mt-auto grid grid-cols-3 items-center gap-2">
        @foreach ([['Click', 'pointer', 'bg-brand-600/30 text-brand-100', 3.6], ['Landing page', 'layout', 'bg-ai-500/25 text-ai-100', 4.3], ['Qualified lead', 'check-circle', 'bg-growth-600/30 text-growth-100', 5]] as [$label, $icon, $tone, $delay])
            <div class="scn-pop flex flex-col items-center gap-1.5 rounded-xl border border-white/10 p-2.5 text-center {{ $tone }}" style="--t:8s; --d:{{ $delay }}s">
                <x-glyph :name="$icon" class="size-5" />
                <span class="text-[10px] font-semibold">{{ $label }}</span>
            </div>
        @endforeach
    </div>
    <p class="scn-seq text-center text-[10px] text-navy-300" style="--t:8s; --d:5.4s">Conversion sent back to Google Ads → bidding learns from qualified outcomes</p>
</div>
