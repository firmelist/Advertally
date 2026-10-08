@extends('layouts.app')

@section('content')
    <section class="bg-hero pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="max-w-3xl">
                <p class="eyebrow">Internships</p>
                <h1 class="h-page mt-5">Learn growth by <span class="text-gradient-anim">doing real work.</span></h1>
                <p class="lead mt-6">Internships in digital marketing, development, design and AI — working alongside the Advertally team on the problems real businesses face.</p>
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            @if ($internships->isEmpty())
                <div class="card mx-auto max-w-2xl p-10 text-center">
                    <x-glyph name="users" class="mx-auto size-10 text-brand-300" />
                    <h2 class="mt-5 text-xl font-bold text-ink">No open internships right now.</h2>
                    <p class="mt-2 text-muted">New roles open throughout the year. You're welcome to introduce yourself in the meantime.</p>
                    <a href="{{ route('contact') }}" class="btn-primary mt-6">Introduce yourself</a>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($internships as $internship)
                        <article class="card-hover group relative flex flex-col p-6" data-reveal>
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($internship->department)<span class="chip">{{ $internship->department }}</span>@endif
                                <span class="chip">{{ \App\Models\Internship::MODES[$internship->work_mode] ?? '' }}</span>
                                @if (! $internship->isOpen())<span class="chip !text-red-700">Closed</span>@endif
                            </div>
                            <h2 class="mt-4 text-xl font-bold text-ink"><a href="{{ $internship->url() }}" class="after:absolute after:inset-0 group-hover:text-brand-700">{{ $internship->title }}</a></h2>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-muted">{{ $internship->summary }}</p>
                            <dl class="mt-5 grid grid-cols-2 gap-2 border-t border-line pt-4 text-xs">
                                @foreach (array_filter(['Duration' => $internship->duration, 'Stipend' => $internship->stipend]) as $label => $value)
                                    <div><dt class="text-muted">{{ $label }}</dt><dd class="font-semibold text-ink">{{ $value }}</dd></div>
                                @endforeach
                            </dl>
                            <span class="link-arrow mt-5">View internship <x-glyph name="arrow-right" class="size-4" /></span>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
