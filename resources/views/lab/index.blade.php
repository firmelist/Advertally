@extends('layouts.app')

@section('content')
    <section class="bg-navy-field relative overflow-hidden pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" dark />
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-6 font-mono text-[11px] tracking-widest text-navy-300 uppercase">
                <span>Advertally AI Search Lab</span>
                <span>Research · Experiments · Frameworks</span>
            </div>
            <h1 class="mt-10 max-w-4xl text-4xl leading-[1.05] font-extrabold tracking-tight text-white sm:text-6xl">How AI discovers, evaluates and recommends businesses.</h1>
            <p class="mt-6 max-w-3xl text-lg leading-relaxed text-navy-200">Independent research into AI search, generative engines and B2B discovery. We publish our methods and our sources — and we say clearly what the data does and does not show.</p>

            <nav class="mt-10 flex flex-wrap gap-2" aria-label="Research categories">
                <a href="{{ route('lab.index') }}" @class(['chip-dark hover:bg-white/10', '!bg-white !text-navy-900' => ! $activeCategory])>All research</a>
                @foreach ($categories as $key => $label)
                    <a href="{{ route('lab.index', ['category' => $key]) }}" @class(['chip-dark hover:bg-white/10', '!bg-white !text-navy-900' => $activeCategory === $key])>{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            @if ($featured)
                <article class="group relative mb-14 grid gap-8 border-b border-line pb-14 lg:grid-cols-[1.4fr_1fr]">
                    <div>
                        <p class="font-mono text-xs font-semibold tracking-wider text-ai-600 uppercase">Featured · {{ $featured->categoryLabel() }}</p>
                        <h2 class="mt-4 text-3xl leading-tight font-extrabold text-ink sm:text-4xl">
                            <a href="{{ $featured->url() }}" class="after:absolute after:inset-0 group-hover:text-brand-700">{{ $featured->title }}</a>
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-muted">{{ $featured->summary }}</p>
                        <p class="mt-6 font-mono text-xs text-muted">{{ $featured->author?->name }} · {{ $featured->published_at?->format('F Y') }}</p>
                    </div>
                    @if ($featured->key_findings)
                        <div class="rounded-2xl bg-canvas p-6">
                            <p class="font-mono text-xs font-semibold tracking-wider text-muted uppercase">Key findings</p>
                            <ol class="mt-4 space-y-3">
                                @foreach (array_slice($featured->key_findings, 0, 3) as $finding)
                                    <li class="flex gap-3 text-sm leading-relaxed text-ink"><span class="font-mono font-bold text-brand-600">{{ $loop->iteration }}</span> {{ $finding }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </article>
            @endif

            @if ($research->isEmpty())
                <p class="text-center text-muted">Research in this category is in progress.</p>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($research as $item)
                        <x-cards.research :research="$item" data-reveal />
                    @endforeach
                </div>
                <div class="mt-12">{{ $research->links() }}</div>
            @endif
        </div>
    </section>

    <section class="section-tight bg-canvas">
        <div class="container-x">
            <div class="card grid items-center gap-8 p-8 sm:p-10 lg:grid-cols-[1.4fr_1fr]">
                <div>
                    <p class="eyebrow">Apply the research</p>
                    <h2 class="mt-3 text-2xl font-bold text-ink sm:text-3xl">How visible is your business to AI?</h2>
                    <p class="mt-3 text-muted">Run the AI Visibility Audit to see how ready your website is to be crawled, understood and cited by AI assistants.</p>
                </div>
                <a href="{{ route('ai-audit') }}" class="btn-primary btn-lg lg:justify-self-end">Check My AI Visibility <x-glyph name="arrow-right" class="size-4" /></a>
            </div>
        </div>
    </section>
@endsection
