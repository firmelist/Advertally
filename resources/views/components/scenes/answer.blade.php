{{-- AEO: a buyer question becomes a direct, structured answer — on screen and by voice. --}}
<div class="flex h-full flex-col gap-3 text-[12px]">
    <div class="scn-seq flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.06] px-3 py-2.5 text-white" style="--t:8s">
        <span class="rounded-md bg-signal-500/25 px-1.5 py-0.5 text-[10px] font-bold text-signal-200">Q</span>{{ $d['question'] }}
    </div>
    <div class="scn-seq relative rounded-xl border-l-4 border-growth-600 bg-white/[0.06] p-3.5" style="--t:8s; --d:1s">
        <p class="text-[10px] font-bold tracking-wider text-growth-100 uppercase">Direct answer</p>
        <div class="mt-2 space-y-1.5">
            <div class="scn-grow-x h-2 w-[95%] rounded bg-white/25" style="--t:8s; --d:1.4s"></div>
            <div class="scn-grow-x h-2 w-[88%] rounded bg-white/25" style="--t:8s; --d:1.7s"></div>
            <div class="scn-grow-x h-2 w-[60%] rounded bg-white/25" style="--t:8s; --d:2s"></div>
        </div>
        <p class="mt-2.5 text-[10px] text-navy-200">Source: <span class="font-semibold text-brand-200">{{ $d['domain'] }}</span></p>
    </div>
    <div class="grid flex-1 grid-cols-[1.1fr_1fr] gap-3">
        <div class="scn-seq rounded-xl border border-white/10 bg-navy-950/80 p-3 font-mono text-[10px] leading-relaxed" style="--t:8s; --d:2.6s">
            <span class="text-navy-300">{</span><br>
            &nbsp;<span class="text-ai-200">"@type"</span>: <span class="text-growth-100">"FAQPage"</span>,<br>
            &nbsp;<span class="text-ai-200">"mainEntity"</span>: <span class="text-brand-200">[…]</span><br>
            <span class="text-navy-300">}</span>
        </div>
        <div class="scn-seq flex flex-col justify-between rounded-xl border border-white/10 bg-white/[0.04] p-3" style="--t:8s; --d:3.2s">
            <p class="flex items-center gap-1.5 text-[10px] font-semibold text-navy-100"><x-glyph name="message" class="size-3.5 text-signal-400" /> Voice & assistants</p>
            <div class="flex h-10 items-center gap-1">
                @foreach ([30, 70, 45, 90, 55, 80, 35, 65, 40, 75] as $i => $h)
                    <span class="orbit-bar w-full rounded-full bg-gradient-to-t from-signal-500 to-ai-400" style="height: {{ $h }}%; animation-duration: 1.4s; animation-delay: {{ $i * .12 }}s"></span>
                @endforeach
            </div>
        </div>
    </div>
</div>
