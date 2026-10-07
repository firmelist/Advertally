{{-- Attribution: a real buyer journey — every touchpoint lights up and earns its share of the deal. --}}
@php $touches = $d['touchpoints']; $n = count($touches); @endphp
<div class="relative flex h-full flex-col text-[11px]">
    <div class="relative mt-2 flex items-center justify-between px-2">
        <div class="absolute inset-x-6 top-[18px] h-0.5 bg-white/10"></div>
        <div class="scn-grow-x absolute inset-x-6 top-[18px] h-0.5 bg-gradient-to-r from-signal-400 via-ai-500 to-growth-600" style="--t:9s"></div>
        @foreach ($touches as $i => [$label, $icon])
            <div class="relative z-10 flex w-14 flex-col items-center text-center">
                <span class="relative grid size-9 place-items-center rounded-full border-2 border-white/20 bg-navy-800 text-navy-200">
                    <x-glyph :name="$icon" class="size-4" />
                    <span class="scn-pop absolute inset-[-2px] grid place-items-center rounded-full border-2 border-signal-400 bg-signal-500/30 text-white" style="--t:9s; --d:{{ .4 + $i * .55 }}s"><x-glyph :name="$icon" class="size-4" /></span>
                </span>
                <span class="mt-1.5 text-[10px] leading-tight font-semibold text-navy-100">{{ $label }}</span>
            </div>
        @endforeach
    </div>
    <div class="mt-4 flex-1 rounded-xl border border-white/10 bg-white/[0.05] p-3">
        <p class="flex justify-between text-[10px] font-bold text-navy-200"><span>Credit by touchpoint</span><span>First · Last · Multi-touch</span></p>
        <div class="mt-2.5 space-y-1.5">
            @foreach ($touches as $i => [$label])
                <div class="flex items-center gap-2">
                    <span class="w-16 truncate text-navy-100">{{ $label }}</span>
                    <div class="h-2 flex-1 rounded-full bg-white/10"><div class="scn-grow-x h-2 rounded-full bg-gradient-to-r from-brand-500 to-ai-500" style="width: {{ $d['weights'][$i] }}%; --t:9s; --d:{{ 3.6 + $i * .2 }}s"></div></div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="scn-pop mt-3 flex items-center justify-center gap-2 rounded-xl bg-growth-600 py-2 font-bold text-white" style="--t:9s; --d:5.4s"><x-glyph name="trending-up" class="size-4" /> Closed deal attributed in the CRM</div>
</div>
