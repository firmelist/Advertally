{{--
    Homepage hero — the Growth OS Reactor.
    Six engines orbit a Growth OS core. Each engine fires in turn (3s each, 18s loop): it lights up, signal particles
    stream into the core, revenue grows, and a live card below acts out that engine. CSS/SVG only; mobile-first
    vertical stack (ring on top, card below) so nothing can overflow the screen. Illustrative content only.
--}}
@php
    $engines = [
        ['AI Search', 'Be Found.', 'search', '#06B6D4'],
        ['Demand', 'Be Discovered.', 'megaphone', '#3B82F6'],
        ['Authority', 'Be Trusted.', 'shield-check', '#8B5CF6'],
        ['Conversion', 'Be Chosen.', 'pointer', '#2563EB'],
        ['Automation', 'Scale Faster.', 'workflow', '#7C3AED'],
        ['Intelligence', 'Know What Works.', 'bar-chart', '#16A34A'],
    ];
    $pos = fn (int $i, float $r) => [50 + $r * cos(deg2rad(-90 + $i * 60)), 50 + $r * sin(deg2rad(-90 + $i * 60))];
@endphp
<div {{ $attributes->merge(['class' => 'gos relative mx-auto flex w-full max-w-[34rem] min-w-0 flex-col items-center']) }} aria-label="Advertally Growth OS: six engines working as one system">

    {{-- ====== THE RING ====== --}}
    <div class="relative aspect-square w-full max-w-[24rem] sm:max-w-[28rem]" aria-hidden="true">
        <div class="absolute inset-[14%] rounded-full bg-gradient-to-br from-brand-400/30 via-ai-400/25 to-signal-400/25 blur-3xl"></div>

        <svg class="absolute inset-0 size-full overflow-visible" viewBox="0 0 100 100" fill="none">
            {{-- orbit + inner rings --}}
            <g class="scn-spin" style="--t:60s"><circle cx="50" cy="50" r="46" stroke="url(#gos-ring)" stroke-width=".35" stroke-dasharray="1 2.4" stroke-linecap="round"/></g>
            <circle cx="50" cy="50" r="38" stroke="rgba(37,99,235,.12)" stroke-width=".3"/>
            <circle cx="50" cy="50" r="22" stroke="rgba(124,58,237,.14)" stroke-width=".3" stroke-dasharray=".8 1.6"/>
            {{-- spokes + particles streaming into the core --}}
            @foreach ($engines as $i => [$name, $tag, $icon, $color])
                @php [$x, $y] = $pos($i, 38); @endphp
                <path id="gos-spoke-{{ $i }}" d="M{{ $x }} {{ $y }} L50 50" stroke="rgba(148,163,184,.25)" stroke-width=".35" stroke-dasharray="1 1.4"/>
                <g class="gos-flow" style="animation-delay: {{ $i * 3 - 18 }}s">
                    @foreach ([0, .5, 1] as $k)
                        <circle r="1.3" fill="{{ $color }}"><animateMotion dur="1.5s" begin="-{{ $k }}s" repeatCount="indefinite"><mpath href="#gos-spoke-{{ $i }}"/></animateMotion></circle>
                    @endforeach
                </g>
            @endforeach
            <defs><linearGradient id="gos-ring" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/></linearGradient></defs>
        </svg>

        {{-- engine nodes --}}
        @foreach ($engines as $i => [$name, $tag, $icon, $color])
            @php [$x, $y] = $pos($i, 38); @endphp
            <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
                <div class="flex flex-col items-center">
                    <span class="relative grid size-10 place-items-center rounded-2xl bg-white text-navy-500 shadow-[var(--shadow-card)] ring-1 ring-line sm:size-14">
                        <x-glyph :name="$icon" class="size-4 sm:size-6" />
                        <span class="gos-node absolute inset-0 grid place-items-center rounded-2xl text-white" style="background: {{ $color }}; box-shadow: 0 0 0 4px {{ $color }}33, 0 12px 30px {{ $color }}66; animation-delay: {{ $i * 3 - 18 }}s">
                            <x-glyph :name="$icon" class="size-4 sm:size-6" />
                        </span>
                    </span>
                    <span class="mt-1 rounded-full bg-white/90 px-1.5 text-[10px] font-bold whitespace-nowrap text-ink shadow-xs sm:mt-1.5 sm:px-2 sm:text-xs">{{ $name }}</span>
                </div>
            </div>
        @endforeach

        {{-- the core --}}
        <div class="absolute top-1/2 left-1/2 w-[34%] -translate-x-1/2 -translate-y-1/2 text-center">
            <div class="orbit-core relative mx-auto grid aspect-square w-[78%] place-items-center rounded-[28%] border border-white/15 bg-gradient-to-br from-navy-700 to-navy-950">
                <span class="scn-pulse absolute inset-0 rounded-[28%] border-2 border-ai-400/60" style="--t:3s"></span>
                <span class="text-center">
                    <span class="block text-[9px] font-semibold tracking-[0.18em] text-signal-400 uppercase sm:text-[10px]">Growth</span>
                    <span class="block text-sm leading-none font-extrabold text-white sm:text-lg">OS</span>
                </span>
            </div>
            <div class="mt-2 rounded-full bg-white/90 p-1 shadow-xs">
                <div class="flex items-center justify-between px-1 text-[8px] font-bold text-growth-700 sm:text-[10px]"><span>Revenue</span><x-glyph name="trending-up" class="size-3" /></div>
                <div class="mt-0.5 h-1.5 overflow-hidden rounded-full bg-growth-100"><div class="gos-revenue h-full rounded-full bg-growth-600"></div></div>
            </div>
        </div>
    </div>

    {{-- ====== LIVE SCENE CARD (one per engine, cycling) ====== --}}
    <div class="relative -mt-2 h-[8.75rem] w-full max-w-[26rem] sm:-mt-4 sm:h-[9.25rem]" aria-hidden="true">
        @foreach ($engines as $i => [$name, $tag, $icon, $color])
            <div class="gos-card absolute inset-0 overflow-hidden rounded-2xl border border-white bg-white/95 p-3.5 shadow-[var(--shadow-lift)] backdrop-blur sm:p-4" style="animation-delay: {{ $i * 3 - 18 }}s">
                <div class="flex items-center justify-between gap-2">
                    <span class="flex min-w-0 items-center gap-2">
                        <span class="grid size-7 shrink-0 place-items-center rounded-lg text-white" style="background: {{ $color }}"><x-glyph :name="$icon" class="size-4" /></span>
                        <span class="min-w-0">
                            <span class="block truncate text-[13px] leading-tight font-bold text-ink">{{ $name }}</span>
                            <span class="block text-[11px] leading-tight font-semibold" style="color: {{ $color }}">{{ $tag }}</span>
                        </span>
                    </span>
                    <span class="flex shrink-0 items-center gap-1 rounded-full bg-canvas px-2 py-0.5 text-[10px] font-semibold text-muted"><span class="size-1.5 animate-pulse rounded-full" style="background: {{ $color }}"></span>live</span>
                </div>

                <div class="mt-3 text-[11px]">
                    @switch($i)
                        @case(0) {{-- AI Search: an assistant cites you --}}
                            <div class="flex items-start gap-2">
                                <span class="grid size-6 shrink-0 place-items-center rounded-full bg-gradient-to-br from-ai-500 to-signal-500 text-white"><x-glyph name="sparkles" class="size-3.5" /></span>
                                <p class="rounded-xl rounded-tl-sm bg-canvas px-2.5 py-1.5 leading-snug text-body">Teams often shortlist <mark class="rounded bg-ai-50 px-1 font-bold text-ai-600">Your Company</mark> for this… <span class="ml-1 rounded border border-ai-200 bg-ai-50 px-1 text-[9px] font-semibold text-ai-600">[1] yourcompany.com</span></p>
                            </div>
                            @break
                        @case(1) {{-- Demand: ad → lead --}}
                            <div class="flex items-center gap-2">
                                <div class="min-w-0 flex-1 rounded-lg border border-amber-200 bg-amber-50/60 px-2.5 py-1.5">
                                    <span class="text-[9px] font-bold text-amber-700 uppercase">Sponsored</span>
                                    <p class="truncate font-semibold text-brand-700">Pipeline-focused B2B growth</p>
                                </div>
                                <x-glyph name="arrow-right" class="size-4 shrink-0 text-muted" />
                                <span class="flex shrink-0 items-center gap-1 rounded-lg bg-growth-50 px-2 py-1.5 font-bold text-growth-700"><x-glyph name="check-circle" class="size-4" /> Lead</span>
                            </div>
                            @break
                        @case(2) {{-- Authority: press feature --}}
                            <div class="rounded-lg bg-[#f4f1ea] px-2.5 py-1.5 text-navy-900">
                                <p class="flex justify-between border-b border-navy-900/10 pb-1 font-serif text-[12px] font-black">Industry Weekly <span class="font-sans text-[9px] font-normal text-navy-400">Opinion</span></p>
                                <p class="mt-1 truncate">The new playbook for growth — <mark class="rounded bg-ai-200 px-1 font-bold">Your Company</mark> explains</p>
                            </div>
                            @break
                        @case(3) {{-- Conversion: A/B --}}
                            <div class="space-y-1.5">
                                @foreach (['A' => [44, 'bg-navy-300'], 'B' => [86, 'bg-growth-600']] as $v => [$w, $tone])
                                    <div class="flex items-center gap-2">
                                        <span class="w-14 font-semibold text-body">Variant {{ $v }}</span>
                                        <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-canvas"><div class="gos-bar h-full rounded-full {{ $tone }}" style="width: {{ $w }}%; animation-delay: {{ $i * 3 - 18 }}s"></div></div>
                                        @if ($v === 'B')<span class="text-[10px] font-bold text-growth-700">wins</span>@else<span class="w-[26px]"></span>@endif
                                    </div>
                                @endforeach
                            </div>
                            @break
                        @case(4) {{-- Automation: lead routed --}}
                            <div class="flex items-center gap-1.5 overflow-hidden">
                                @foreach (['New lead', 'Enriched', 'Routed', 'Follow-up sent'] as $s => $step)
                                    <span class="shrink-0 rounded-md px-2 py-1 font-semibold {{ $s === 3 ? 'bg-growth-50 text-growth-700' : 'bg-ai-50 text-ai-600' }}">{{ $step }}</span>
                                    @unless ($loop->last)<x-glyph name="chevron-right" class="size-3 shrink-0 text-muted" />@endunless
                                @endforeach
                            </div>
                            @break
                        @default {{-- Intelligence: pipeline → revenue --}}
                            <div class="flex items-end gap-3">
                                <svg class="h-12 flex-1" viewBox="0 0 160 48" preserveAspectRatio="none" fill="none">
                                    <path d="M0 42 C 20 40, 30 34, 48 34 S 80 22, 100 20 S 136 8, 160 4" stroke="#16A34A" stroke-width="2.5" stroke-linecap="round" class="gos-draw" style="animation-delay: {{ $i * 3 - 18 }}s" vector-effect="non-scaling-stroke"/>
                                </svg>
                                <span class="shrink-0 text-right"><span class="block text-[10px] text-muted">Pipeline → revenue</span><span class="block font-bold text-growth-700">attributed</span></span>
                            </div>
                    @endswitch
                </div>
            </div>
        @endforeach
    </div>
    <p class="mt-2 text-center text-[10px] text-muted">Illustrative · six engines, one growth system</p>
</div>
