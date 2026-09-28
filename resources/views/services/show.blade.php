@extends('layouts.app')

@section('title', $service->meta_title ?: $service->title.' | Advertally')
@section('description', $service->meta_description ?: $service->short_description)
@section('whatsapp_context', $service->title)

@php
    $crumbs = [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $parent->title, 'url' => $parent->url],
        ['label' => $service->title],
    ];
    $isHire = $service->pillar === 'scale';
    $serviceKey = [
        'seo' => 'seo', 'google-ads' => 'google_ads', 'meta-ads' => 'meta_ads', 'social-media-management' => 'social_media',
        'whatsapp-marketing' => 'whatsapp_marketing',
    ][$service->slug] ?? (['build' => 'website', 'scale' => 'hire', 'automate' => 'crm', 'transform' => 'custom_software'][$service->pillar] ?? null);
@endphp

@push('schema')
<script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->title,
        'serviceType' => $parent->title,
        'description' => $service->short_description,
        'provider' => ['@type' => 'Organization', 'name' => setting('company_name', 'Advertally'), 'url' => url('/')],
        'areaServed' => 'IN',
        'offers' => $service->starting_price ? ['@type' => 'Offer', 'priceCurrency' => 'INR', 'price' => $service->starting_price] : null,
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

@include('services._hero', [
    'formType' => $isHire ? 'hire' : 'quote',
    'formVariant' => 'compact',
    'formServices' => array_filter([$serviceKey]),
    'formButton' => $isHire ? 'Request Profiles' : 'Get My Free Proposal',
])

@include('services._sections', ['service' => $service, 'fallback' => $parent])

{{-- Testimonial --}}
@if ($testimonial)
<section class="py-16">
    <div class="container-x">
        <figure class="mx-auto max-w-4xl text-center">
            <x-lucide name="quote" class="mx-auto size-10 text-brand-200" />
            <blockquote class="mt-6 font-display text-xl leading-relaxed font-semibold text-ink sm:text-2xl">“{{ $testimonial->quote }}”</blockquote>
            <figcaption class="mt-6 text-sm text-muted"><strong class="text-ink">{{ $testimonial->name }}</strong> · {{ $testimonial->designation }}, {{ $testimonial->company }}</figcaption>
        </figure>
    </div>
</section>
@endif

{{-- Related services in same hub --}}
@if ($siblings->isNotEmpty())
<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading eyebrow="Related" :title="'More in '.$parent->title" />
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($siblings->take(4) as $s)
                <a href="{{ route('services.show', [$parent->slug, $s->slug]) }}" class="card-hover group p-5">
                    <x-lucide :name="$s->icon" class="size-6 text-brand-600" />
                    <p class="mt-3 font-semibold group-hover:text-brand-700">{{ $s->title }}</p>
                    <p class="mt-1 line-clamp-2 text-sm text-muted">{{ $s->short_description }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-faq :faqs="$service->faqs" />

@include('services._upsell', ['next' => $next])

<x-cta-band :context="$service->title" />

@endsection
