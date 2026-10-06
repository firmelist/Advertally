@extends('layouts.app')

@section('content')
    <article>
        <header class="bg-hero pt-10 pb-12 sm:pt-14">
            <div class="container-narrow">
                <x-breadcrumbs class="mb-10" />
                <p class="text-sm font-semibold text-brand-700">
                    @if ($post->category)<a href="{{ $post->category->url() }}" class="hover:underline">{{ $post->category->name }}</a> · @endif{{ \App\Models\Post::TYPES[$post->type] ?? 'Article' }}
                </p>
                <h1 class="mt-4 text-4xl leading-[1.1] font-extrabold tracking-tight text-ink sm:text-5xl">{{ $post->title }}</h1>
                <p class="lead mt-6">{{ $post->excerpt }}</p>
                <div class="mt-8 flex items-center gap-3 border-t border-line pt-6 text-sm">
                    @if ($post->author)
                        @if ($post->author->photo)
                            <img src="{{ media_url($post->author->photo) }}" alt="" class="size-10 rounded-full object-cover">
                        @else
                            <span class="grid size-10 place-items-center rounded-full bg-navy-900 text-xs font-bold text-white">{{ str($post->author->name)->explode(' ')->map(fn ($w) => $w[0])->take(2)->implode('') }}</span>
                        @endif
                        <span>
                            <a href="{{ $post->author->url() }}" class="font-semibold text-ink hover:text-brand-700" rel="author">{{ $post->author->name }}</a>
                            <span class="block text-muted">{{ $post->author->job_title }}</span>
                        </span>
                    @endif
                    <span class="ml-auto text-right text-muted">
                        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time><br>{{ $post->reading_time }} min read
                    </span>
                </div>
            </div>
        </header>

        @if ($post->featured_image)
            <div class="container-x"><img src="{{ media_url($post->featured_image) }}" alt="" class="mx-auto -mt-2 max-h-[30rem] w-full max-w-5xl rounded-3xl object-cover"></div>
        @endif

        <div class="bg-white py-14 sm:py-16">
            <div class="container-narrow">
                <div class="prose-adv prose-lg">{!! str($post->content)->sanitizeHtml() !!}</div>

                @if ($post->tags)
                    <ul class="mt-12 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)<li class="chip">#{{ $tag }}</li>@endforeach
                    </ul>
                @endif

                {{-- semantic links --}}
                @if ($post->services->isNotEmpty() || $post->industries->isNotEmpty())
                    <aside class="card mt-12 p-6 sm:p-8" aria-label="Related solutions">
                        @if ($post->services->isNotEmpty())
                            <p class="text-sm font-bold text-ink">Put this into practice</p>
                            <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                                @foreach ($post->services as $service)
                                    <li><a href="{{ $service->url() }}" class="flex items-center justify-between rounded-xl border border-line px-4 py-3 text-sm font-semibold text-ink hover:border-brand-200 hover:text-brand-700">{{ $service->title }} <x-glyph name="arrow-right" class="size-4 text-muted" /></a></li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($post->industries->isNotEmpty())
                            <p class="mt-6 text-sm font-bold text-ink">Most relevant for</p>
                            <ul class="mt-3 flex flex-wrap gap-2">
                                @foreach ($post->industries as $industry)
                                    <li><a href="{{ $industry->url() }}" class="chip hover:text-brand-700">{{ $industry->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </aside>
                @endif

                @if ($post->author && $post->author->bio)
                    <aside class="mt-12 flex gap-5 border-t border-line pt-10" aria-label="About the author">
                        <span class="grid size-14 shrink-0 place-items-center rounded-full bg-navy-900 text-sm font-bold text-white">{{ str($post->author->name)->explode(' ')->map(fn ($w) => $w[0])->take(2)->implode('') }}</span>
                        <div>
                            <p class="text-xs font-semibold tracking-wider text-muted uppercase">Written by</p>
                            <a href="{{ $post->author->url() }}" class="mt-1 block text-lg font-bold text-ink hover:text-brand-700">{{ $post->author->name }}</a>
                            <p class="mt-2 text-sm leading-relaxed text-muted">{{ $post->author->bio }}</p>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="Keep reading" title="Related insights." />
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-cards.post :post="$item->loadMissing('category')" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
@endsection
