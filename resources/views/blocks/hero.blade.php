@php
    $visual = $data['visual'] ?? 'none';
    $points = array_filter((array) ($data['trust_points'] ?? []));
    $sideVisual = in_array($visual, ['dashboard', 'evolution', 'cycle', 'constellation'], true);
    // *words* in the CMS headline get the animated gradient treatment; everything else is escaped.
    $headline = preg_replace('/\*(.+?)\*/u', '<span class="text-gradient-anim">$1</span>', e($data['headline'] ?? ''));
@endphp
<section class="bg-hero {{ $visual === 'dashboard' ? 'pt-12 pb-20 sm:pt-16 lg:pt-20 lg:pb-28' : 'pt-12 pb-16 sm:pt-16 lg:pt-20 lg:pb-20' }}">
    <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_top_right,black,transparent_70%)]" aria-hidden="true"></div>

    <div class="container-x relative">
        @unless ($isHome)
            <x-breadcrumbs class="mb-8" />
        @endunless

        {{-- min-w-0 stops wide content (the engine ticker) from stretching the column past the screen on phones --}}
        <div @class(['grid grid-cols-1 items-center gap-14 lg:gap-16', 'lg:grid-cols-[1.02fr_1fr]' => $sideVisual])>
            <div @class(['min-w-0', 'max-w-4xl' => ! $sideVisual])>
                @if (! empty($data['eyebrow']))
                    <p class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white/80 py-1 pr-3.5 pl-1.5 text-xs font-semibold text-navy-700 shadow-xs backdrop-blur" data-reveal>
                        <span class="relative rounded-full bg-navy-900 px-2 py-0.5 text-[10px] font-bold tracking-wider text-white uppercase">
                            <span class="absolute -top-0.5 -right-0.5 flex size-2"><span class="absolute inline-flex size-full animate-ping rounded-full bg-signal-400 opacity-75"></span><span class="relative inline-flex size-2 rounded-full bg-signal-400"></span></span>
                            {{ $data['eyebrow_tag'] ?? 'New' }}
                        </span>
                        {{ $data['eyebrow'] }}
                    </p>
                @endif

                <h1 class="{{ $isHome ? 'h-display' : 'h-page' }} mt-6">{!! $headline !!}</h1>

                @if (! empty($data['subheadline']))
                    <p class="lead mt-6 max-w-2xl">{{ $data['subheadline'] }}</p>
                @endif

                @if (! empty($data['primary_label']))
                    <x-cta-buttons class="mt-9"
                        :primary-label="$data['primary_label']" :primary-url="$data['primary_url'] ?? null"
                        :secondary-label="$data['secondary_label'] ?? null" :secondary-url="$data['secondary_url'] ?? null" />
                @endif

                @if ($points)
                    <ul class="mt-10 flex flex-wrap gap-x-6 gap-y-3">
                        @foreach ($points as $point)
                            <li class="flex items-center gap-2 text-sm font-semibold text-navy-700">
                                <x-glyph name="check-circle" class="size-[18px] text-brand-600" /> {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($isHome)
                    {{-- the six engines, gliding past --}}
                    <div class="relative mt-12 max-w-xl overflow-hidden [mask-image:linear-gradient(90deg,transparent,black_12%,black_88%,transparent)]" aria-label="Advertally Growth OS engines">
                        <div class="flex w-max gap-2" style="animation: scn-conveyor 26s linear infinite">
                            @foreach ([1, 2] as $copy)
                                @foreach ([['AI Search', 'search'], ['Demand', 'megaphone'], ['Authority', 'shield-check'], ['Conversion', 'pointer'], ['Automation', 'workflow'], ['Intelligence', 'bar-chart']] as [$engine, $icon])
                                    <span class="flex items-center gap-1.5 rounded-full border border-line bg-white/80 px-3 py-1.5 text-xs font-semibold whitespace-nowrap text-navy-700 backdrop-blur" @if ($copy === 2) aria-hidden="true" @endif>
                                        <x-glyph :name="$icon" class="size-3.5 text-brand-600" /> {{ $engine }}
                                    </span>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            @switch($visual)
                @case('dashboard')
                    <x-hero-command-center class="mx-auto w-full max-w-[36rem] lg:mr-0" :data="array_filter(['scores' => $data['dashboard_scores'] ?? null, 'funnel' => $data['dashboard_funnel'] ?? null])" />
                    @break
                @case('evolution')
                    <x-hero.evolution />
                    @break
                @case('cycle')
                    <x-hero.cycle />
                    @break
                @case('constellation')
                    <x-hero.constellation />
                    @break
            @endswitch
        </div>

        @if ($visual === 'signal')
            <x-signal class="mt-14 max-w-5xl" />
        @endif
    </div>
</section>
