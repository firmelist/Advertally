@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="max-w-4xl">
                <p class="eyebrow"><x-glyph :name="$industry->icon ?: 'building'" class="size-4" /> {{ $industry->name }}</p>
                <h1 class="h-page mt-5">{{ $industry->headline ?: "Growth for {$industry->name}" }}</h1>
                <p class="lead mt-6">{{ $industry->summary }}</p>
                <x-cta-buttons class="mt-9" />
            </div>
        </div>
    </section>

    {{-- How buyers search + AI --}}
    <section class="section bg-white">
        <div class="container-x grid gap-6 lg:grid-cols-2">
            <div class="card p-7 sm:p-9" data-reveal>
                <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-glyph name="search" /></span>
                <h2 class="mt-5 text-2xl font-bold text-ink">How your customers search</h2>
                <p class="mt-3 leading-relaxed text-muted">{{ $industry->how_customers_search }}</p>
            </div>
            <div class="card relative overflow-hidden p-7 sm:p-9" data-reveal>
                <div class="absolute -top-16 -right-16 size-56 rounded-full bg-ai-50 blur-2xl" aria-hidden="true"></div>
                <span class="relative grid size-11 place-items-center rounded-xl bg-ai-50 text-ai-600"><x-glyph name="sparkles" /></span>
                <h2 class="relative mt-5 text-2xl font-bold text-ink">How AI is changing discovery</h2>
                <p class="relative mt-3 leading-relaxed text-muted">{{ $industry->ai_discovery }}</p>
            </div>
        </div>
    </section>

    {{-- Challenges + opportunities --}}
    <section class="section bg-canvas">
        <div class="container-x grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
            <div>
                <x-section-heading eyebrow="Typical acquisition challenges" title="What usually holds growth back." />
                <ul class="mt-8 space-y-3">
                    @foreach ((array) $industry->challenges as $challenge)
                        <li class="flex gap-3 rounded-xl border border-line bg-white px-4 py-3.5 text-[15px] text-ink" data-reveal>
                            <x-glyph name="alert" class="mt-0.5 size-5 text-ai-600" /> {{ $challenge }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <x-section-heading eyebrow="Growth opportunities" title="Where the upside is." />
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach ((array) $industry->opportunities as $o)
                        <div class="card p-6" data-reveal>
                            <x-glyph name="trending-up" class="size-5 text-growth-600" />
                            <h3 class="mt-3 font-bold text-ink">{{ $o['title'] ?? '' }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $o['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Example growth system --}}
    @if ($industry->growth_system)
        <section class="bg-navy-field relative overflow-hidden py-20 sm:py-24">
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="container-x relative">
                <x-section-heading eyebrow="Example growth system" :title="'A connected system for '.$industry->name.'.'" dark />
                <ol @class(['mt-12 grid gap-4 md:grid-cols-2', 'lg:grid-cols-5' => count($industry->growth_system) >= 5, 'lg:grid-cols-4' => count($industry->growth_system) < 5])>
                    @foreach ($industry->growth_system as $i => $step)
                        <li class="card-dark relative p-6" data-reveal>
                            <span class="font-mono text-xs font-bold text-signal-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="mt-3 font-bold text-white">{{ $step['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-navy-200">{{ $step['text'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- Relevant solutions --}}
    @if ($industry->services->isNotEmpty())
        <section class="section bg-white">
            <div class="container-x">
                <x-section-heading eyebrow="Relevant Advertally solutions" title="The engines that matter most here." />
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($industry->services as $service)
                        <x-cards.service :service="$service" data-reveal />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($industry->caseStudies->isNotEmpty() || $industry->posts->isNotEmpty())
        <section class="section bg-canvas">
            <div class="container-x grid gap-6 md:grid-cols-3">
                @foreach ($industry->caseStudies->take(1) as $caseStudy)
                    <x-cards.case-study :case-study="$caseStudy->loadMissing('industry', 'metrics')" class="md:col-span-1" />
                @endforeach
                @foreach ($industry->posts->take(2) as $post)
                    <x-cards.post :post="$post->loadMissing('category')" />
                @endforeach
            </div>
        </section>
    @endif

    <x-faq :items="$industry->faqs ?? []" />

    <section class="section-tight border-t border-line bg-white">
        <div class="container-x">
            <p class="text-sm font-bold text-ink">Other industries</p>
            <ul class="mt-4 flex flex-wrap gap-2">
                @foreach ($others as $other)
                    <li><a href="{{ $other->url() }}" class="chip hover:border-brand-200 hover:text-brand-700">{{ $other->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </section>

    <x-cta-band :title="'Find out where '.$industry->name.' growth is leaking.'" />
@endsection
