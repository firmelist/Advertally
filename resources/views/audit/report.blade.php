@extends('layouts.app')

@section('content')
    @if ($audit->status === 'completed')
        @include('partials.report', [
            'audit' => $audit,
            'eyebrow' => 'Advertally AI Visibility Score',
            'title' => 'AI Visibility Report: '.$audit->company,
            'scoreLabel' => 'AI Visibility Score',
            'note' => 'Automated analysis of on-site signals. It does not measure or guarantee live AI rankings or citations.',
            'nextTitle' => 'Discuss your AI visibility opportunities.',
            'nextText' => 'We will review this report alongside how AI assistants currently describe '.$audit->company.($audit->competitor ? ' and '.$audit->competitor : '').', then outline a practical plan.',
            'secondaryLabel' => 'Get the full Growth Score',
            'secondaryUrl' => route('growth-score'),
        ])
    @else
        <section class="section bg-hero">
            <div class="container-narrow text-center">
                <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-ai-50 text-ai-600"><x-glyph name="user-search" class="size-8" /></span>
                <h1 class="h-page mt-6">Your audit needs an analyst.</h1>
                <p class="lead mt-5">We could not analyse <strong class="text-ink">{{ $audit->website }}</strong> automatically — the site may block automated visitors or be temporarily unavailable. That is common for well-protected sites.</p>
                <p class="mt-4 text-muted">Your request is saved. An Advertally analyst will complete the audit manually and email it to {{ $audit->email }} within two business days.</p>
                <x-cta-buttons class="mt-9 justify-center" primary-label="Take the Growth Score meanwhile" :secondary-label="'Talk to an Expert'" />
            </div>
        </section>
    @endif
@endsection
