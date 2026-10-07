{{-- Hire designers: a design tool in motion — cursor working, guides snapping, a conversion-ready screen taking shape. --}}
<div class="grid h-full grid-cols-[3rem_1fr_5rem] gap-2 text-[10px] sm:grid-cols-[3.5rem_1fr_6rem]">
    <div class="flex flex-col items-center gap-2 rounded-xl border border-white/10 bg-white/[0.05] py-2.5 text-navy-200">
        @foreach (['pointer', 'layout', 'file-text', 'layers'] as $tool)<x-glyph :name="$tool" class="size-4" />@endforeach
    </div>
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-[#1e2a3d] p-3">
        <div class="relative h-full rounded-lg bg-white p-2.5">
            <div class="h-2 w-10 rounded bg-navy-900"></div>
            <div class="mt-2 h-14 rounded-md bg-gradient-to-br from-brand-100 to-violet-100" style="animation: scn-resize 8s ease-in-out infinite"></div>
            <div class="mt-2 grid grid-cols-3 gap-1.5">
                @for ($c = 0; $c < 3; $c++)<span class="scn-pop h-8 rounded-md border border-slate-200" style="--t:8s; --d:{{ 1 + $c * .4 }}s"></span>@endfor
            </div>
            <span class="scn-glow mt-2 inline-block rounded-md bg-brand-600 px-3 py-1 text-[8px] font-bold text-white">{{ $d['cta'] }}</span>
            {{-- snap guides --}}
            <span class="scn-seq absolute inset-y-0 left-1/2 w-px bg-pink-500" style="--t:4s; --d:1s"></span>
            <span class="scn-seq absolute inset-x-0 top-[46%] h-px bg-pink-500" style="--t:4s; --d:1.4s"></span>
        </div>
        <span class="scn-cursor-a pointer-events-none absolute top-0 left-0 size-full">
            <svg class="size-5" viewBox="0 0 24 24"><path d="M4 3l7 17 2.5-7.5L21 10z" fill="#EC4899" stroke="#fff" stroke-width="1.2"/></svg>
            <span class="ml-4 rounded bg-pink-500 px-1.5 py-0.5 text-[8px] font-bold text-white">{{ $d['designer'] }}</span>
        </span>
    </div>
    <div class="space-y-2 rounded-xl border border-white/10 bg-white/[0.05] p-2">
        <p class="font-bold text-white">Styles</p>
        <div class="grid grid-cols-3 gap-1">
            @foreach (['#2563EB', '#0B1F3A', '#7C3AED', '#06B6D4', '#16A34A', '#F8FAFC'] as $sw)<span class="aspect-square rounded" style="background: {{ $sw }}"></span>@endforeach
        </div>
        <p class="pt-1 font-bold text-white">Type</p>
        <p class="text-[13px] font-extrabold text-white">Aa</p>
        <p class="text-navy-300">Design system</p>
    </div>
</div>
<style>@keyframes scn-resize { 0%, 100% { width: 100%; } 40% { width: 70%; } 60% { width: 100%; } }</style>
