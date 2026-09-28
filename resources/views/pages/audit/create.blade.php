@extends('layouts.app')

@section('title', 'Free Website Audit — Instant SEO, Speed & Mobile Check | Advertally')
@section('description', 'Get a free instant audit of your website: SSL, speed, mobile-friendliness, SEO basics and lead capture. Receive a PDF report with fixes in plain English.')
@section('whatsapp_context', 'a website audit')

@php
    $mine = old('form_id') === 'audit';
@endphp

@section('content')
<section class="bg-navy-glow relative overflow-hidden text-white">
    <div class="bg-grid pointer-events-none absolute inset-0 opacity-25"></div>
    <div class="container-x relative grid gap-12 py-16 lg:grid-cols-12 lg:py-20">
        <div class="lg:col-span-6">
            <span class="eyebrow !bg-white/10 !text-accent-300">Free tool · No signup fee</span>
            <h1 class="mt-5 text-4xl leading-tight font-extrabold text-white sm:text-5xl">Is your website helping or hurting your business?</h1>
            <p class="mt-5 text-lg text-white/75">Get an instant score across security, speed, mobile, SEO and lead capture — with plain-English fixes. We'll also email you a detailed PDF report.</p>
            <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                @foreach ([['lock', 'SSL & security'], ['gauge', 'Speed & page weight'], ['phone', 'Mobile readiness'], ['search', 'SEO essentials'], ['message', 'WhatsApp & call buttons'], ['bar-chart', 'Analytics tracking']] as [$ic, $t])
                    <li class="flex items-center gap-3 text-sm text-white/85"><span class="grid size-8 place-items-center rounded-lg bg-white/10"><x-lucide :name="$ic" class="size-4 text-accent-400" /></span>{{ $t }}</li>
                @endforeach
            </ul>
        </div>
        <div class="lg:col-span-6">
            <form method="POST" action="{{ route('audit.store') }}" x-data="{ sending: false }" @submit="sending = true" class="rounded-[var(--radius-card)] bg-white p-6 text-ink shadow-2xl sm:p-8">
                @csrf
                <input type="hidden" name="form_type" value="audit">
                <input type="hidden" name="form_id" value="audit">
                <input type="hidden" name="services[]" value="website">
                <input type="hidden" name="source_page" value="{{ request()->getRequestUri() }}">
                <input type="hidden" name="_ts" value="{{ time() }}">
                <div class="hidden" aria-hidden="true"><input type="text" name="company_website" tabindex="-1" autocomplete="off"></div>

                <p class="font-display text-xl font-bold">Run my free audit</p>
                @if ($mine && $errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
                @endif
                <div class="mt-5 space-y-4">
                    <div>
                        <label for="a-website" class="field-label">Website address <span class="text-red-600">*</span></label>
                        <input id="a-website" name="website" type="text" inputmode="url" required value="{{ $mine ? old('website') : request('url') }}" class="field !py-3 !text-base" placeholder="yourbusiness.com">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="a-name" class="field-label">Your name <span class="text-red-600">*</span></label>
                            <input id="a-name" name="name" type="text" required value="{{ $mine ? old('name') : '' }}" class="field" autocomplete="name">
                        </div>
                        <div>
                            <label for="a-phone" class="field-label">Mobile / WhatsApp <span class="text-red-600">*</span></label>
                            <input id="a-phone" name="phone" type="tel" inputmode="numeric" required value="{{ $mine ? old('phone') : '' }}" class="field" autocomplete="tel-national" placeholder="98XXXXXXXX">
                        </div>
                    </div>
                    <div>
                        <label for="a-email" class="field-label">Email (for the PDF report)</label>
                        <input id="a-email" name="email" type="email" value="{{ $mine ? old('email') : '' }}" class="field" autocomplete="email" placeholder="you@company.com">
                    </div>
                    <div>
                        <label for="a-size" class="field-label">Business size</label>
                        <select id="a-size" name="business_size" class="field">
                            <option value="">Select…</option>
                            @foreach (config('advertally.business_sizes') as $k => $label)<option value="{{ $k }}" @selected($mine && old('business_size') === $k)>{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    @if (config('advertally.turnstile.site_key'))
                        <div class="cf-turnstile" data-sitekey="{{ config('advertally.turnstile.site_key') }}" data-size="flexible"></div>
                        @push('scripts')<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endpush
                    @endif
                    <label class="flex items-start gap-2.5 text-xs text-muted">
                        <input type="checkbox" name="consent" value="1" checked required class="mt-0.5 size-4 rounded border-line text-brand-600">
                        <span>I agree to receive my report and be contacted about it. See our <a href="{{ route('privacy') }}" class="underline">privacy policy</a>.</span>
                    </label>
                    <button class="btn-cta w-full !py-3.5" :disabled="sending">
                        <span x-show="!sending">Analyse My Website</span>
                        <span x-show="sending" x-cloak class="flex items-center gap-2">
                            <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3"/></svg>
                            Analysing… this takes 10–20 seconds
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-x">
        <x-section-heading eyebrow="How it works" title="Your report in three simple steps" />
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ([['Enter your website', 'Just the address — no login or code needed.'], ['Get your instant score', 'See what\'s working and what\'s costing you customers.'], ['Fix it (or let us)', 'Follow the plain-English fixes yourself or ask us for help.']] as $i => [$t, $d])
                <div class="card p-6"><p class="font-display text-4xl font-extrabold text-brand-100">0{{ $i + 1 }}</p><h3 class="mt-2 text-lg font-bold">{{ $t }}</h3><p class="mt-2 text-sm text-muted">{{ $d }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<x-faq :faqs="$faqs" />
@endsection
