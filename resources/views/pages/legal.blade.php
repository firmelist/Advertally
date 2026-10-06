@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-12 sm:pt-14">
        <div class="container-narrow">
            <x-breadcrumbs class="mb-10" />
            <h1 class="h-page">{{ $page->title }}</h1>
            <p class="mt-4 text-sm text-muted">Last updated {{ $page->updated_at->format('j F Y') }}</p>
        </div>
    </section>
    <section class="bg-white py-14">
        <div class="container-narrow prose-adv">{!! str($page->body)->sanitizeHtml() !!}</div>
    </section>
@endsection
