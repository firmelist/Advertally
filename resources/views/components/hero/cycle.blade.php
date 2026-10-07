{{-- Approach hero: the six-step growth cycle — a light travels the wheel and each step lights up as it passes. --}}
@php $steps = [['Diagnose', 'compass'], ['Discover', 'search'], ['Build', 'layers'], ['Activate', 'rocket'], ['Measure', 'bar-chart'], ['Improve', 'refresh']]; @endphp
<div {{ $attributes->merge(['class' => 'relative mx-auto aspect-square w-full max-w-[30rem]']) }} aria-hidden="true">
    <div class="absolute inset-[14%] rounded-full bg-gradient-to-br from-brand-300/30 via-ai-300/25 to-signal-300/25 blur-3xl"></div>
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" fill="none">
        <circle cx="50" cy="50" r="38" stroke="#E2E8F0" stroke-width="2.2"/>
        <circle cx="50" cy="50" r="38" stroke="url(#cyc-g)" stroke-width="2.2" stroke-linecap="round" stroke-dasharray="40 199" transform="rotate(-90 50 50)" style="animation: orbit-spin 9s linear infinite; transform-origin: 50px 50px"/>
        <circle cx="50" cy="50" r="27" stroke="rgba(37,99,235,.12)" stroke-width=".4" stroke-dasharray="1 2"/>
        <defs><linearGradient id="cyc-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/></linearGradient></defs>
    </svg>
    @foreach ($steps as $i => [$label, $icon])
        @php $a = deg2rad(-90 + $i * 60); $x = 50 + 38 * cos($a); $y = 50 + 38 * sin($a); @endphp
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="flex flex-col items-center">
                <span class="relative grid size-11 place-items-center rounded-2xl bg-white text-navy-500 shadow-[var(--shadow-card)] ring-1 ring-line sm:size-14">
                    <x-glyph :name="$icon" class="size-5 sm:size-6" />
                    <span class="absolute inset-0 grid place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-ai-600 text-white shadow-[0_10px_30px_rgb(37_99_235/.45)]" style="animation: cyc-on 9s linear infinite; animation-delay: {{ $i * 1.5 - 9 }}s"><x-glyph :name="$icon" class="size-5 sm:size-6" /></span>
                </span>
                <span class="mt-1 rounded-full bg-white/90 px-1.5 text-[10px] font-bold whitespace-nowrap text-ink shadow-xs sm:mt-1.5 sm:px-2 sm:text-xs"><span class="mr-1 font-mono text-brand-600">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>{{ $label }}</span>
            </div>
        </div>
    @endforeach
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center">
        <p class="text-[11px] font-bold tracking-[0.14em] text-brand-700 uppercase">Continuous</p>
        <p class="text-2xl font-extrabold text-ink">Growth loop</p>
        <p class="mt-1 text-xs text-muted">Diagnose → Improve → repeat</p>
    </div>
</div>
<style>@keyframes cyc-on { 0%, 2% { opacity: 0; transform: scale(.85); } 5%, 20% { opacity: 1; transform: none; } 26%, 100% { opacity: 0; } }</style>
