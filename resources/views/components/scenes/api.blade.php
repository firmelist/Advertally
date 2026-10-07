{{-- API integration: a request goes out, a response comes back, failures retry — logged and monitored. --}}
<div class="grid h-full grid-rows-[auto_1fr_auto] gap-2.5 font-mono text-[10px]">
    <div class="scn-seq rounded-xl border border-white/10 bg-[#050f1e] p-2.5" style="--t:8s">
        <p><span class="rounded bg-brand-600 px-1 font-bold text-white">POST</span> <span class="text-navy-100">{{ $d['endpoint'] }}</span></p>
        <p class="mt-1.5 text-navy-300">{ <span class="text-ai-200">"event"</span>: <span class="text-growth-100">"{{ $d['event'] }}"</span>, <span class="text-ai-200">"source"</span>: <span class="text-growth-100">"website"</span> }</p>
    </div>
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-white/[0.03]">
        <div class="absolute inset-y-0 left-1/2 w-px bg-white/10"></div>
        @foreach ([0, 1.3, 2.6] as $k)
            <span class="scn-fall absolute left-1/2 top-0 -ml-1 size-2 rounded-full bg-signal-400 shadow-[0_0_8px_rgb(34_211_238)]" style="--t:3.9s; --d:{{ $k }}s; --y:7rem; --end:0"></span>
        @endforeach
        <div class="absolute top-2 left-2 rounded-md bg-white/10 px-2 py-1 font-sans text-[10px] font-semibold text-white">{{ $d['from'] }}</div>
        <div class="absolute right-2 bottom-2 rounded-md bg-white/10 px-2 py-1 font-sans text-[10px] font-semibold text-white">{{ $d['to'] }}</div>
        <div class="scn-pop absolute top-1/2 right-3 -translate-y-1/2 rounded-md border border-amber-300/40 bg-amber-300/10 px-2 py-1 font-sans text-[9px] text-amber-100" style="--t:8s; --d:2s">timeout → retry 1/3</div>
    </div>
    <div class="scn-seq rounded-xl border border-growth-600/40 bg-growth-600/10 p-2.5" style="--t:8s; --d:3.2s">
        <p><span class="rounded bg-growth-600 px-1 font-bold text-white">200 OK</span> <span class="text-navy-200">fast · logged</span></p>
        <p class="mt-1.5 text-navy-300">{ <span class="text-ai-200">"status"</span>: <span class="text-growth-100">"synced"</span>, <span class="text-ai-200">"id"</span>: <span class="text-brand-200">"…"</span> }</p>
    </div>
</div>
