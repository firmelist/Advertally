{{-- CRO: a live A/B test — attention heat-maps the page and variant B earns the win. --}}
<div class="flex h-full flex-col gap-3 text-[11px]">
    <div class="grid flex-1 grid-cols-2 gap-3">
        @foreach (['A' => ['Learn more', 'bg-slate-400', false], 'B' => [$d['cta'], 'bg-brand-600', true]] as $variant => [$cta, $btn, $winner])
            <div @class(['relative overflow-hidden rounded-xl border bg-white p-2.5', 'border-growth-600 shadow-[0_0_24px_rgb(22_163_74/.45)]' => $winner, 'border-white/10' => ! $winner])>
                <span class="absolute top-2 right-2 rounded-full bg-navy-900 px-1.5 text-[9px] font-bold text-white">{{ $variant }}</span>
                <div class="h-2 w-2/3 rounded bg-navy-900"></div>
                <p class="mt-2 text-[11px] leading-tight font-bold text-navy-900">{{ $winner ? $d['headline_b'] : $d['headline_a'] }}</p>
                <div class="mt-1.5 h-1.5 w-full rounded bg-slate-200"></div>
                <div class="mt-1 h-1.5 w-3/4 rounded bg-slate-200"></div>
                <span class="mt-2.5 inline-block rounded-md px-2.5 py-1 text-[9px] font-bold text-white {{ $btn }}">{{ $cta }}</span>
                @if ($winner)<div class="mt-2 flex gap-1">@for ($s = 0; $s < 3; $s++)<span class="h-3 w-7 rounded bg-slate-200"></span>@endfor</div>@endif
                {{-- heat dots --}}
                @foreach ($winner ? [[30, 70], [38, 74], [26, 66], [44, 72], [34, 78]] : [[60, 30], [70, 50], [40, 82]] as $h => [$x, $y])
                    <span class="scn-pop absolute size-5 -translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500/40 blur-[3px]" style="left: {{ $x }}%; top: {{ $y }}%; --t:8s; --d:{{ .6 + $h * .4 }}s"></span>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="space-y-2 rounded-xl border border-white/10 bg-white/[0.05] p-3">
        @foreach (['A' => [42, 'bg-slate-400'], 'B' => [78, 'bg-growth-600']] as $variant => [$w, $tone])
            <div class="flex items-center gap-2">
                <span class="w-14 font-semibold text-navy-100">Variant {{ $variant }}</span>
                <div class="h-2.5 flex-1 rounded-full bg-white/10"><div class="scn-grow-x h-2.5 rounded-full {{ $tone }}" style="width: {{ $w }}%; --t:8s; --d:2.4s"></div></div>
            </div>
        @endforeach
        <p class="scn-pop flex items-center justify-center gap-1.5 pt-1 font-bold text-growth-100" style="--t:8s; --d:4.4s"><x-glyph name="trending-up" class="size-4" /> B wins — rolled out to all traffic</p>
    </div>
</div>
