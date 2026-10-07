{{-- Websites / landing pages: the page assembles block by block, then passes speed, SEO and accessibility checks. --}}
@php $landing = ($d['variant'] ?? 'site') === 'landing'; @endphp
<div class="grid h-full grid-cols-[1fr_7rem] gap-3 text-[11px] sm:grid-cols-[1fr_8rem]">
    <div class="flex flex-col overflow-hidden rounded-xl border border-white/10 bg-white">
        <div class="flex items-center gap-1.5 border-b border-slate-200 bg-slate-100 px-2.5 py-1.5">
            <span class="size-1.5 rounded-full bg-slate-300"></span><span class="size-1.5 rounded-full bg-slate-300"></span>
            <span class="ml-1 flex-1 truncate rounded bg-white px-2 py-0.5 text-[9px] text-slate-500">{{ $d['url'] }}</span>
        </div>
        <div class="flex-1 space-y-2 p-2.5">
            <div class="scn-seq flex items-center justify-between" style="--t:9s; --d:.3s">
                <span class="h-2 w-12 rounded bg-navy-900"></span>
                @unless ($landing)<span class="flex gap-1.5">@for ($n = 0; $n < 3; $n++)<span class="h-1.5 w-6 rounded bg-slate-300"></span>@endfor</span>@endunless
            </div>
            <div class="scn-seq rounded-lg bg-gradient-to-br from-brand-50 to-violet-50 p-2.5" style="--t:9s; --d:.9s">
                <p class="text-[12px] leading-tight font-extrabold text-navy-900">{{ $d['headline'] }}</p>
                <div class="mt-1.5 h-1.5 w-3/4 rounded bg-slate-300"></div>
                <span class="scn-glow mt-2 inline-block rounded-md bg-brand-600 px-2.5 py-1 text-[9px] font-bold text-white" style="--t:2.5s">{{ $d['cta'] }}</span>
            </div>
            @if ($landing)
                <div class="scn-seq flex items-center gap-2 rounded-lg border border-slate-200 p-2" style="--t:9s; --d:1.5s">
                    @for ($s = 0; $s < 4; $s++)<span class="h-3 w-8 rounded bg-slate-200"></span>@endfor
                    <span class="ml-auto text-[8px] font-semibold text-slate-500">Trusted by teams</span>
                </div>
                <div class="scn-seq rounded-lg border border-brand-200 p-2" style="--t:9s; --d:2.1s">
                    <div class="grid grid-cols-2 gap-1.5"><span class="h-4 rounded bg-slate-100"></span><span class="h-4 rounded bg-slate-100"></span></div>
                    <span class="mt-1.5 block rounded bg-brand-600 py-1 text-center text-[8px] font-bold text-white">{{ $d['cta'] }}</span>
                </div>
            @else
                <div class="grid grid-cols-3 gap-1.5">
                    @for ($c = 0; $c < 3; $c++)
                        <div class="scn-seq rounded-lg border border-slate-200 p-1.5" style="--t:9s; --d:{{ 1.5 + $c * .3 }}s">
                            <span class="block size-3 rounded bg-brand-200"></span><span class="mt-1 block h-1 rounded bg-slate-300"></span><span class="mt-1 block h-1 w-2/3 rounded bg-slate-200"></span>
                        </div>
                    @endfor
                </div>
                <div class="scn-seq h-6 rounded-lg bg-navy-900" style="--t:9s; --d:2.6s"></div>
            @endif
        </div>
    </div>
    <div class="flex flex-col gap-2">
        @foreach ($d['checks'] as $i => $check)
            <div class="scn-pop flex items-center gap-1.5 rounded-lg border border-growth-600/40 bg-growth-600/10 px-2 py-2 font-semibold text-growth-100" style="--t:9s; --d:{{ 3.2 + $i * .5 }}s">
                <x-glyph name="check-circle" class="size-4 shrink-0" /> <span class="leading-tight">{{ $check }}</span>
            </div>
        @endforeach
        <div class="mt-auto rounded-lg border border-white/10 bg-white/[0.05] p-2 text-center">
            <p class="text-[9px] text-navy-300">Built in</p><p class="font-bold text-white">{{ $d['stack'] }}</p>
        </div>
    </div>
</div>
