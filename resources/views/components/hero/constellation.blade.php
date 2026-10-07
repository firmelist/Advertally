{{-- Careers hero: a constellation of specialists — stars connect into one team as disciplines light up. --}}
@php
    $people = [['Strategy', 18, 22], ['SEO & AI', 50, 10], ['Paid media', 82, 24], ['Content', 88, 60], ['CRO', 68, 86], ['Engineering', 32, 86], ['Analytics', 12, 60], ['Automation', 50, 50]];
    $links = [[0, 1], [1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 0], [7, 0], [7, 2], [7, 4], [7, 6], [7, 1]];
@endphp
<div {{ $attributes->merge(['class' => 'relative mx-auto aspect-square w-full max-w-[30rem]']) }} aria-hidden="true">
    <div class="absolute inset-[10%] rounded-full bg-gradient-to-br from-ai-300/30 via-brand-300/25 to-signal-300/25 blur-3xl"></div>
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" fill="none">
        @foreach ($links as $l => [$a, $b])
            <line x1="{{ $people[$a][1] }}" y1="{{ $people[$a][2] }}" x2="{{ $people[$b][1] }}" y2="{{ $people[$b][2] }}" stroke="url(#con-g)" stroke-width=".45" class="scn-draw" style="--len:60; --t:9s; --d:{{ .3 * $l }}s"/>
        @endforeach
        <defs><linearGradient id="con-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/></linearGradient></defs>
    </svg>
    @foreach ($people as $i => [$role, $x, $y])
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="scn-float flex flex-col items-center" style="--d:-{{ $i * .7 }}s">
                <span @class(['relative grid place-items-center rounded-full text-sm font-bold text-white shadow-[var(--shadow-lift)] ring-4 ring-white',
                    'size-12 sm:size-16 bg-gradient-to-br from-navy-700 to-navy-900' => $i === 7, 'size-10 sm:size-12 '.['bg-signal-500', 'bg-brand-600', 'bg-ai-600', 'bg-brand-500'][$i % 4] => $i !== 7])>
                    <x-glyph :name="['compass', 'search', 'megaphone', 'file-text', 'gauge', 'code', 'chart', 'workflow'][$i]" class="size-4 sm:size-5" />
                    <span class="scn-pulse absolute inset-0 rounded-full border-2 border-white/70" style="--t:3s; --d:{{ $i * .4 }}s"></span>
                </span>
                <span class="mt-1 rounded-full bg-white/90 px-1.5 text-[10px] font-bold whitespace-nowrap text-ink shadow-xs sm:mt-1.5 sm:px-2 sm:text-[11px]">{{ $role }}</span>
            </div>
        </div>
    @endforeach
</div>
