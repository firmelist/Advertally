@extends('layouts.app')

@php
    $chart = $caseStudy->chart;
    $chartMax = $chart ? max(1, ...array_map('floatval', (array) ($chart['values'] ?? [1]))) : 1;
@endphp

@section('content')
    <section class="bg-hero pt-10 pb-14 sm:pt-14 lg:pb-16">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="flex flex-wrap items-center gap-2">
                <span class="eyebrow">Growth Story</span>
                @if ($caseStudy->industry)<a href="{{ $caseStudy->industry->url() }}" class="chip">{{ $caseStudy->industry->name }}</a>@endif
                @if ($caseStudy->is_sample)<span class="sample-badge">Sample case study</span>@endif
            </div>
            <h1 class="h-page mt-5 max-w-4xl">{{ $caseStudy->title }}</h1>
            <p class="lead mt-6 max-w-3xl">{{ $caseStudy->summary }}</p>
            <p class="mt-6 text-sm font-semibold text-ink">Client: {{ $caseStudy->client_name }}</p>

            @if ($caseStudy->is_sample)
                <div class="mt-8 flex max-w-3xl gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900" role="note">
                    <x-glyph name="info" class="mt-0.5 size-5 shrink-0" />
                    <p><strong>This is a sample case study.</strong> It illustrates how Advertally structures a growth engagement. The company and figures are illustrative and do not describe a real client.</p>
                </div>
            @endif
        </div>
    </section>

    @if ($caseStudy->metrics->isNotEmpty())
        <section class="border-y border-line bg-white">
            <div class="container-x">
                <dl class="grid grid-cols-2 divide-line lg:grid-cols-4 lg:divide-x">
                    @foreach ($caseStudy->metrics as $metric)
                        <div class="py-8 lg:px-8 lg:first:pl-0">
                            <dt class="text-sm text-muted">{{ $metric->label }}</dt>
                            <dd class="mt-2 text-4xl font-extrabold text-growth-700 tabular-nums">{{ $metric->value }}</dd>
                            @if ($metric->before || $metric->after)
                                <dd class="mt-1 text-xs text-muted">{{ $metric->before }} → {{ $metric->after }}</dd>
                            @endif
                        </div>
                    @endforeach
                </dl>
                @if ($caseStudy->is_sample)<p class="pb-4 text-xs text-muted">Sample data — illustrative figures.</p>@endif
            </div>
        </section>
    @endif

    <section class="section bg-white">
        <div class="container-x grid gap-12 lg:grid-cols-[15rem_1fr] lg:gap-16">
            <nav class="hidden lg:block" aria-label="Story sections">
                <ol class="sticky top-28 space-y-2 border-l border-line text-sm">
                    @foreach (\App\Models\CaseStudy::SECTIONS as $field => $label)
                        @if (filled($caseStudy->{$field}))
                            <li><a href="#{{ $field }}" class="-ml-px block border-l-2 border-transparent pl-4 text-muted hover:border-brand-600 hover:text-ink">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            <div class="max-w-3xl space-y-14">
                @foreach (\App\Models\CaseStudy::SECTIONS as $field => $label)
                    @if (filled($caseStudy->{$field}))
                        <section id="{{ $field }}" class="scroll-mt-28">
                            <p class="font-mono text-xs font-bold text-brand-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h2 class="mt-2 text-2xl font-bold text-ink">{{ $label }}</h2>
                            <div class="prose-adv mt-4">{!! str($caseStudy->{$field})->sanitizeHtml() !!}</div>

                            @if ($field === 'results' && $chart && ! empty($chart['values']))
                                <figure class="card mt-8 p-6">
                                    <figcaption class="flex items-center justify-between text-sm font-bold text-ink">
                                        {{ $chart['title'] ?? 'Results over time' }}
                                        @if ($caseStudy->is_sample)<span class="sample-badge">Sample data</span>@endif
                                    </figcaption>
                                    <div class="mt-6 flex h-48 items-end gap-2 sm:gap-3" role="img" aria-label="{{ $chart['title'] ?? 'Chart' }}">
                                        @foreach ($chart['values'] as $i => $value)
                                            <div class="flex flex-1 flex-col items-center gap-2">
                                                <span class="text-[11px] font-semibold text-ink tabular-nums">{{ $value }}{{ $chart['unit'] ?? '' }}</span>
                                                <div class="w-full rounded-t-lg {{ $loop->last ? 'bg-growth-600' : 'bg-brand-600/80' }}" style="height: {{ max(4, (float) $value / $chartMax * 140) }}px"></div>
                                                <span class="text-[11px] text-muted">{{ $chart['labels'][$i] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </figure>
                            @endif
                        </section>
                    @endif
                @endforeach

                @if ($caseStudy->testimonial)
                    <figure class="card p-8">
                        <x-glyph name="quote" class="size-8 text-brand-200" />
                        <blockquote class="mt-4 text-lg leading-relaxed font-medium text-ink">“{{ $caseStudy->testimonial->quote }}”</blockquote>
                        <figcaption class="mt-5 text-sm text-muted"><strong class="text-ink">{{ $caseStudy->testimonial->name }}</strong>, {{ collect([$caseStudy->testimonial->role, $caseStudy->testimonial->company])->filter()->implode(', ') }}</figcaption>
                    </figure>
                @endif

                @if ($caseStudy->services->isNotEmpty())
                    <div>
                        <p class="text-sm font-bold text-ink">Engines used</p>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($caseStudy->services as $service)
                                <li><a href="{{ $service->url() }}" class="chip hover:border-brand-200 hover:text-brand-700">{{ $service->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($more->isNotEmpty())
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="More growth stories" title="Keep reading." />
                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    @foreach ($more as $item)
                        <x-cards.case-study :case-study="$item->loadMissing('industry', 'metrics')" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
@endsection
