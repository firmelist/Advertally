@props(['industries' => collect()])
{{-- Industries hero: a slow-turning wheel of the markets we serve; each sector lights as the beam passes. --}}
@php $items = $industries->take(8)->values(); $n = max(1, $items->count()); @endphp
<div {{ $attributes->merge(['class' => 'relative mx-auto aspect-square w-full max-w-[30rem]']) }} aria-hidden="true">
    <div class="absolute inset-[12%] rounded-full bg-gradient-to-br from-brand-300/30 via-signal-300/25 to-ai-300/25 blur-3xl"></div>
    <div class="absolute inset-[6%] rounded-full" style="background: conic-gradient(from 0deg, rgb(37 99 235 / .22), transparent 18%, transparent); animation: orbit-spin 10s linear infinite"></div>
    <div class="absolute inset-[6%] rounded-full border border-brand-200/70"></div>
    <div class="absolute inset-[26%] rounded-full border border-dashed border-ai-200"></div>
    @foreach ($items as $i => $industry)
        @php $a = deg2rad(-90 + $i * 360 / $n); $x = 50 + 40 * cos($a); $y = 50 + 40 * sin($a); @endphp
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="flex flex-col items-center">
                <span class="relative grid size-12 place-items-center rounded-2xl bg-white text-brand-700 shadow-[var(--shadow-card)] ring-1 ring-line">
                    <x-glyph :name="$industry->icon ?: 'building'" class="size-5" />
                    <span class="absolute inset-0 rounded-2xl ring-2 ring-brand-500" style="animation: cyc-on 10s linear infinite; animation-delay: {{ $i * 10 / $n - 10 }}s"></span>
                </span>
                <span class="mt-1.5 rounded-full bg-white/90 px-2 text-[11px] font-bold whitespace-nowrap text-ink shadow-xs">{{ $industry->name }}</span>
            </div>
        </div>
    @endforeach
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center">
        <div class="orbit-core mx-auto grid size-20 place-items-center rounded-[1.5rem] bg-navy-900 text-white"><x-glyph name="network" class="size-8" /></div>
        <p class="mt-2 text-xs font-bold text-ink">Considered, high-value markets</p>
    </div>
</div>
<style>@keyframes cyc-on { 0%, 2% { opacity: 0; transform: scale(.85); } 5%, 20% { opacity: 1; transform: none; } 26%, 100% { opacity: 0; } }</style>
