{{-- AI agents / AI integration: an agent works step by step — and waits for a human before acting. --}}
<div class="flex h-full flex-col overflow-hidden rounded-xl border border-white/10 bg-[#050f1e] font-mono text-[11px]">
    <div class="flex items-center justify-between border-b border-white/10 px-3 py-2 text-navy-200">
        <span class="flex items-center gap-1.5"><x-glyph name="bot" class="size-3.5 text-signal-400" /> {{ $d['agent'] }}</span>
        <span class="rounded bg-growth-600/20 px-1.5 text-[9px] text-growth-100">running</span>
    </div>
    <div class="flex-1 space-y-1.5 p-3">
        @foreach ($d['steps'] as $i => $step)
            @php [$icon, $tone] = match ($step[0]) { 'wait' => ['⏸', 'text-amber-200'], 'ok' => ['✓', 'text-growth-100'], default => ['→', 'text-brand-200'] }; @endphp
            <p class="scn-seq flex gap-2 text-navy-100" style="--t:10s; --d:{{ .3 + $i * .8 }}s"><span class="{{ $tone }}">{{ $icon }}</span><span>{{ $step[1] }}</span></p>
        @endforeach
        <p class="text-navy-300"><span class="text-signal-400">›</span> <span class="scn-blink">▍</span></p>
    </div>
    <div class="scn-pop m-3 mt-0 flex items-center justify-between rounded-lg border border-amber-300/40 bg-amber-300/10 px-3 py-2 font-sans" style="--t:10s; --d:{{ .3 + count($d['steps']) * .8 }}s">
        <span class="text-amber-100">Needs approval</span>
        <span class="flex gap-1.5"><span class="rounded bg-growth-600 px-2 py-0.5 text-[10px] font-bold text-white">Approve</span><span class="rounded bg-white/10 px-2 py-0.5 text-[10px] text-white">Edit</span></span>
    </div>
</div>
