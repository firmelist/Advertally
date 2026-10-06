@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <p class="eyebrow">Advertally Growth OS</p>
            <h1 class="h-page mt-5 max-w-4xl">One Growth System. Six Engines.</h1>
            <p class="lead mt-6 max-w-3xl">Marketing is no longer a collection of channels. It is a connected growth system — and every engine makes the others more effective.</p>
            <x-signal class="mt-14 max-w-5xl" />
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x space-y-6">
            @foreach ($engines as $engine)
                <article class="card grid gap-8 p-6 sm:p-10 lg:grid-cols-[1fr_1.4fr] lg:gap-14" data-reveal>
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="grid size-12 place-items-center rounded-2xl bg-navy-900 text-white"><x-glyph :name="$engine->icon ?: 'sparkles'" class="size-6" /></span>
                            <span class="font-mono text-sm font-bold text-brand-600">{{ $engine->number }}</span>
                        </div>
                        <h2 class="mt-5 text-3xl font-extrabold text-ink">{{ $engine->name }}</h2>
                        <p class="mt-1 text-xl font-semibold text-brand-700">{{ $engine->tagline }}</p>
                        <p class="mt-4 leading-relaxed text-muted">{{ $engine->summary }}</p>
                        <a href="{{ $engine->url() }}" class="btn-dark mt-7">Explore {{ $engine->name }} <x-glyph name="arrow-right" class="size-4" /></a>
                    </div>
                    <ul class="grid content-start gap-2 sm:grid-cols-2">
                        @foreach ($engine->services as $service)
                            <li>
                                <a href="{{ $service->url() }}" class="group flex h-full flex-col rounded-xl border border-line p-4 transition hover:border-brand-200 hover:bg-canvas">
                                    <span class="flex items-center justify-between font-semibold text-ink group-hover:text-brand-700">{{ $service->title }} <x-glyph name="arrow-up-right" class="size-4 text-muted" /></span>
                                    <span class="mt-1 text-sm text-muted">{{ $service->short_description }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>

    <x-cta-band />
@endsection
