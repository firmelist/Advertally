@extends('layouts.app')

@section('title', 'Case Studies — Results for Indian SMEs | Advertally')
@section('description', 'How Advertally helped clinics, manufacturers, D2C brands and service businesses grow leads, sales and efficiency with marketing, websites and automation.')

@section('content')
<section class="bg-hero">
    <div class="container-x pt-8 pb-12">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'Case Studies']]" />
        <div class="mt-8 max-w-3xl">
            <span class="eyebrow">Case studies</span>
            <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">Real results for real businesses</h1>
            <p class="lead-text mt-5">Numbers our clients care about: enquiries, sales, cost per lead and hours saved.</p>
        </div>
        @if ($industries->count() > 1)
            <div class="mt-8 flex flex-wrap gap-2">
                <a href="{{ route('case-studies.index') }}" @class(['rounded-full border px-4 py-1.5 text-sm font-medium', 'border-brand-600 bg-brand-600 text-white' => ! $active, 'border-line bg-white text-muted hover:text-ink' => $active])>All</a>
                @foreach ($industries as $ind)
                    <a href="{{ route('case-studies.index', ['industry' => $ind]) }}" @class(['rounded-full border px-4 py-1.5 text-sm font-medium', 'border-brand-600 bg-brand-600 text-white' => $active === $ind, 'border-line bg-white text-muted hover:text-ink' => $active !== $ind])>{{ config('advertally.industries')[$ind] ?? $ind }}</a>
                @endforeach
            </div>
        @endif
    </div>
</section>
<section class="pb-20">
    <div class="container-x grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($caseStudies as $cs)
            <x-case-study-card :cs="$cs" />
        @empty
            <p class="text-muted">No case studies in this category yet.</p>
        @endforelse
    </div>
</section>
<x-cta-band title="Want results like these?" />
@endsection
