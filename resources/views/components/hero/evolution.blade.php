{{-- About hero: scattered channels (SEO, PPC, Social, Website) drift in, then snap into one connected six-engine system. --}}
@php
    $engines = [['AI Search', 'search'], ['Demand', 'megaphone'], ['Authority', 'shield-check'], ['Conversion', 'pointer'], ['Automation', 'workflow'], ['Intelligence', 'bar-chart']];
    $old = [['SEO', -38, -30], ['PPC', 34, -36], ['Social', -40, 30], ['Website', 36, 32]];
@endphp
<div {{ $attributes->merge(['class' => 'relative mx-auto aspect-square w-full max-w-[30rem]']) }} aria-hidden="true">
    <div class="absolute inset-[12%] rounded-full bg-gradient-to-br from-brand-300/30 via-ai-300/25 to-signal-300/25 blur-3xl"></div>
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" fill="none">
        <circle cx="50" cy="50" r="36" stroke="url(#evo-g)" stroke-width=".5" stroke-dasharray="1.2 2" class="scn-spin" style="--t:40s"/>
        @foreach ($engines as $i => $e)
            @php $a = deg2rad(-90 + $i * 60); $x = 50 + 36 * cos($a); $y = 50 + 36 * sin($a); @endphp
            <line x1="50" y1="50" x2="{{ $x }}" y2="{{ $y }}" stroke="url(#evo-g)" stroke-width=".5" class="scn-draw" style="--len:40; --t:10s; --d:{{ 4 + $i * .2 }}s"/>
        @endforeach
        <defs><linearGradient id="evo-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/></linearGradient></defs>
    </svg>

    {{-- phase 1: isolated channels, drifting and disconnected --}}
    @foreach ($old as $i => [$label, $dx, $dy])
        <div class="absolute" style="left: {{ 50 + $dx }}%; top: {{ 50 + $dy }}%">
            <span class="block" style="animation: evo-old 10s ease-in-out infinite both; animation-delay: {{ $i * .25 }}s">
                <span class="block rounded-xl border border-dashed border-navy-300 bg-white/80 px-3 py-1.5 text-xs font-semibold text-navy-400 shadow-sm">{{ $label }}</span>
            </span>
        </div>
    @endforeach

    {{-- phase 2: six connected engines --}}
    @foreach ($engines as $i => [$label, $icon])
        @php $a = deg2rad(-90 + $i * 60); $x = 50 + 36 * cos($a); $y = 50 + 36 * sin($a); @endphp
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="flex flex-col items-center" style="animation: evo-new 10s cubic-bezier(.2,.9,.3,1.2) infinite both; animation-delay: {{ 3.6 + $i * .2 }}s">
                <span class="grid size-12 place-items-center rounded-2xl bg-white text-brand-700 shadow-[var(--shadow-lift)] ring-1 ring-brand-100"><x-glyph :name="$icon" class="size-5" /></span>
                <span class="mt-1.5 rounded-full bg-white/90 px-2 text-[11px] font-bold whitespace-nowrap text-ink shadow-xs">{{ $label }}</span>
            </div>
        </div>
    @endforeach

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center">
        <div class="grid size-24 place-items-center rounded-[1.75rem] bg-navy-900 shadow-[0_0_60px_rgb(124_58_237/.45)] orbit-core">
            <x-logo dark class="[&>span:last-child]:hidden" />
        </div>
        <p class="mt-2 text-xs font-bold text-ink">One growth system</p>
    </div>
</div>
<style>
    @keyframes evo-old { 0% { opacity: 0; transform: translate(-50%, -50%) scale(.8); } 8%, 28% { opacity: 1; transform: translate(-50%, -50%) rotate(-4deg); } 36%, 100% { opacity: 0; transform: translate(-50%, -50%) scale(.3); } }
    @keyframes evo-new { 0% { opacity: 0; transform: scale(.3); } 6%, 82% { opacity: 1; transform: none; } 92%, 100% { opacity: 0; transform: scale(.8); } }
</style>
