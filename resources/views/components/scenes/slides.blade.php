{{-- Thought leadership: on stage, under the spotlight, a signature framework builds itself. --}}
<div class="relative flex h-full flex-col items-center text-[11px]">
    <div class="pointer-events-none absolute -top-4 left-1/2 h-[120%] w-[70%] -translate-x-1/2 bg-[conic-gradient(from_180deg_at_50%_0%,transparent_150deg,rgb(167_139_250/.22)_180deg,transparent_210deg)]" style="animation: orbit-glow 4s ease-in-out infinite"></div>
    <div class="relative w-full max-w-[22rem] rounded-xl border border-white/15 bg-white/[0.07] p-3.5 shadow-2xl">
        <div class="flex items-center justify-between">
            <p class="text-[13px] font-extrabold text-white">{{ $d['framework'] }}</p>
            <span class="rounded bg-ai-500/30 px-1.5 py-0.5 text-[9px] font-bold text-ai-100">Framework</span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2">
            @foreach ($d['quadrants'] as $i => $q)
                <div class="scn-pop rounded-lg border border-white/10 p-2.5 {{ ['bg-brand-600/25', 'bg-ai-500/25', 'bg-signal-500/20', 'bg-growth-600/25'][$i] }}" style="--t:9s; --d:{{ .6 + $i * .7 }}s">
                    <p class="font-bold text-white">{{ $q }}</p>
                    <div class="mt-1.5 h-1 w-3/4 rounded bg-white/25"></div>
                </div>
            @endforeach
        </div>
        <p class="scn-seq mt-3 text-center text-[10px] text-navy-200 italic" style="--t:9s; --d:3.6s">“{{ $d['point_of_view'] }}”</p>
    </div>
    <div class="relative mt-auto flex w-full max-w-[22rem] items-end justify-between px-2">
        @for ($a = 0; $a < 9; $a++)
            <span class="scn-pop size-4 rounded-t-full {{ ['bg-signal-400/70', 'bg-brand-400/70', 'bg-ai-400/70'][$a % 3] }}" style="--t:9s; --d:{{ 4 + $a * .15 }}s"></span>
        @endfor
    </div>
    <div class="h-2 w-full max-w-[24rem] rounded-full bg-white/10"></div>
    <p class="mt-2 text-[10px] text-navy-300">Keynotes · reports · podcasts · articles</p>
</div>
