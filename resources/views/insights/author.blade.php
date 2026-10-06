@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-14 sm:pt-14">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="flex flex-col gap-8 sm:flex-row sm:items-center">
                @if ($author->photo)
                    <img src="{{ media_url($author->photo) }}" alt="{{ $author->name }}" class="size-28 rounded-3xl object-cover">
                @else
                    <span class="grid size-28 place-items-center rounded-3xl bg-navy-900 text-3xl font-bold text-white">{{ str($author->name)->explode(' ')->map(fn ($w) => $w[0])->take(2)->implode('') }}</span>
                @endif
                <div>
                    <p class="eyebrow">{{ $author->isTeam() ? 'Advertally team' : 'Author' }}</p>
                    <h1 class="h-page mt-3">{{ $author->name }}</h1>
                    @if ($author->job_title)<p class="mt-2 text-lg font-semibold text-muted">{{ $author->job_title }}</p>@endif
                </div>
            </div>
            @if ($author->bio)<p class="lead mt-8 max-w-3xl">{{ $author->bio }}</p>@endif
            @if ($author->expertise)
                <ul class="mt-6 flex flex-wrap gap-2">
                    @foreach ($author->expertise as $topic)<li class="chip">{{ $topic }}</li>@endforeach
                </ul>
            @endif
            @if ($author->linkedin_url)
                <a href="{{ $author->linkedin_url }}" class="link-arrow mt-6" rel="me noopener" target="_blank"><x-glyph name="linkedin" class="size-4" /> LinkedIn</a>
            @endif
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x space-y-14">
            @if ($research->isNotEmpty())
                <div>
                    <h2 class="text-2xl font-bold text-ink">AI Search Lab research</h2>
                    <div class="mt-8 grid gap-6 md:grid-cols-3">
                        @foreach ($research as $item)<x-cards.research :research="$item" />@endforeach
                    </div>
                </div>
            @endif
            <div>
                <h2 class="text-2xl font-bold text-ink">Insights</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @forelse ($posts as $post)
                        <x-cards.post :post="$post" />
                    @empty
                        <p class="text-muted">No published insights yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
