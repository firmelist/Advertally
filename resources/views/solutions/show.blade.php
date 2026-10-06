@extends('layouts.app')

@php
    $isTalent = $category->group === 'talent';
    $flow = array_values(array_filter((array) $category->flow));
    $metrics = array_values(array_filter((array) $category->metrics));
@endphp

@section('content')
    {{-- HERO --}}
    <section class="bg-hero relative overflow-hidden pt-10 pb-16 sm:pt-14 lg:pb-24">
        <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_top_right,black,transparent_70%)]" aria-hidden="true"></div>
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_1fr] lg:gap-16">
                <div>
                    <p class="eyebrow">
                        @if ($category->group === 'solution')
                            <span class="font-mono">{{ $category->number }}</span> — {{ $category->name }} · {{ $category->tagline }}
                        @else
                            {{ $isTalent ? 'Advertally Technology & Talent' : 'Growth Technology' }}
                        @endif
                    </p>
                    <h1 class="h-page mt-5">{{ $category->headline ?: $category->name }}</h1>
                    @if ($category->subheadline)
                        <p class="lead mt-6 max-w-2xl">{{ $category->subheadline }}</p>
                    @endif
                    @if ($isTalent)
                        <x-cta-buttons class="mt-9" :primary-label="$category->cta_label ?: 'Discuss your requirement'" primary-url="#enquire" secondary-label="See how we work" :secondary-url="url('approach')" />
                    @else
                        <x-cta-buttons class="mt-9" :primary-label="$category->cta_label ?: 'Get Your Growth Score'" :primary-url="$category->cta_url ? url($category->cta_url) : null" />
                    @endif
                </div>

                {{-- visual --}}
                <div data-reveal>
                    @if ($flow)
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between border-b border-line px-6 py-4">
                                <p class="text-sm font-bold text-ink">{{ $category->flow_title ?: 'How it compounds' }}</p>
                                <x-glyph name="trending-up" class="size-5 text-growth-600" />
                            </div>
                            <ol class="space-y-0 p-6">
                                @foreach ($flow as $i => $step)
                                    <li class="relative flex items-center gap-4 pb-5 last:pb-0">
                                        @unless ($loop->last)<span class="absolute top-9 bottom-0 left-[17px] w-0.5 bg-gradient-to-b from-brand-200 to-brand-100" aria-hidden="true"></span>@endunless
                                        <span @class([
                                            'relative grid size-9 shrink-0 place-items-center rounded-xl text-xs font-bold',
                                            'bg-growth-600 text-white' => $loop->last,
                                            'bg-navy-900 text-white' => $loop->first && ! $loop->last,
                                            'bg-brand-50 text-brand-700' => ! $loop->first && ! $loop->last,
                                        ])>{{ $i + 1 }}</span>
                                        <span @class(['text-[15px] font-semibold', 'text-growth-700' => $loop->last, 'text-ink' => ! $loop->last])>{{ $step }}</span>
                                        <span class="ml-auto h-1.5 rounded-full {{ $loop->last ? 'bg-growth-600' : 'bg-brand-200' }}" style="width: {{ max(12, 100 - $i * (80 / max(1, count($flow) - 1))) }}px" aria-hidden="true"></span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @elseif ($metrics)
                        <div class="card p-6 sm:p-8">
                            <p class="text-sm font-bold text-ink">{{ $category->metrics_title }}</p>
                            <ul class="mt-5 grid grid-cols-2 gap-2.5">
                                @foreach ($metrics as $metric)
                                    <li class="flex items-center gap-2.5 rounded-xl border border-line px-3.5 py-3 text-sm font-semibold text-ink">
                                        <span class="size-2 rounded-full {{ $loop->index % 3 === 0 ? 'bg-ai-600' : ($loop->index % 3 === 1 ? 'bg-brand-600' : 'bg-signal-500') }}"></span> {{ $metric }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="card p-8"><x-signal /></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- PRINCIPLE --}}
    @if ($category->principle)
        <section class="border-y border-line bg-white">
            <div class="container-x py-10 sm:py-12">
                <p class="mx-auto max-w-4xl text-center text-xl leading-snug font-bold text-ink sm:text-2xl" data-reveal>“{{ $category->principle }}”</p>
            </div>
        </section>
    @endif

    {{-- INTRO + HIGHLIGHTS --}}
    @if ($category->intro || $category->highlights)
        <section class="section bg-white">
            <div @class(['grid gap-12 lg:grid-cols-[1fr_1.1fr] lg:gap-20 container-x' => $category->highlights, 'container-narrow' => ! $category->highlights])>
                <div class="prose-adv lg:sticky lg:top-28 lg:self-start" data-reveal>{!! str($category->intro)->sanitizeHtml() !!}</div>
                @if ($category->highlights)
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($category->highlights as $h)
                            <div class="card p-6" data-reveal>
                                <h3 class="font-bold text-ink">{{ $h['title'] ?? '' }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-muted">{{ $h['text'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- SERVICES --}}
    @if ($category->services->isNotEmpty())
        <section class="section bg-canvas" aria-labelledby="services-title">
            <div class="container-x">
                <x-section-heading
                    :eyebrow="$isTalent ? 'Ways to extend your team' : 'Services'"
                    :title="$isTalent ? 'Specialists who plug into your growth system.' : 'What we build inside '.$category->name.'.'"
                    id="services-title" />
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($category->services as $service)
                        <x-cards.service :service="$service" data-reveal />
                    @endforeach
                </div>
                @php $extra = array_diff((array) $category->capabilities, $category->services->pluck('title')->all()); @endphp
                @if ($extra)
                    <div class="mt-10" data-reveal>
                        <p class="text-sm font-semibold text-ink">Also part of this engine</p>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($extra as $capability)
                                <li class="chip">{{ $capability }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- METRICS (when the hero used the flow) --}}
    @if ($flow && $metrics)
        <section class="bg-navy-field relative overflow-hidden py-20 sm:py-24">
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="container-x relative">
                <x-section-heading eyebrow="Measured on what matters" :title="$category->metrics_title ?: 'The numbers we report on.'" dark />
                <ul @class(['mt-12 grid grid-cols-2 gap-4 md:grid-cols-3', 'lg:grid-cols-4' => count($metrics) > 6])>
                    @foreach ($metrics as $metric)
                        <li class="card-dark p-5" data-reveal>
                            <span class="font-mono text-[11px] font-semibold text-signal-400">KPI {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <p class="mt-2 text-lg font-bold text-white">{{ $metric }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- PROCESS --}}
    @if ($category->process)
        <section class="section bg-white">
            <div class="container-x">
                <x-section-heading eyebrow="How we deliver" title="From diagnosis to compounding results." />
                <ol class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($category->process as $i => $step)
                        <li class="card relative p-6" data-reveal>
                            <span class="font-mono text-xs font-bold text-brand-600">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="mt-3 text-lg font-bold text-ink">{{ $step['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted">{{ $step['text'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- OTHER ENGINES --}}
    @if ($engines->isNotEmpty())
        <section class="section-tight border-t border-line bg-canvas">
            <div class="container-x">
                <p class="text-sm font-bold text-ink">One Growth System. Six Engines.</p>
                <ul class="mt-5 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-6">
                    @foreach ($engines as $engine)
                        <li>
                            <a href="{{ $engine->url() }}" @class([
                                'flex h-full flex-col rounded-xl border px-4 py-3 transition',
                                'border-brand-600 bg-brand-600 text-white' => $engine->is($category),
                                'border-line bg-white hover:border-brand-200' => ! $engine->is($category),
                            ]) @if ($engine->is($category)) aria-current="page" @endif>
                                <span class="font-mono text-[11px] {{ $engine->is($category) ? 'text-brand-100' : 'text-brand-600' }}">{{ $engine->number }}</span>
                                <span class="text-sm font-bold">{{ $engine->name }}</span>
                                <span class="text-xs {{ $engine->is($category) ? 'text-brand-100' : 'text-muted' }}">{{ $engine->tagline }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- PROOF & THINKING --}}
    @if ($caseStudies->isNotEmpty() || $posts->isNotEmpty())
        <section class="section bg-white">
            <div class="container-x space-y-16">
                @if ($caseStudies->isNotEmpty())
                    <div>
                        <x-section-heading eyebrow="Growth Stories" title="See the system at work." />
                        <div class="mt-10 grid gap-6 lg:grid-cols-2">
                            @foreach ($caseStudies as $caseStudy)
                                <x-cards.case-study :case-study="$caseStudy->loadMissing('industry', 'metrics')" />
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($posts->isNotEmpty())
                    <div>
                        <x-section-heading eyebrow="Insights" title="Related thinking." />
                        <div class="mt-10 grid gap-6 md:grid-cols-3">
                            @foreach ($posts as $post)
                                <x-cards.post :post="$post->loadMissing('category')" />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <x-faq :items="$category->faqs ?? []" />

    @if ($isTalent)
        @include('partials.talent-form')
    @else
        <x-cta-band />
    @endif
@endsection
