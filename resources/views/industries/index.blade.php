@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_1fr]">
                <div>
                    <p class="eyebrow">Industries</p>
                    <h1 class="h-page mt-5">Built for markets where <span class="text-gradient-anim">trust decides the deal.</span></h1>
                    <p class="lead mt-6">We focus on businesses with considered, high-value purchases — where buyers research deeply, compare carefully and increasingly ask AI before they ever speak to sales.</p>
                    <x-cta-buttons class="mt-9" />
                </div>
                <x-hero.industries :industries="$industries" />
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($industries as $industry)
                <a href="{{ $industry->url() }}" class="card-hover group flex flex-col p-7" data-reveal>
                    <span class="grid size-12 place-items-center rounded-2xl bg-brand-50 text-brand-700"><x-glyph :name="$industry->icon ?: 'building'" class="size-6" /></span>
                    <h2 class="mt-6 text-xl font-bold text-ink group-hover:text-brand-700">{{ $industry->name }}</h2>
                    <p class="mt-2 flex-1 text-[15px] leading-relaxed text-muted">{{ $industry->summary }}</p>
                    <span class="link-arrow mt-6">See the growth system <x-glyph name="arrow-right" class="size-4" /></span>
                </a>
            @endforeach
        </div>
    </section>

    <x-cta-band />
@endsection
