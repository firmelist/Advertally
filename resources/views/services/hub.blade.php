@extends('layouts.app')

@section('title', $service->meta_title ?: $service->title.' | Advertally')
@section('description', $service->meta_description ?: $service->short_description)
@section('whatsapp_context', $service->title)

@php
    $crumbs = [['label' => 'Home', 'url' => route('home')], ['label' => $service->title]];
    $isHire = $service->pillar === 'scale';
    $pillarService = ['grow' => [], 'build' => ['website'], 'scale' => ['hire'], 'automate' => ['crm'], 'transform' => ['custom_software']][$service->pillar] ?? [];
@endphp

@push('schema')
<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->title,
        'description' => $service->short_description,
        'provider' => ['@type' => 'Organization', 'name' => setting('company_name', 'Advertally'), 'url' => url('/')],
        'areaServed' => 'IN',
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => $service->title,
            'itemListElement' => $service->children->map(fn ($c) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $c->title], 'priceCurrency' => 'INR', 'price' => $c->starting_price])->values(),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

@include('services._hero', [
    'formType' => $isHire ? 'hire' : 'quote',
    'formVariant' => 'compact',
    'formServices' => $pillarService,
    'formButton' => $isHire ? 'Request Profiles' : 'Get My Free Proposal',
])

{{-- Child services grid --}}
<section class="section">
    <div class="container-x">
        <x-section-heading :eyebrow="$service->title" :title="$isHire ? 'Who do you want to hire?' : 'Services under '.$service->title" />
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($service->children as $child)
                <a href="{{ route('services.show', [$service->slug, $child->slug]) }}" class="card-hover group flex flex-col p-6">
                    <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-lucide :name="$child->icon" class="size-5" /></span>
                    <h3 class="mt-5 font-sans text-lg font-bold">{{ $child->title }}</h3>
                    <p class="mt-2 flex-1 text-sm text-muted">{{ $child->short_description }}</p>
                    <div class="mt-5 flex items-center justify-between border-t border-line pt-4 text-sm">
                        @if ($child->starting_price)
                            <span class="text-muted">From <strong class="text-ink">{{ inr($child->starting_price) }}</strong>{{ ['month' => '/mo', 'hour' => '/hr'][$child->price_unit] ?? '' }}</span>
                        @else <span></span> @endif
                        <span class="inline-flex items-center gap-1 font-semibold text-brand-700">Details <x-lucide name="arrow-right" class="size-4" /></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Hire: engagement models & rate card --}}
@if ($isHire && $service->rate_card)
<section class="section bg-brand-950">
    <div class="container-x">
        <x-section-heading dark eyebrow="Engagement models" title="Flexible ways to hire" subtitle="All models include a 7-day risk-free trial, NDA, daily reporting and free replacement." />
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($service->rate_card as $rc)
                <div class="rounded-[var(--radius-card)] border border-white/10 bg-white/5 p-6 text-white">
                    <p class="text-sm font-semibold text-accent-300">{{ $rc['model'] }}</p>
                    <p class="mt-3 font-display text-3xl font-extrabold">{{ $rc['price'] ? inr($rc['price']) : 'Custom' }}<span class="text-sm font-medium text-white/60">{{ $rc['price'] ? '/'.$rc['unit'] : '' }}</span></p>
                    <p class="mt-2 text-sm text-white/70">{{ $rc['note'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['shield', '7-day risk-free trial'], ['lock', 'NDA & full IP ownership'], ['file-text', 'Daily work reports'], ['refresh', 'Free replacement']] as [$ic, $txt])
                <div class="flex items-center gap-3 text-sm text-white/80"><x-lucide :name="$ic" class="size-5 text-accent-400" /> {{ $txt }}</div>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <x-section-heading align="left" eyebrow="Request profiles" title="Get 2–3 matching profiles in 48 hours" subtitle="Share your requirement. We'll send pre-vetted profiles you can interview this week." />
        </div>
        <div class="card p-6 sm:p-8 lg:col-span-7">
            <x-lead-form form-type="hire" form-id="hire-full" variant="hire" button="Request Profiles" />
        </div>
    </div>
</section>
@else
    @include('services._sections', ['service' => $service])
@endif

{{-- Packages --}}
@if ($plans->isNotEmpty())
<section class="section {{ $isHire ? 'bg-canvas' : '' }}">
    <div class="container-x">
        <x-section-heading eyebrow="Pricing" title="Transparent packages" subtitle="All prices in ₹, exclusive of GST. Custom quotes available." />
        <div class="mx-auto mt-14 grid max-w-6xl gap-6 lg:grid-cols-3 lg:items-center">
            @foreach ($plans as $plan)<x-pricing-card :plan="$plan" />@endforeach
        </div>
    </div>
</section>
@endif

{{-- Case study + testimonial --}}
@if ($caseStudy)
<section class="section bg-canvas">
    <div class="container-x grid items-center gap-10 lg:grid-cols-2">
        <div>
            <x-section-heading align="left" eyebrow="Case study" :title="$caseStudy->title" :subtitle="$caseStudy->summary" />
            <a href="{{ route('case-studies.show', $caseStudy) }}" class="btn-ghost mt-8">Read the full story <x-lucide name="arrow-right" class="size-4" /></a>
        </div>
        <div class="grid grid-cols-3 gap-4">
            @foreach (collect($caseStudy->results)->take(3) as $r)
                <div class="card p-5 text-center">
                    <p class="font-display text-3xl font-extrabold text-brand-600">{{ $r['value'] }}</p>
                    <p class="mt-2 text-xs text-muted">{{ $r['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-faq :faqs="$faqs" />

@include('services._upsell', ['next' => $next])

<x-cta-band :context="$service->title" />

@endsection
