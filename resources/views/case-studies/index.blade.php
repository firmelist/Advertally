@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_1fr]">
                <div>
                    <p class="eyebrow">Growth Stories</p>
                    <h1 class="h-page mt-5">From diagnosis to <span class="text-gradient-anim">measurable growth.</span></h1>
                    <p class="lead mt-6">Every story follows the same discipline: understand the business, diagnose the growth system, build what is missing and measure what changed.</p>
                    <x-cta-buttons class="mt-9" />
                </div>
                <x-hero.story-chart />
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            @if ($caseStudies->isEmpty())
                <div class="card mx-auto max-w-2xl p-10 text-center">
                    <x-glyph name="book" class="mx-auto size-10 text-brand-300" />
                    <h2 class="mt-5 text-xl font-bold text-ink">Growth stories are being documented.</h2>
                    <p class="mt-2 text-muted">We publish client stories only with verified data and client approval. In the meantime, see how your own business scores.</p>
                    <x-cta-buttons class="mt-7 justify-center" size="md" />
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ($caseStudies as $caseStudy)
                        <x-cards.case-study :case-study="$caseStudy" data-reveal />
                    @endforeach
                </div>
                <div class="mt-12">{{ $caseStudies->links() }}</div>
            @endif
        </div>
    </section>

    <x-cta-band />
@endsection
