@extends('layouts.app')

@section('title', $cs->title.' | Advertally Case Study')
@section('description', $cs->summary)

@push('schema')
<script type="application/ld+json">
    {!! json_encode(['@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $cs->title, 'description' => $cs->summary, 'author' => ['@type' => 'Organization', 'name' => setting('company_name', 'Advertally')], 'datePublished' => $cs->created_at?->toAtomString(), 'dateModified' => $cs->updated_at?->toAtomString()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<section class="bg-navy-glow text-white">
    <div class="container-x pt-8 pb-16">
        <nav class="text-sm text-white/60"><a href="{{ route('case-studies.index') }}" class="hover:text-white">← All case studies</a></nav>
        <p class="mt-8 text-xs font-semibold tracking-wider text-accent-300 uppercase">{{ $cs->industry_label }}{{ $cs->city ? ' · '.$cs->city : '' }}</p>
        <h1 class="mt-4 max-w-4xl text-4xl leading-tight font-extrabold text-white sm:text-5xl">{{ $cs->title }}</h1>
        <p class="mt-5 max-w-3xl text-lg text-white/75">{{ $cs->summary }}</p>
        <div class="mt-10 grid max-w-3xl grid-cols-3 gap-6 border-t border-white/10 pt-8">
            @foreach (collect($cs->results) as $r)
                <div><p class="font-display text-3xl font-extrabold sm:text-4xl">{{ $r['value'] }}</p><p class="mt-1 text-sm text-white/60">{{ $r['label'] }}</p></div>
            @endforeach
        </div>
    </div>
</section>
<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <article class="space-y-10 lg:col-span-8">
            <div><h2 class="text-2xl font-bold">The challenge</h2><p class="mt-3 leading-relaxed text-muted">{{ $cs->challenge }}</p></div>
            <div><h2 class="text-2xl font-bold">What we did</h2><p class="mt-3 leading-relaxed text-muted">{{ $cs->solution }}</p></div>
            @if ($cs->testimonial)
                <blockquote class="rounded-[var(--radius-card)] border-l-4 border-accent-500 bg-canvas p-6 font-display text-lg font-semibold">“{{ $cs->testimonial }}”</blockquote>
            @endif
        </article>
        <aside class="lg:col-span-4">
            <div class="card sticky top-28 p-6">
                <p class="text-sm font-semibold">Client</p><p class="text-muted">{{ $cs->client }}</p>
                <p class="mt-4 text-sm font-semibold">Services used</p>
                <div class="mt-2 flex flex-wrap gap-1.5">@foreach ($cs->services ?? [] as $s)<span class="chip">{{ $s }}</span>@endforeach</div>
                <a href="{{ route('contact') }}#quote" class="btn-cta mt-6 w-full">Get similar results</a>
            </div>
        </aside>
    </div>
</section>
@if ($more->isNotEmpty())
<section class="section bg-canvas">
    <div class="container-x">
        <h2 class="text-2xl font-bold">More case studies</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-2">@foreach ($more as $m)<x-case-study-card :cs="$m" />@endforeach</div>
    </div>
</section>
@endif
<x-cta-band />
@endsection
