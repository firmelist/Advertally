@props(['center', 'nodes' => []])
@php
    // Card anchor positions (desktop) and the matching line end-points in the 100×100 SVG space.
    $positions = ['sm:left-0 sm:top-0', 'sm:right-0 sm:top-[4%]', 'sm:left-0 sm:bottom-[4%]', 'sm:right-0 sm:bottom-0'];
    $lineEnds = [[24, 20], [76, 23], [24, 77], [76, 80]];
    $tones = [
        ['bg-brand-500/15 text-brand-300 ring-brand-400/30', 'from-brand-400 to-signal-400'],
        ['bg-ai-500/15 text-ai-200 ring-ai-400/30', 'from-ai-400 to-brand-400'],
        ['bg-signal-500/15 text-signal-200 ring-signal-400/30', 'from-signal-400 to-growth-600'],
        ['bg-ai-500/15 text-ai-200 ring-ai-400/30', 'from-ai-500 to-ai-400'],
    ];
@endphp
<figure {{ $attributes->merge(['class' => 'orbit-panel relative overflow-hidden rounded-[2rem] p-4 shadow-[var(--shadow-glow)] sm:p-6']) }}
    aria-label="{{ $center['label'] }}: {{ collect($nodes)->pluck('title')->implode(', ') }}">
    <div class="orbit-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>

    <div class="relative mx-auto w-full sm:aspect-square sm:max-w-[34rem]">
        {{-- orbit ring, inner circles and signal lines (desktop) --}}
        <svg class="pointer-events-none absolute inset-0 hidden size-full sm:block" viewBox="0 0 100 100" fill="none" aria-hidden="true">
            <circle cx="50" cy="50" r="27" stroke="rgba(255,255,255,.07)" stroke-width=".25"/>
            <circle cx="50" cy="50" r="18" stroke="rgba(255,255,255,.06)" stroke-width=".2" stroke-dasharray=".6 1.2"/>
            <g class="orbit-ring">
                <circle cx="50" cy="50" r="38" stroke="url(#orbit-ring-g)" stroke-width=".45" stroke-linecap="round" stroke-dasharray="1.2 3.2"/>
            </g>
            @foreach ($lineEnds as $i => [$x, $y])
                @if (isset($nodes[$i]))
                    <line x1="50" y1="50" x2="{{ $x }}" y2="{{ $y }}" stroke="rgba(255,255,255,.08)" stroke-width=".35"/>
                    <line x1="50" y1="50" x2="{{ $x }}" y2="{{ $y }}" stroke="url(#orbit-line-g)" stroke-width=".5" stroke-linecap="round" class="signal-line" style="stroke-dasharray: 1.6 2.4; animation-duration: {{ 2.6 + $i * .4 }}s"/>
                @endif
            @endforeach
            <defs>
                <linearGradient id="orbit-ring-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/>
                </linearGradient>
                <linearGradient id="orbit-line-g" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#818CF8"/><stop offset="1" stop-color="#A78BFA"/>
                </linearGradient>
            </defs>
        </svg>

        {{-- centre --}}
        <div class="relative z-10 mx-auto mb-4 flex w-fit flex-col items-center sm:absolute sm:inset-0 sm:m-auto sm:h-fit">
            <div class="orbit-core grid size-20 place-items-center rounded-[1.6rem] border border-white/15 bg-gradient-to-br from-navy-700 to-navy-950 sm:size-28 sm:rounded-[2rem]">
                <x-glyph :name="$center['icon']" class="size-9 text-brand-300 sm:size-12" stroke="1.5" />
            </div>
            <span class="mt-3 max-w-[11rem] text-center text-xs font-bold tracking-wide text-white/90 sm:text-sm">{{ $center['label'] }}</span>
        </div>

        {{-- cards --}}
        <div class="relative z-20 grid grid-cols-2 gap-3 sm:static">
            @foreach (array_slice($nodes, 0, 4) as $i => $node)
                @php [$iconTone, $barTone] = $tones[$i]; $widget = $node['widget'] ?? ['type' => 'bars']; $tag = $node['url'] ? 'a' : 'div'; @endphp
                <{{ $tag }} @if ($node['url']) href="{{ $node['url'] }}" @endif
                    class="orbit-card group block rounded-2xl border border-white/10 bg-navy-900/70 p-3.5 backdrop-blur-md transition hover:border-white/25 sm:absolute sm:w-[45%] sm:p-4 {{ $positions[$i] }}">
                    <div class="flex items-start gap-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl ring-1 {{ $iconTone }} sm:size-10">
                            <x-glyph :name="$node['icon']" class="size-[18px]" />
                        </span>
                        <span class="min-w-0">
                            <span class="block font-mono text-[10px] text-white/40">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="line-clamp-2 block text-sm leading-tight font-bold text-white sm:truncate sm:text-[15px]">{{ $node['title'] }}</span>
                        </span>
                    </div>
                    <p class="mt-2 hidden truncate text-xs text-navy-200 sm:block">{{ $node['subtitle'] }}</p>

                    {{-- mini widget --}}
                    <div class="mt-3 rounded-xl border border-white/10 bg-navy-950/70 px-3 py-2.5 text-[11px]" aria-hidden="true">
                        @switch($widget['type'])
                            @case('search')
                                <div class="flex items-center gap-2 text-navy-100">
                                    <x-glyph name="search" class="size-3.5 text-navy-300" />
                                    <span class="truncate"><span class="orbit-type">{{ $widget['query'] }}</span><span class="orbit-caret ml-px text-signal-400">|</span></span>
                                </div>
                                @break
                            @case('chips')
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($widget['items'] as $c => $chip)
                                        <span class="orbit-chip rounded-md px-1.5 py-0.5 font-semibold" style="animation-delay: {{ $c * 1.5 }}s">{{ $chip }}</span>
                                    @endforeach
                                </div>
                                @break
                            @case('check')
                                <ul class="space-y-1">
                                    @foreach ($widget['items'] as $c => $item)
                                        <li class="flex items-center gap-1.5 text-navy-100">
                                            <span class="orbit-check grid size-3.5 place-items-center rounded-full bg-growth-600" style="animation-delay: {{ $c * .6 }}s"><x-glyph name="check" class="size-2.5 text-white" stroke="3" /></span>
                                            <span class="truncate">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @break
                            @case('flow')
                                <div class="flex items-center gap-1 text-navy-100">
                                    @foreach ($widget['items'] as $c => $item)
                                        <span @class(['rounded-md px-1.5 py-0.5 font-semibold', 'bg-signal-500/20 text-signal-200' => $c === 1, 'bg-white/10' => $c !== 1])>{{ $item }}</span>
                                        @unless ($loop->last)<x-glyph name="arrow-right" class="size-3 text-signal-400" />@endunless
                                    @endforeach
                                </div>
                                @break
                            @case('avatars')
                                <div class="flex items-center gap-2">
                                    <span class="flex -space-x-1.5">
                                        @foreach (['bg-signal-500', 'bg-brand-500', 'bg-ai-500', 'bg-growth-600'] as $a => $tone)
                                            <span class="size-4 rounded-full ring-2 ring-navy-950 {{ $tone }}"></span>
                                        @endforeach
                                    </span>
                                    <span class="truncate font-semibold text-navy-100">{{ $widget['label'] }}</span>
                                </div>
                                @break
                            @case('code')
                                <div class="space-y-0.5 font-mono">
                                    <p class="truncate text-ai-200"><span class="text-brand-300">›</span> {{ $widget['lines'][0] ?? '' }}</p>
                                    <p class="truncate text-growth-100"><span class="orbit-check mr-1 inline-block size-1.5 rounded-full bg-growth-600 align-middle"></span>{{ $widget['lines'][1] ?? '' }}</p>
                                </div>
                                @break
                            @default
                                <div class="flex h-7 items-end gap-1">
                                    @foreach ([40, 55, 45, 70, 60, 85, 100] as $b => $h)
                                        <span class="orbit-bar w-full rounded-sm bg-gradient-to-t {{ $barTone }}" style="height: {{ $h }}%; animation-delay: {{ $b * .18 }}s"></span>
                                    @endforeach
                                </div>
                        @endswitch
                    </div>
                </{{ $tag }}>
            @endforeach
        </div>
    </div>
</figure>
