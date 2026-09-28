@extends('layouts.app')

@section('content')
<section class="bg-hero">
    <div class="container-x pt-8 pb-10">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => $heading]]" />
        <h1 class="h-display mt-8 !text-4xl">{{ $heading }}</h1>
        <p class="mt-3 text-sm text-muted">Last updated: {{ $updated }}</p>
    </div>
</section>
<section class="pb-20">
    <div class="container-x">
        <div class="prose prose-slate max-w-3xl prose-headings:font-display prose-a:text-brand-700">
            @yield('legal')
        </div>
    </div>
</section>
@endsection
