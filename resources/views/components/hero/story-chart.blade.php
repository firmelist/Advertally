{{-- Case studies hero: a growth story drawn as a chart — diagnosis, system, results — with markers dropping in. --}}
<div {{ $attributes->merge(['class' => 'relative mx-auto w-full max-w-[32rem]']) }} aria-hidden="true">
    <div class="absolute inset-[6%] rounded-full bg-gradient-to-br from-brand-300/30 via-ai-300/25 to-growth-100/40 blur-3xl"></div>
    <div class="relative rounded-3xl border border-white bg-white/90 p-5 shadow-[var(--shadow-lift)] backdrop-blur">
        <div class="flex items-center justify-between">
            <p class="text-sm font-bold text-ink">Anatomy of a growth story</p>
            <span class="sample-badge">Illustrative</span>
        </div>
        <svg class="mt-4 h-52 w-full" viewBox="0 0 320 180" fill="none">
            @foreach ([40, 80, 120, 160] as $y)<line x1="0" x2="320" y1="{{ $y }}" y2="{{ $y }}" stroke="#E2E8F0" stroke-dasharray="3 4"/>@endforeach
            <path d="M0 150 C 40 148, 60 150, 90 140 S 140 120, 160 110 S 220 70, 250 52 S 300 24, 320 18 L320 180 L0 180 Z" fill="url(#sc-fill)" class="scn-seq" style="--t:9s; --d:1.6s"/>
            <path d="M0 150 C 40 148, 60 150, 90 140 S 140 120, 160 110 S 220 70, 250 52 S 300 24, 320 18" stroke="url(#sc-line)" stroke-width="3.5" stroke-linecap="round" class="scn-draw" style="--len:400; --t:9s"/>
            @foreach ([[90, 140, 'Diagnosis', 1.2, '#06B6D4'], [160, 110, 'System built', 2.2, '#7C3AED'], [250, 52, 'Momentum', 3.2, '#2563EB'], [320, 18, 'Results', 4.2, '#16A34A']] as [$x, $y, $label, $delay, $c])
                <g class="scn-pop" style="--t:9s; --d:{{ $delay }}s; transform-box: fill-box; transform-origin: center">
                    <circle cx="{{ min($x, 312) }}" cy="{{ $y }}" r="7" fill="#fff" stroke="{{ $c }}" stroke-width="3"/>
                </g>
            @endforeach
            <defs>
                <linearGradient id="sc-line" x1="0" x2="320" y1="0" y2="0" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#16A34A"/></linearGradient>
                <linearGradient id="sc-fill" x1="0" x2="0" y1="0" y2="180" gradientUnits="userSpaceOnUse"><stop stop-color="#7C3AED" stop-opacity=".18"/><stop offset="1" stop-color="#7C3AED" stop-opacity="0"/></linearGradient>
            </defs>
        </svg>
        <div class="mt-2 grid grid-cols-4 gap-2 text-center text-[11px] font-semibold">
            @foreach ([['Diagnosis', 'text-signal-600'], ['System', 'text-ai-600'], ['Momentum', 'text-brand-700'], ['Results', 'text-growth-700']] as $s => [$label, $tone])
                <span class="scn-seq rounded-lg bg-canvas py-1.5 {{ $tone }}" style="--t:9s; --d:{{ 1.2 + $s }}s">{{ $label }}</span>
            @endforeach
        </div>
    </div>
</div>
