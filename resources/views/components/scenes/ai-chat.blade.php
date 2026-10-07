{{-- GEO: an AI assistant answers a buyer's question and cites your brand. --}}
<div class="flex h-full flex-col gap-3 text-[12px]">
    <div class="flex flex-wrap gap-1.5">
        @foreach (['ChatGPT', 'Gemini', 'Perplexity', 'Claude', 'Copilot'] as $i => $p)
            <span class="orbit-chip rounded-full px-2.5 py-1 font-semibold" style="animation-duration: 7.5s; animation-delay: {{ $i * 1.5 }}s">{{ $p }}</span>
        @endforeach
    </div>
    <div class="ml-auto max-w-[85%] rounded-2xl rounded-br-sm bg-brand-600 px-3.5 py-2.5 text-white">
        <span class="scn-type" style="--t:9s">{{ $d['prompt'] }}</span>
    </div>
    <div class="flex gap-2.5">
        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-ai-500 to-signal-500 text-white"><x-glyph name="sparkles" class="size-4" /></span>
        <div class="flex-1 space-y-2 rounded-2xl rounded-tl-sm border border-white/10 bg-white/[0.05] p-3">
            <div class="scn-grow-x h-2 w-[92%] rounded bg-white/20" style="--t:9s; --d:2.2s"></div>
            <div class="scn-grow-x h-2 w-[78%] rounded bg-white/20" style="--t:9s; --d:2.6s"></div>
            <p class="scn-seq leading-relaxed text-navy-100" style="--t:9s; --d:3.2s">
                Teams often shortlist <mark class="rounded bg-ai-500/40 px-1 font-bold text-white">{{ $d['brand'] }}</mark> {{ $d['reason'] }}
            </p>
            <div class="flex flex-wrap gap-1.5 pt-1">
                <span class="scn-pop scn-glow rounded-md border border-ai-400/60 bg-ai-500/20 px-2 py-0.5 font-semibold text-ai-100" style="--t:9s; --d:4s">[1] {{ $d['domain'] }}</span>
                <span class="scn-pop rounded-md border border-white/10 bg-white/5 px-2 py-0.5 text-navy-200" style="--t:9s; --d:4.4s">[2] industry-review.com</span>
            </div>
        </div>
    </div>
</div>
