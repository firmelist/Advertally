@extends('layouts.app')

@php
    $isTalent = $category->group === 'talent';
    $steps = $service->processSteps();
    $scene = \App\Support\Scenes::forService($service);
@endphp

@section('content')
    {{-- HERO --}}
    <section class="bg-hero relative overflow-hidden pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_1fr] lg:gap-14">
                <div>
                    <a href="{{ $category->url() }}" class="eyebrow hover:text-brand-800">
                        @if ($category->number)<span class="font-mono">{{ $category->number }}</span> — @endif{{ $category->name }}@if ($category->tagline) · {{ $category->tagline }}@endif
                    </a>
                    <h1 class="h-page mt-5">{{ $service->hero_title ?: $service->title }}</h1>
                    <p class="lead mt-6 max-w-2xl">{{ $service->hero_subtitle ?: $service->short_description }}</p>
                    @if ($isTalent)
                        <x-cta-buttons class="mt-9" primary-label="Discuss your requirement" primary-url="#enquire" :secondary-label="null" />
                    @else
                        <x-cta-buttons class="mt-9" />
                    @endif
                </div>
                <x-scene :scene="$scene" class="self-center" />
            </div>
        </div>
    </section>

    {{-- BODY + BENEFITS --}}
    <section class="section bg-white">
        <div class="container-x grid gap-12 lg:grid-cols-[1.2fr_1fr] lg:gap-20">
            <div class="prose-adv" data-reveal>{!! str($service->long_description)->sanitizeHtml() !!}</div>
            <div class="space-y-4">
                @if ($service->deliverables)
                    <aside class="card p-6 sm:p-8" aria-labelledby="included-title" data-reveal>
                        <p id="included-title" class="text-sm font-bold text-ink">{{ $isTalent ? 'What you get' : 'What is included' }}</p>
                        <ul class="mt-5 space-y-3">
                            @foreach ($service->deliverables as $item)
                                <li class="flex gap-3 text-[15px] text-body"><x-glyph name="check" class="mt-0.5 size-5 text-brand-600" /> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
                @if ($service->benefits)
                    <p class="eyebrow pt-4">Business outcomes</p>
                    @foreach ($service->benefits as $benefit)
                        <div class="card flex gap-4 p-6" data-reveal>
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-glyph name="target" /></span>
                            <div>
                                <h3 class="font-bold text-ink">{{ $benefit['title'] ?? '' }}</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $benefit['text'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- PROCESS --}}
    @if ($steps)
        <section class="bg-navy-field relative overflow-hidden py-20 sm:py-24">
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="container-x relative">
                <x-section-heading eyebrow="How it works" :title="'How we deliver '.$service->title.'.'" dark />
                <ol class="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($steps as $i => $step)
                        <li class="card-dark p-6" data-reveal>
                            <span class="font-mono text-xs font-bold text-signal-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="mt-3 text-lg font-bold text-white">{{ $step['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-navy-200">{{ $step['text'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- SEMANTIC LINKS: related services, industries, proof, research --}}
    @php
        $related = $service->related->isNotEmpty() ? $service->related : $siblings->take(3);
    @endphp
    <section class="section bg-canvas">
        <div class="container-x space-y-16">
            @if ($related->isNotEmpty())
                <div>
                    <x-section-heading eyebrow="Works best with" title="Connected services." />
                    <div class="mt-10 grid gap-5 md:grid-cols-3">
                        @foreach ($related->take(3) as $item)
                            <x-cards.service :service="$item" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($service->industries->isNotEmpty())
                <div>
                    <p class="text-sm font-bold text-ink">Industries where this matters most</p>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        @foreach ($service->industries as $industry)
                            <li><a href="{{ $industry->url() }}" class="chip hover:border-brand-200 hover:text-brand-700">{{ $industry->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($service->caseStudies->isNotEmpty())
                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ($service->caseStudies->take(2) as $caseStudy)
                        <x-cards.case-study :case-study="$caseStudy->loadMissing('industry', 'metrics')" />
                    @endforeach
                </div>
            @endif

            @if ($service->posts->isNotEmpty() || $service->research->isNotEmpty())
                <div>
                    <x-section-heading eyebrow="Research & insights" title="Go deeper." />
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        @foreach ($service->research->take(3) as $item)
                            <x-cards.research :research="$item" />
                        @endforeach
                        @foreach ($service->posts->take(3 - min(3, $service->research->count())) as $post)
                            <x-cards.post :post="$post->loadMissing('category')" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-faq :items="$service->faqs ?? []" />

    @if ($isTalent)
        @include('partials.talent-form')
    @else
        <x-cta-band />
    @endif
@endsection
