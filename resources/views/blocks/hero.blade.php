@php
    $visual = $data['visual'] ?? 'none';
    $points = array_filter((array) ($data['trust_points'] ?? []));
@endphp
<section class="bg-hero relative overflow-hidden {{ $visual === 'dashboard' ? 'pt-12 pb-16 sm:pt-16 lg:pt-20 lg:pb-24' : 'pt-12 pb-16 sm:pt-16 lg:pt-20 lg:pb-20' }}">
    <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_top_right,black,transparent_70%)]" aria-hidden="true"></div>

    <div class="container-x relative">
        @unless ($isHome)
            <x-breadcrumbs class="mb-8" />
        @endunless

        <div @class(['grid items-center gap-12 lg:gap-14', 'lg:grid-cols-[1.02fr_1fr]' => $visual === 'dashboard'])>
            <div @class(['max-w-4xl' => $visual !== 'dashboard'])>
                @if (! empty($data['eyebrow']))
                    <p class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white/80 py-1 pr-3.5 pl-1.5 text-xs font-semibold text-navy-700 shadow-xs backdrop-blur">
                        <span class="rounded-full bg-navy-900 px-2 py-0.5 text-[10px] font-bold tracking-wider text-white uppercase">{{ $data['eyebrow_tag'] ?? 'New' }}</span>
                        {{ $data['eyebrow'] }}
                    </p>
                @endif

                <h1 class="{{ $isHome ? 'h-display' : 'h-page' }} mt-6">{!! $data['headline'] ?? '' !!}</h1>

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
            </div>

            @if ($visual === 'dashboard')
                <x-growth-dashboard :data="array_filter(['scores' => $data['dashboard_scores'] ?? null, 'funnel' => $data['dashboard_funnel'] ?? null])" />
            @endif
        </div>

        @if ($visual === 'signal')
            <x-signal class="mt-14 max-w-5xl" />
        @endif
    </div>
</section>
