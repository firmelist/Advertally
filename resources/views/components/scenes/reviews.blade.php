{{-- Reviews & reputation: genuine reviews arrive, stars fill, and every review gets a considered reply. --}}
@php $reviews = $d['reviews']; @endphp
<div class="relative h-full text-[11px]">
    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.05] px-3 py-2">
        <span class="font-bold text-white">{{ $d['brand'] }}</span>
        <span class="flex gap-1">
            @foreach ($d['platforms'] as $p)<span class="rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-semibold text-navy-100">{{ $p }}</span>@endforeach
        </span>
    </div>
    <div class="relative mt-3 h-[calc(100%-3rem)]">
        @foreach ($reviews as $i => $review)
            <div class="scn-seq absolute inset-x-0 rounded-xl border border-white/10 bg-navy-800/95 p-3" style="top: {{ $i * 34 }}%; --t:9s; --d:{{ $i * 1.6 }}s">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2"><span class="size-6 rounded-full bg-white/15"></span><span class="font-semibold text-white">{{ $review['who'] }}</span></span>
                    <span class="flex">
                        @for ($s = 0; $s < 5; $s++)
                            <svg class="scn-pop size-3.5" style="--t:9s; --d:{{ $i * 1.6 + .3 + $s * .12 }}s" viewBox="0 0 24 24" fill="#FBBF24"><path d="M12 2l3 6.6 7 .7-5.3 4.7 1.6 7L12 17.3 5.7 21l1.6-7L2 9.3l7-.7z"/></svg>
                        @endfor
                    </span>
                </div>
                <p class="mt-1.5 text-navy-100">“{{ $review['text'] }}”</p>
                <p class="scn-seq mt-1.5 border-l-2 border-brand-400 pl-2 text-[10px] text-brand-200" style="--t:9s; --d:{{ $i * 1.6 + 1 }}s">Reply from {{ $d['brand'] }} · thank you</p>
            </div>
        @endforeach
    </div>
</div>
