@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-16 sm:pt-14">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <p class="eyebrow">Resources</p>
            <h1 class="h-page mt-5 max-w-4xl">Growth intelligence for the AI era.</h1>
            <p class="lead mt-6 max-w-3xl">Research, frameworks and free diagnostics to help you understand where growth is coming from — and where it is leaking.</p>

            <div class="mt-12 grid gap-5 md:grid-cols-2">
                <a href="{{ route('growth-score') }}" class="bg-navy-field group relative overflow-hidden rounded-[var(--radius-card)] p-8 text-white">
                    <div class="bg-dots-dark absolute inset-0" aria-hidden="true"></div>
                    <x-glyph name="gauge" class="relative size-8 text-signal-400" />
                    <p class="relative mt-5 text-2xl font-bold">Advertally Growth Score™</p>
                    <p class="relative mt-2 text-navy-200">Benchmark your business across six growth engines in about four minutes.</p>
                    <span class="relative mt-6 inline-flex items-center gap-1.5 font-semibold text-signal-400">Get your score <x-glyph name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" /></span>
                </a>
                <a href="{{ route('ai-audit') }}" class="card-hover group relative overflow-hidden p-8">
                    <div class="absolute -top-16 -right-16 size-56 rounded-full bg-ai-50 blur-2xl" aria-hidden="true"></div>
                    <x-glyph name="sparkles" class="relative size-8 text-ai-600" />
                    <p class="relative mt-5 text-2xl font-bold text-ink">AI Visibility Audit</p>
                    <p class="relative mt-2 text-muted">See how ready your website is to be crawled, understood and cited by AI assistants.</p>
                    <span class="relative mt-6 inline-flex items-center gap-1.5 font-semibold text-brand-700">Check my AI visibility <x-glyph name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" /></span>
                </a>
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x space-y-20">
            @if ($research->isNotEmpty())
                <div>
                    <div class="flex items-end justify-between gap-6">
                        <x-section-heading eyebrow="AI Search Lab" title="Latest research." />
                        <a href="{{ route('lab.index') }}" class="link-arrow shrink-0">All research <x-glyph name="arrow-right" class="size-4" /></a>
                    </div>
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        @foreach ($research as $item)<x-cards.research :research="$item" />@endforeach
                    </div>
                </div>
            @endif

            @if ($posts->isNotEmpty())
                <div>
                    <div class="flex items-end justify-between gap-6">
                        <x-section-heading eyebrow="Insights" title="Latest thinking." />
                        <a href="{{ route('insights.index') }}" class="link-arrow shrink-0">All insights <x-glyph name="arrow-right" class="size-4" /></a>
                    </div>
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        @foreach ($posts as $post)<x-cards.post :post="$post" />@endforeach
                    </div>
                </div>
            @endif

            @if ($reports->isNotEmpty())
                <div>
                    <x-section-heading eyebrow="Reports & frameworks" title="Tools for structured thinking." />
                    <ul class="mt-8 divide-y divide-line border-y border-line">
                        @foreach ($reports as $report)
                            <li>
                                <a href="{{ $report->url() }}" class="group flex items-center justify-between gap-6 py-5">
                                    <span>
                                        <span class="text-xs font-semibold text-brand-700">{{ \App\Models\Post::TYPES[$report->type] }}</span>
                                        <span class="block text-lg font-semibold text-ink group-hover:text-brand-700">{{ $report->title }}</span>
                                    </span>
                                    <x-glyph name="arrow-right" class="size-5 text-muted" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($caseStudies->isNotEmpty())
                <div>
                    <div class="flex items-end justify-between gap-6">
                        <x-section-heading eyebrow="Case studies" title="Growth stories." />
                        <a href="{{ route('case-studies.index') }}" class="link-arrow shrink-0">All stories <x-glyph name="arrow-right" class="size-4" /></a>
                    </div>
                    <div class="mt-10 grid gap-6 lg:grid-cols-2">
                        @foreach ($caseStudies as $caseStudy)<x-cards.case-study :case-study="$caseStudy->loadMissing('metrics')" />@endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
