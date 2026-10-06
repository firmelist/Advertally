@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden pt-10 pb-20 sm:pt-14">
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
                <div>
                    <p class="eyebrow">Contact</p>
                    <h1 class="h-page mt-5">Let's Build Your Next Growth Engine.</h1>
                    <p class="lead mt-6">Tell us where you are and where you need to be. A senior strategist — not a sales script — will respond within one business day.</p>

                    <ol class="mt-10 space-y-5">
                        @foreach ([
                            ['A focused conversation', 'Thirty minutes on your market, your numbers and what is blocking growth.'],
                            ['A growth diagnosis', 'We review your visibility, demand and conversion system before proposing anything.'],
                            ['A clear recommendation', 'Priorities, sequencing and expected outcomes — including what not to do.'],
                        ] as [$title, $text])
                            <li class="flex gap-4">
                                <span class="grid size-8 shrink-0 place-items-center rounded-full bg-navy-900 font-mono text-xs font-bold text-white">{{ $loop->iteration }}</span>
                                <span><span class="font-semibold text-ink">{{ $title }}</span><span class="mt-0.5 block text-sm text-muted">{{ $text }}</span></span>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-10 space-y-3 border-t border-line pt-8 text-sm">
                        @if ($email = setting('email'))
                            <a href="mailto:{{ $email }}" class="flex items-center gap-3 font-semibold text-ink hover:text-brand-700"><x-glyph name="mail" class="size-5 text-brand-600" /> {{ $email }}</a>
                        @endif
                        @if ($phone = setting('phone'))
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="flex items-center gap-3 font-semibold text-ink hover:text-brand-700"><x-glyph name="phone" class="size-5 text-brand-600" /> {{ $phone }}</a>
                        @endif
                        @if ($address = setting('address'))
                            <p class="flex items-start gap-3 text-muted"><x-glyph name="map-pin" class="size-5 text-brand-600" /> {{ $address }}</p>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('contact.store') }}" class="card relative space-y-5 self-start p-6 sm:p-8" novalidate>
                    @csrf
                    <input type="hidden" name="form" value="contact">
                    <x-form.guard />

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">Please check the highlighted fields.</div>
                    @endif

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field name="name" label="Name" required autocomplete="name" />
                        <x-form.field name="company" label="Company" required autocomplete="organization" />
                        <x-form.field name="email" type="email" label="Business email" required autocomplete="email" />
                        <x-form.field name="website" label="Website" placeholder="yourcompany.com" autocomplete="url" />
                        <x-form.field name="industry" label="Industry" :options="config('advertally.industries')" required />
                        <x-form.field name="challenge" label="Current challenge" :options="config('advertally.challenges')" />
                        <x-form.field name="objective" label="Marketing objective" :options="config('advertally.objectives')" />
                        <x-form.field name="budget" label="Approx. monthly marketing investment" :options="config('advertally.budgets')" />
                    </div>
                    <x-form.field name="message" type="textarea" label="How can we help?" required rows="5" placeholder="What are you trying to achieve in the next 6–12 months?" />
                    <x-form.consent />
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <button class="btn-primary btn-lg" data-track="contact_submit">Request Growth Strategy <x-glyph name="arrow-right" class="size-4" /></button>
                        <a href="{{ route('growth-score') }}" class="link-arrow sm:ml-3">Run Growth Score <x-glyph name="arrow-right" class="size-4" /></a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
