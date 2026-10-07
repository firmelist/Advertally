{{-- Analytics: real interactions fire named events into GA4 — consent respected, conversions marked. --}}
<div class="grid h-full grid-cols-[1fr_1.1fr] gap-3 text-[11px]">
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-white p-2.5">
        <div class="h-2 w-14 rounded bg-navy-900"></div>
        <div class="mt-2 rounded-lg bg-slate-100 p-2">
            <div class="h-2 w-3/4 rounded bg-slate-300"></div>
            <span class="mt-2 inline-block rounded bg-brand-600 px-2 py-0.5 text-[9px] font-bold text-white">Book a call</span>
        </div>
        <div class="mt-2 space-y-1"><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-2/3 rounded bg-slate-200"></div></div>
        <div class="mt-2 rounded-lg border border-slate-200 p-1.5"><div class="h-2 rounded bg-slate-100"></div><div class="mt-1 h-2 rounded bg-slate-100"></div></div>
        {{-- click ripples --}}
        @foreach ([[30, 34, 0], [70, 86, 2.6], [50, 62, 5.2]] as [$x, $y, $delay])
            <span class="scn-pulse absolute size-5 rounded-full border-2 border-brand-500" style="left: {{ $x }}%; top: {{ $y }}%; --t:7.8s; --d:{{ $delay }}s"></span>
        @endforeach
    </div>
    <div class="flex flex-col rounded-xl border border-white/10 bg-[#050f1e] p-2.5 font-mono">
        <p class="flex items-center justify-between font-sans text-[10px] font-bold text-navy-100"><span>GA4 · realtime events</span><span class="size-1.5 animate-pulse rounded-full bg-growth-600"></span></p>
        <div class="mt-2 flex-1 space-y-1.5">
            @foreach ($d['events'] as $i => [$event, $conversion])
                <p class="scn-seq flex items-center justify-between gap-2 rounded-md bg-white/[0.05] px-2 py-1 text-signal-200" style="--t:8s; --d:{{ .4 + $i * .9 }}s">
                    <span class="truncate">{{ $event }}</span>
                    @if ($conversion)<span class="shrink-0 rounded bg-growth-600/30 px-1 font-sans text-[8px] font-bold text-growth-100">conversion</span>@endif
                </p>
            @endforeach
        </div>
        <p class="mt-2 flex items-center gap-1 font-sans text-[9px] text-navy-300"><x-glyph name="lock" class="size-3" /> Loaded after cookie consent</p>
    </div>
</div>
