@extends('layouts.app')

@section('content')
    <section class="section bg-hero">
        <div class="container-narrow text-center">
            <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-growth-50 text-growth-600"><x-glyph name="check-circle" class="size-8" /></span>
            <h1 class="h-page mt-6">Thank you — we have your request.</h1>
            <p class="lead mt-5">
                @if ($form === 'talent')
                    We will review your requirement and respond within one business day with matched profiles or a team proposal.
                @else
                    A senior strategist will review your details and respond within one business day. Check your inbox for a confirmation.
                @endif
            </p>
            <div class="mt-10 grid gap-4 text-left sm:grid-cols-2">
                <a href="{{ route('growth-score') }}" class="card-hover p-6">
                    <x-glyph name="gauge" class="size-6 text-brand-600" />
                    <p class="mt-3 font-bold text-ink">Get your Growth Score</p>
                    <p class="mt-1 text-sm text-muted">Arrive at the call with a benchmark across six engines.</p>
                </a>
                <a href="{{ route('lab.index') }}" class="card-hover p-6">
                    <x-glyph name="flask" class="size-6 text-ai-600" />
                    <p class="mt-3 font-bold text-ink">Explore the AI Search Lab</p>
                    <p class="mt-1 text-sm text-muted">Research on how AI discovers and recommends businesses.</p>
                </a>
            </div>
        </div>
    </section>
    <script>window.addEventListener('load', () => window.track && window.track('generate_lead', { form: @js($form) }));</script>
@endsection
