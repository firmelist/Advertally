{{-- Content: an expert article being written against an outline, then published. --}}
<div class="grid h-full grid-cols-[7rem_1fr] gap-3 text-[11px] sm:grid-cols-[8.5rem_1fr]">
    <div class="rounded-xl border border-white/10 bg-white/[0.04] p-2.5">
        <p class="text-[9px] font-bold tracking-wider text-navy-300 uppercase">Outline</p>
        <ul class="mt-2 space-y-2">
            @foreach ($d['outline'] as $i => $h)
                <li class="flex items-start gap-1.5 text-navy-100">
                    <span class="relative mt-0.5 size-3 shrink-0 rounded-full border border-white/25">
                        <span class="scn-pop absolute inset-0 grid place-items-center rounded-full bg-growth-600" style="--t:9s; --d:{{ 1.2 + $i * 1.3 }}s"><x-glyph name="check" class="size-2" stroke="3" /></span>
                    </span>
                    <span class="leading-tight">{{ $h }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-white/[0.06] p-3.5">
        <p class="text-[9px] font-bold tracking-wider text-signal-400 uppercase">{{ $d['type'] }}</p>
        <p class="mt-1 text-[14px] leading-snug font-extrabold text-white"><span class="scn-type" style="--t:9s">{{ $d['title'] }}</span></p>
        <div class="mt-3 space-y-1.5">
            @foreach ([[100, 1.6], [92, 1.9], [96, 2.2], [70, 2.5]] as [$w, $delay])
                <div class="scn-grow-x h-1.5 rounded bg-white/25" style="width: {{ $w }}%; --t:9s; --d:{{ $delay }}s"></div>
            @endforeach
        </div>
        <div class="scn-seq mt-3 rounded-lg border-l-2 border-ai-400 bg-ai-500/10 px-2.5 py-2 text-[10px] text-ai-100 italic" style="--t:9s; --d:3.4s">“{{ $d['quote'] }}” — from the expert interview</div>
        <div class="mt-3 space-y-1.5">
            @foreach ([[94, 4.4], [88, 4.7], [55, 5]] as [$w, $delay])
                <div class="scn-grow-x h-1.5 rounded bg-white/25" style="width: {{ $w }}%; --t:9s; --d:{{ $delay }}s"></div>
            @endforeach
        </div>
        <span class="scn-pop absolute right-3 bottom-3 -rotate-6 rounded-lg border-2 border-growth-600 px-2.5 py-1 text-[11px] font-extrabold tracking-wider text-growth-100 uppercase" style="--t:9s; --d:6s">Published</span>
    </div>
</div>
