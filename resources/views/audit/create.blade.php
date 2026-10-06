@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden pt-10 pb-20 sm:pt-14">
        <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]" aria-hidden="true"></div>
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                <div>
                    <p class="eyebrow"><x-glyph name="sparkles" class="size-4" /> AI Visibility Audit</p>
                    <h1 class="h-page mt-5">Be the brand AI recommends.</h1>
                    <p class="lead mt-6">Buyers now ask ChatGPT, Gemini, Perplexity, Claude, Copilot and Google's AI Overviews before they ever visit a website. Find out how ready your business is to be crawled, understood, trusted and cited.</p>

                    <p class="mt-10 text-sm font-bold text-ink">Your Advertally AI Visibility Score covers</p>
                    <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($dimensions as $dimension)
                            <li class="flex gap-3 rounded-xl border border-line bg-white p-3.5">
                                <x-glyph name="check-circle" class="mt-0.5 size-5 text-brand-600" />
                                <span><span class="block text-sm font-semibold text-ink">{{ $dimension->name }}</span><span class="block text-xs leading-relaxed text-muted">{{ $dimension->description }}</span></span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-6 text-xs leading-relaxed text-muted">The automated audit analyses on-site signals AI systems rely on. It does not guarantee or claim to measure live AI rankings — an analyst reviews competitor and citation context on request.</p>
                </div>

                <form method="POST" action="{{ route('ai-audit.store') }}" class="card relative space-y-5 self-start p-6 sm:p-8"
                    x-data="{ busy: false }" @submit="busy = true">
                    @csrf
                    <x-form.guard />
                    <div>
                        <p class="text-lg font-bold text-ink">Run My AI Visibility Audit</p>
                        <p class="mt-1 text-sm text-muted">Takes about 20 seconds. Your report opens immediately.</p>
                    </div>
                    <x-form.field name="website" label="Website" required placeholder="yourcompany.com" autocomplete="url" />
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field name="company" label="Company" required autocomplete="organization" />
                        <x-form.field name="industry" label="Industry" :options="config('advertally.industries')" required />
                        <x-form.field name="country" label="Primary market" required value="India" autocomplete="country-name" />
                        <x-form.field name="primary_service" label="Primary service you sell" required placeholder="e.g. Cloud consulting" />
                    </div>
                    <x-form.field name="competitor" label="Main competitor (optional)" placeholder="competitor.com" />
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field name="name" label="Your name" required autocomplete="name" />
                        <x-form.field name="email" type="email" label="Business email" required autocomplete="email" />
                    </div>
                    <x-form.consent text="I agree to Advertally preparing this report and contacting me about it." />
                    <button class="btn-primary btn-lg w-full" :disabled="busy" data-track="ai_audit_submit">
                        <span x-show="!busy">Run My AI Visibility Audit</span>
                        <span x-show="busy" x-cloak>Analysing your website…</span>
                        <x-glyph name="arrow-right" class="size-4" />
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
