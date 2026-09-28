@extends('layouts.app')
@section('title', 'Page not found | Advertally')
@section('noindex', true)
@section('content')
<section class="bg-hero">
    <div class="container-x py-24 text-center">
        <p class="font-display text-7xl font-extrabold text-brand-100">404</p>
        <h1 class="h-display mt-4 !text-4xl">This page took a wrong turn.</h1>
        <p class="lead-text mx-auto mt-4 max-w-lg">The page you're looking for doesn't exist or has moved. Let's get you back on track.</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="btn-primary">Go to homepage</a>
            <a href="{{ route('contact') }}" class="btn-ghost">Contact us</a>
        </div>
    </div>
</section>
@endsection
