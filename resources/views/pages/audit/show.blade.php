@extends('layouts.app')

@section('title', 'Website Audit Report: '.parse_url($report->url, PHP_URL_HOST).' | Advertally')
@section('noindex', true)

@php
    $host = parse_url($report->url, PHP_URL_HOST);
    $color = $report->score >= 85 ? '#0F766E' : ($report->score >= 60 ? '#D9540B' : '#DC2626');
    $groups = collect($report->checks)->groupBy('category');
    $labels = ['security' => 'Security', 'speed' => 'Speed', 'mobile' => 'Mobile', 'seo' => 'SEO', 'conversion' => 'Lead capture'];
    $fails = collect($report->checks)->where('status', '!=', 'pass')->count();
@endphp

@section('content')
<section class="bg-hero">
    <div class="container-x pt-10 pb-16">
        <div class="grid items-center gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <span class="eyebrow">Website audit report</span>
                <h1 class="h-display mt-5 !text-4xl break-words">{{ $host }}</h1>
                <p class="lead-text mt-4">
                    We found <strong class="text-ink">{{ $fails }} {{ \Illuminate\Support\Str::plural('issue', $fails) }}</strong> that may be costing you enquiries.
                    @if ($report->lead?->email) A detailed PDF is on its way to <strong class="text-ink">{{ $report->lead->email }}</strong>. @endif
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ whatsapp_link('fixing my website audit issues ('.$host.')') }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-whatsapp-glyph class="size-5" /> Get these fixed</a>
                    <a href="{{ route('consultation') }}" class="btn-ghost">Book a free review call</a>
                </div>
            </div>
            <div class="lg:col-span-4">
                <div class="card mx-auto flex max-w-xs flex-col items-center p-8">
                    <div class="relative size-40">
                        <svg viewBox="0 0 120 120" class="size-40 -rotate-90">
                            <circle cx="60" cy="60" r="52" fill="none" stroke="#E3E8F2" stroke-width="12"/>
                            <circle cx="60" cy="60" r="52" fill="none" stroke="{{ $color }}" stroke-width="12" stroke-linecap="round" stroke-dasharray="{{ round(326.7 * $report->score / 100, 1) }} 326.7"/>
                        </svg>
                        <div class="absolute inset-0 grid place-items-center text-center">
                            <div><p class="font-display text-5xl font-extrabold" style="color: {{ $color }}">{{ $report->score }}</p><p class="text-xs text-muted">out of 100</p></div>
                        </div>
                    </div>
                    <p class="mt-4 font-semibold" style="color: {{ $color }}">{{ $report->grade }}</p>
                    @if ($report->performance_score !== null)<p class="mt-1 text-xs text-muted">Google PageSpeed (mobile): {{ $report->performance_score }}/100</p>@endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-20">
    <div class="container-x space-y-10">
        @foreach ($labels as $key => $label)
            @continue(! $groups->has($key))
            <div>
                <h2 class="text-xl font-bold">{{ $label }}</h2>
                <div class="mt-4 divide-y divide-line overflow-hidden rounded-[var(--radius-card)] border border-line bg-white">
                    @foreach ($groups[$key] as $c)
                        <div class="flex gap-4 p-5">
                            @if ($c['status'] === 'pass')
                                <x-lucide name="check-circle" class="size-6 shrink-0 text-teal-700" />
                            @elseif ($c['status'] === 'warn')
                                <x-lucide name="alert" class="size-6 shrink-0 text-accent-600" />
                            @else
                                <x-lucide name="x-circle" class="size-6 shrink-0 text-red-600" />
                            @endif
                            <div>
                                <p class="font-semibold">{{ $c['label'] }}</p>
                                <p class="mt-1 text-sm text-muted">{{ $c['detail'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

<x-cta-band title="Want us to fix these for you?" subtitle="Our team can fix most of these issues within a week — and set up tracking so you can see the impact on enquiries." :context="'my website audit ('.$host.')'" />
@endsection
