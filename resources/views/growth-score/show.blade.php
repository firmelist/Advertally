@extends('layouts.app')

@section('content')
    <section class="bg-hero relative overflow-hidden pt-10 pb-20 sm:pt-14">
        <div class="bg-dots absolute inset-0 [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]" aria-hidden="true"></div>
        <div class="container-x relative">
            <x-breadcrumbs class="mb-10" />
            <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
                <div>
                    <p class="eyebrow">Advertally Growth Score™</p>
                    <h1 class="h-page mt-5">How strong is your growth system?</h1>
                    <p class="lead mt-6">Answer {{ $dimensions->sum(fn ($d) => $d->questions()->where('is_active', true)->count()) }} quick questions about how your business is found, trusted and chosen. Get a 0–100 score across six engines — and the moves that matter most.</p>
                    <ul class="mt-8 space-y-4">
                        @foreach ($dimensions as $dimension)
                            <li class="flex gap-3">
                                <span class="grid size-7 shrink-0 place-items-center rounded-lg bg-white font-mono text-xs font-bold text-brand-700 shadow-xs ring-1 ring-line">{{ $loop->iteration }}</span>
                                <span><span class="font-semibold text-ink">{{ $dimension->name }}</span><span class="block text-sm text-muted">{{ $dimension->description }}</span></span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-8 flex items-center gap-2 text-sm text-muted"><x-glyph name="clock" class="size-4" /> About 4 minutes · Free · Results instantly</p>
                </div>
                <div>
                    <livewire:growth-score-wizard />
                </div>
            </div>
        </div>
    </section>
@endsection
