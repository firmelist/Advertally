{{-- Hire developers: real work, shipped — code written in your repo, reviewed, tested and deployed. --}}
<div class="flex h-full flex-col overflow-hidden rounded-xl border border-white/10 bg-[#050f1e] font-mono text-[11px]">
    <div class="flex items-center gap-3 border-b border-white/10 px-3 py-2 text-[10px]">
        @foreach ($d['tabs'] as $t => $tab)<span @class(['rounded px-1.5 py-0.5', 'bg-white/10 text-white' => $t === 0, 'text-navy-300' => $t !== 0])>{{ $tab }}</span>@endforeach
    </div>
    <div class="flex-1 space-y-1 p-3 leading-relaxed">
        @foreach ($d['lines'] as $i => $line)
            <p class="flex gap-3" style="padding-left: {{ ($line[0] ?? 0) * 14 }}px">
                <span class="w-4 shrink-0 text-right text-navy-500">{{ $i + 1 }}</span>
                <span class="scn-type text-navy-100" style="--t:10s; --d:{{ $i * .45 }}s">{!! $line[1] !!}</span>
            </p>
        @endforeach
    </div>
    <div class="flex flex-wrap items-center gap-1.5 border-t border-white/10 px-3 py-2 font-sans text-[10px]">
        @foreach (['Lint', 'Tests', 'Review', 'Deploy'] as $c => $check)
            <span class="scn-pop flex items-center gap-1 rounded-full bg-growth-600/20 px-2 py-0.5 font-semibold text-growth-100" style="--t:10s; --d:{{ 4.4 + $c * .5 }}s">✓ {{ $check }}</span>
        @endforeach
        <span class="ml-auto text-navy-300">{{ $d['stack'] }}</span>
    </div>
</div>
