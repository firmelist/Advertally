@extends('layouts.app')

@section('title', 'Thank you — Advertally')
@section('noindex', true)

@section('content')
<section class="bg-hero">
    <div class="container-x py-24 text-center">
        <span class="mx-auto grid size-16 place-items-center rounded-full bg-teal-50 text-teal-700"><x-lucide name="check-circle" class="size-8" /></span>
        <h1 class="h-display mt-6 !text-4xl">Thank you{{ $lead ? ', '.explode(' ', $lead)[0] : '' }}!</h1>
        <p class="lead-text mx-auto mt-4 max-w-xl">We've received your enquiry. A strategist will call or WhatsApp you within 2 working hours ({{ setting('business_hours') }}).</p>
        <div class="mx-auto mt-10 grid max-w-3xl gap-4 text-left sm:grid-cols-3">
            @foreach ([['1', 'We review', 'your website, market and competitors.'], ['2', 'We call you', 'to understand goals and budget.'], ['3', 'You get a plan', 'with clear targets and a fixed quote.']] as [$n, $t, $d])
                <div class="card p-5"><p class="font-display text-3xl font-extrabold text-brand-100">0{{ $n }}</p><p class="mt-1 font-semibold">{{ $t }}</p><p class="text-sm text-muted">{{ $d }}</p></div>
            @endforeach
        </div>
        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ whatsapp_link('my enquiry') }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-whatsapp-glyph class="size-5" /> Speed things up on WhatsApp</a>
            <a href="{{ route('case-studies.index') }}" class="btn-ghost">Read case studies meanwhile</a>
        </div>
    </div>
</section>
@endsection
