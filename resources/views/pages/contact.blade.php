@extends('layouts.app')

@section('title', 'Contact Advertally — Get a Free Proposal')
@section('description', 'Talk to Advertally about digital marketing, websites, CRM, dedicated developers or custom software. Call, WhatsApp or send an enquiry — we reply within 2 working hours.')

@section('content')
<section class="bg-hero relative overflow-hidden">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_80%)]"></div>
    <div class="container-x relative pt-8 pb-20">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']]" />
        <div class="mt-10 grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <span class="eyebrow">Contact us</span>
                <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">Let's grow your business together.</h1>
                <p class="lead-text mt-5">Share a few details and a senior strategist will get back within 2 working hours with honest recommendations.</p>

                <div class="mt-8 space-y-3">
                    <a href="{{ whatsapp_link('a proposal') }}" target="_blank" rel="noopener" class="card-hover flex items-center gap-4 p-4">
                        <span class="grid size-11 place-items-center rounded-xl bg-[#128C4A] text-white"><x-whatsapp-glyph class="size-6" /></span>
                        <span><span class="block text-xs text-muted">WhatsApp (fastest)</span><span class="font-semibold">Chat with us now</span></span>
                    </a>
                    <a href="{{ tel_link() }}" class="card-hover flex items-center gap-4 p-4">
                        <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-lucide name="call" class="size-5" /></span>
                        <span><span class="block text-xs text-muted">Call us</span><span class="font-semibold">{{ setting('phone') }}</span></span>
                    </a>
                    <a href="mailto:{{ setting('email') }}" class="card-hover flex items-center gap-4 p-4">
                        <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-lucide name="mail" class="size-5" /></span>
                        <span><span class="block text-xs text-muted">Email</span><span class="font-semibold">{{ setting('email') }}</span></span>
                    </a>
                    <a href="{{ route('consultation') }}" class="card-hover flex items-center gap-4 p-4">
                        <span class="grid size-11 place-items-center rounded-xl bg-accent-50 text-accent-600"><x-lucide name="calendar" class="size-5" /></span>
                        <span><span class="block text-xs text-muted">Prefer a scheduled call?</span><span class="font-semibold">Book a free 30-min consultation</span></span>
                    </a>
                </div>
                <div class="mt-8 text-sm text-muted">
                    <p class="flex gap-2"><x-lucide name="map-pin" class="size-4 shrink-0 text-brand-600" /> {{ setting('address') }}</p>
                    <p class="mt-2 flex gap-2"><x-lucide name="clock" class="size-4 shrink-0 text-brand-600" /> {{ setting('business_hours') }}</p>
                </div>
            </div>
            <div class="lg:col-span-7" id="quote">
                <div class="card p-6 shadow-[var(--shadow-lift)] sm:p-8">
                    @if (request('plan'))
                        <p class="mb-5 rounded-xl bg-brand-50 px-4 py-3 text-sm text-brand-800">You selected the <strong>{{ request('plan') }}</strong> plan. Complete the form and we'll send a formal quote.</p>
                    @endif
                    <x-lead-form form-type="{{ request('plan') ? 'quote' : 'contact' }}" form-id="contact" variant="full"
                                 title="Tell us about your business" subtitle="Takes 60 seconds. Fields marked * are required.">
                        @if (request('plan'))<input type="hidden" name="plan_summary" value="Plan: {{ request('plan') }}">@endif
                        @if (array_key_exists(request('industry'), config('advertally.industries')))<input type="hidden" name="industry" value="{{ request('industry') }}">@endif
                    </x-lead-form>
                </div>
            </div>
        </div>
    </div>
</section>

@if (setting('map_embed_url'))
<section class="pb-16">
    <div class="container-x">
        <iframe src="{{ setting('map_embed_url') }}" title="Office location map" class="h-80 w-full rounded-[var(--radius-card)] border border-line" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>
@endif

<x-faq :faqs="$faqs" />
@endsection
