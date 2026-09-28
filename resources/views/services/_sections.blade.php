{{-- Problem → solution → deliverables → process. Expects $service (and optional $fallback for child pages) --}}
@php
    $src = $fallback ?? $service;
    $problems = $service->problems ?: $src->problems;
    $process = $service->process ?: $src->process;
    $body = $service->body ?: $src->body;
@endphp

@if ($problems)
<section class="section">
    <div class="container-x grid gap-12 lg:grid-cols-2 lg:gap-16">
        <div>
            <x-section-heading align="left" eyebrow="The problem" title="Why most businesses struggle here" />
            <ul class="mt-8 space-y-3">
                @foreach ($problems as $p)
                    <li class="flex gap-3 rounded-xl border border-red-100 bg-red-50/50 p-4 text-sm font-medium text-ink/85">
                        <x-lucide name="x-circle" class="size-5 shrink-0 text-red-500" /> {{ $p }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            <x-section-heading align="left" eyebrow="Our solution" :title="'How Advertally fixes it'" />
            <div class="prose prose-slate mt-8 max-w-none prose-p:text-muted prose-p:leading-relaxed">{!! $body !!}</div>
            @if ($service->deliverables)
                <div class="card mt-8 p-6">
                    <p class="text-sm font-bold text-ink">What's included</p>
                    <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($service->deliverables as $d)
                            <li class="check-li"><x-lucide name="check-circle" class="mt-0.5 size-4 shrink-0 text-teal-700" /> {{ $d }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

@if ($process)
<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading eyebrow="Process" title="How we'll work together" />
        <ol class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($process as $i => $step)
                <li class="card relative p-6">
                    <span class="font-display text-5xl font-extrabold text-brand-100">0{{ $i + 1 }}</span>
                    <h3 class="mt-2 text-lg font-bold">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif
