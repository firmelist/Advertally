{{-- YouTube: your expertise on screen — playing, chaptered, and building an audience. --}}
<div class="flex h-full flex-col gap-3 text-[11px]">
    <div class="relative flex-1 overflow-hidden rounded-xl border border-white/10 bg-gradient-to-br from-navy-800 via-navy-900 to-ai-600/40">
        <div class="absolute top-3 left-3 rounded-md bg-black/50 px-2 py-1 text-[10px] font-semibold text-white">{{ $d['title'] }}</div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <span class="scn-pulse absolute inset-0 rounded-2xl bg-red-500/50"></span>
            <span class="relative grid h-12 w-16 place-items-center rounded-2xl bg-red-600 shadow-lg"><svg class="size-6" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg></span>
        </div>
        <div class="absolute inset-x-3 bottom-3">
            <div class="relative h-1.5 rounded-full bg-white/20">
                <div class="scn-grow-x absolute inset-y-0 left-0 w-full rounded-full bg-red-500" style="--t:9s"></div>
                @foreach ([22, 48, 74] as $c)<span class="absolute top-0 h-1.5 w-0.5 bg-navy-950" style="left: {{ $c }}%"></span>@endforeach
            </div>
            <div class="mt-1.5 flex justify-between text-[9px] text-white/70">
                @foreach ($d['chapters'] as $ch)<span>{{ $ch }}</span>@endforeach
            </div>
        </div>
    </div>
    <div class="grid grid-cols-[1fr_auto] items-center gap-3 rounded-xl border border-white/10 bg-white/[0.05] p-2.5">
        <div class="flex items-center gap-2">
            <span class="size-7 rounded-full bg-gradient-to-br from-signal-400 to-ai-500"></span>
            <div><p class="font-bold text-white">{{ $d['brand'] }}</p><p class="text-[10px] text-navy-300">Expert-led video</p></div>
        </div>
        <span class="scn-shake rounded-full bg-white px-3 py-1.5 text-[10px] font-bold text-navy-900" style="--t:4s">Subscribe</span>
    </div>
    <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2">
        <span class="flex -space-x-1.5">
            @for ($a = 0; $a < 7; $a++)
                <span class="scn-pop size-5 rounded-full ring-2 ring-navy-900 {{ ['bg-signal-500', 'bg-brand-500', 'bg-ai-500', 'bg-growth-600'][$a % 4] }}" style="--t:9s; --d:{{ 1 + $a * .45 }}s"></span>
            @endfor
        </span>
        <span class="text-navy-100">Viewers become a retargeting audience</span>
    </div>
</div>
