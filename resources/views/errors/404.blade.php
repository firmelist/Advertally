@php app(\App\Services\Seo::class)->page('Page not found')->noindex(); @endphp
@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden py-24 sm:py-32">
        <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" aria-hidden="true"></div>
        <div class="container-narrow relative text-center">
            <p class="font-mono text-sm font-bold text-ai-600">404 · SIGNAL LOST</p>
            <h1 class="h-page mt-5">This Growth Signal Went Offline.</h1>
            <p class="lead mt-5">The page you were looking for has moved or no longer exists. The rest of the system is running normally.</p>
            <x-signal class="mx-auto mt-12 max-w-xl" :steps="['Intent', 'Search', '404', 'Growth OS']" compact />
            <div class="mt-12 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('solutions.index') }}" class="btn-primary btn-lg">Return to Growth OS <x-glyph name="arrow-right" class="size-4" /></a>
                <a href="{{ route('home') }}" class="btn-secondary btn-lg">Go to homepage</a>
            </div>
        </div>
    </section>
@endsection
