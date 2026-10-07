{{-- Custom growth tools: an interactive calculator — sliders move, the result updates, a qualified lead is captured. --}}
<div class="grid h-full grid-cols-[1.25fr_1fr] gap-3 text-[11px]">
    <div class="flex flex-col justify-between rounded-xl border border-white/10 bg-white/[0.05] p-3">
        <p class="font-bold text-white">{{ $d['title'] }}</p>
        @foreach ($d['inputs'] as $i => $input)
            <div>
                <p class="flex justify-between text-[10px] text-navy-200"><span>{{ $input }}</span></p>
                <div class="relative mt-2 h-1.5 rounded-full bg-white/15">
                    <div class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-brand-500 to-ai-500" style="animation: scn-slider-fill 6s ease-in-out infinite; animation-delay: -{{ $i * 1.5 }}s"></div>
                    <span class="absolute top-1/2 size-3.5 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white bg-ai-500 shadow" style="animation: scn-slider 6s ease-in-out infinite; animation-delay: -{{ $i * 1.5 }}s"></span>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col gap-2">
        <div class="flex flex-1 flex-col justify-center rounded-xl border border-growth-600/40 bg-growth-600/10 p-3 text-center">
            <p class="text-[10px] text-navy-200">{{ $d['result'] }}</p>
            <div class="mx-auto mt-2 flex h-14 items-end gap-1">
                @foreach ([40, 60, 50, 80, 100] as $b => $h)<span class="orbit-bar w-3 rounded-sm bg-growth-600" style="height: {{ $h }}%; animation-delay: {{ $b * .2 }}s"></span>@endforeach
            </div>
            <p class="mt-2 text-[10px] font-semibold text-growth-100">Updates as you move the sliders</p>
        </div>
        <span class="scn-glow rounded-lg bg-brand-600 py-2 text-center text-[10px] font-bold text-white">Email me the full report</span>
    </div>
</div>
<style>
    @keyframes scn-slider { 0%, 100% { left: 25%; } 50% { left: 80%; } }
    @keyframes scn-slider-fill { 0%, 100% { width: 25%; } 50% { width: 80%; } }
</style>
