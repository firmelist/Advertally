@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-12 sm:pt-14">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_1fr]">
                <div>
                    <p class="eyebrow">Insights</p>
                    <h1 class="h-page mt-5">@if ($activeCategory){{ $activeCategory->name }}@else Thinking for leaders who <span class="text-gradient-anim">own growth.</span>@endif</h1>
                    <p class="lead mt-6">{{ $activeCategory?->description ?: 'Articles, frameworks and reports on AI search, B2B demand, authority, conversion, automation and measurement.' }}</p>
                </div>
                <x-hero.articles :categories="$categories" class="hidden lg:block" />
            </div>

            <nav class="mt-10 flex flex-wrap gap-2" aria-label="Insight categories">
                <a href="{{ route('insights.index') }}" @class(['chip', '!border-navy-900 !bg-navy-900 !text-white' => ! $activeCategory && ! $activeType])>All</a>
                @foreach ($categories as $category)
                    <a href="{{ $category->url() }}" @class(['chip hover:border-brand-200', '!border-navy-900 !bg-navy-900 !text-white' => $activeCategory?->is($category)])>{{ $category->name }}</a>
                @endforeach
                <span class="mx-1 hidden w-px bg-line sm:block" aria-hidden="true"></span>
                @foreach (['report' => 'Reports', 'framework' => 'Frameworks', 'guide' => 'Guides'] as $type => $label)
                    <a href="{{ route('insights.index', ['type' => $type]) }}" @class(['chip hover:border-brand-200', '!border-navy-900 !bg-navy-900 !text-white' => $activeType === $type])>{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            @if ($featured)
                <article class="card-hover group relative mb-12 grid overflow-hidden lg:grid-cols-2">
                    <div class="bg-navy-field relative min-h-56 overflow-hidden" aria-hidden="true">
                        @if ($featured->featured_image)
                            <img src="{{ media_url($featured->featured_image) }}" alt="" class="absolute inset-0 size-full object-cover">
                        @else
                            <div class="bg-dots-dark absolute inset-0"></div>
                            <x-signal class="absolute inset-x-8 bottom-10" :steps="['Search', 'AI', 'Authority', 'Revenue']" dark compact />
                        @endif
                    </div>
                    <div class="p-8 sm:p-10">
                        <p class="text-xs font-semibold text-brand-700">Featured · {{ $featured->category?->name }}</p>
                        <h2 class="mt-3 text-2xl leading-tight font-bold text-ink sm:text-3xl">
                            <a href="{{ $featured->url() }}" class="after:absolute after:inset-0 group-hover:text-brand-700">{{ $featured->title }}</a>
                        </h2>
                        <p class="mt-4 leading-relaxed text-muted">{{ $featured->excerpt }}</p>
                        <p class="mt-6 text-sm text-muted">{{ $featured->author?->name }} · {{ $featured->reading_time }} min read</p>
                    </div>
                </article>
            @endif

            @if ($posts->isEmpty())
                <p class="text-center text-muted">No insights published here yet.</p>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-cards.post :post="$post" data-reveal />
                    @endforeach
                </div>
                <div class="mt-12">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>

    <x-cta-band />
@endsection
