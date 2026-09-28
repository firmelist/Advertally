@extends('layouts.app')

@section('title', 'Book a Free 30-Minute Growth Consultation | Advertally')
@section('description', 'Book a free 30-minute call with an Advertally strategist to discuss marketing, website, CRM or software for your business.')

@section('content')
<section class="bg-hero">
    <div class="container-x pt-8 pb-20">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'Book a consultation']]" />
        <div class="mx-auto mt-10 max-w-3xl text-center">
            <span class="eyebrow">Free consultation</span>
            <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">Book a free 30-minute growth call</h1>
            <p class="lead-text mt-5">Pick a slot that suits you. We'll review your business beforehand and come prepared with ideas.</p>
        </div>
        <div class="mt-12 grid gap-8 lg:grid-cols-12">
            <div class="card overflow-hidden lg:col-span-8">
                <iframe src="{{ config('advertally.booking_url') }}?hide_gdpr_banner=1" title="Book a consultation" class="h-[700px] w-full" loading="lazy"></iframe>
            </div>
            <div class="lg:col-span-4">
                <div class="card p-6">
                    <p class="font-display text-lg font-bold">Can't find a slot?</p>
                    <p class="mt-1 text-sm text-muted">Leave your number and we'll call you back.</p>
                    <x-lead-form form-type="consultation" form-id="consultation" variant="compact" button="Request a Call Back" class="mt-5 space-y-4" />
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
