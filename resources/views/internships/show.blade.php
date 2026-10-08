@extends('layouts.app')

@php
    $i = $internship;
    $open = $i->isOpen();
    $facts = array_filter([
        ['Department', $i->department, 'layers'],
        ['Work mode', \App\Models\Internship::MODES[$i->work_mode] ?? null, 'globe'],
        ['Location', $i->location, 'map-pin'],
        ['Duration', $i->duration, 'clock'],
        ['Stipend', $i->stipend, 'award'],
        ['Hours', $i->hours, 'calculator'],
        ['Start date', $i->start_date, 'rocket'],
        ['Openings', $i->openings, 'users'],
        ['Apply by', $i->apply_by?->format('j M Y'), 'compass'],
    ], fn ($f) => filled($f[1]));
@endphp

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="bg-hero pt-10 pb-16 sm:pt-14 lg:pb-20">
        <div class="container-x">
            <x-breadcrumbs class="mb-10" />
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-[1.15fr_1fr]">
                <div class="min-w-0">
                    <p class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white/80 py-1 pr-3.5 pl-1.5 text-xs font-semibold text-navy-700 shadow-xs">
                        <span class="rounded-full bg-navy-900 px-2 py-0.5 text-[10px] font-bold tracking-wider text-white uppercase">Internship</span>
                        {{ $i->department ?: 'Advertally' }} · {{ \App\Models\Internship::MODES[$i->work_mode] ?? '' }}
                    </p>
                    <h1 class="h-page mt-6">{!! preg_replace('/\b(Internship)$/u', '<span class="text-gradient-anim">$1</span>', e($i->title)) !!}</h1>
                    @if ($i->headline)<p class="mt-4 text-xl font-semibold text-ink">{!! preg_replace('/\*(.+?)\*/u', '<span class="text-brand-600">$1</span>', e($i->headline)) !!}</p>@endif
                    @if ($i->summary)<p class="lead mt-4">{{ $i->summary }}</p>@endif
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        @if ($open)
                            <a href="#apply" class="btn-primary btn-lg" data-track="internship_apply_cta">Apply now <x-glyph name="arrow-right" class="size-4" /></a>
                        @else
                            <span class="btn-secondary btn-lg pointer-events-none opacity-70">Applications closed</span>
                        @endif
                        <a href="{{ route('internships.index') }}" class="btn-secondary btn-lg">All internships</a>
                    </div>
                </div>
                <div class="card relative overflow-hidden p-6 sm:p-8">
                    <div class="absolute -top-16 -right-16 size-56 rounded-full bg-ai-50 blur-2xl" aria-hidden="true"></div>
                    <p class="relative text-sm font-bold text-ink">Internship at a glance</p>
                    <dl class="relative mt-5 grid grid-cols-2 gap-3">
                        @foreach ($facts as [$label, $value, $icon])
                            <div class="rounded-xl border border-line bg-canvas p-3">
                                <dt class="flex items-center gap-1.5 text-[11px] font-semibold text-muted"><x-glyph :name="$icon" class="size-3.5 text-brand-600" /> {{ $label }}</dt>
                                <dd class="mt-1 text-sm font-bold text-ink">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHY THIS INTERNSHIP ============ --}}
    @if ($i->why)
        <section class="section bg-white">
            <div class="container-x">
                <x-section-heading eyebrow="Why this internship?" title="Learn by doing work that matters." />
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($i->why as $n => $item)
                        <div class="card p-6" data-reveal>
                            <span class="font-mono text-xs font-bold text-brand-600">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="mt-3 text-lg font-bold text-ink">{{ $item['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted">{{ $item['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ PART OF THE TEAM FROM DAY ONE ============ --}}
    @if ($i->team_intro || $i->team_points)
        <section class="bg-navy-field hero-dark relative overflow-hidden py-20 sm:py-24">
            <div class="container-x relative grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="eyebrow-dark">Day one</p>
                    <h2 class="h-section mt-3 !text-white">You're part of the team from day one.</h2>
                    @if ($i->team_intro)<p class="mt-5 text-lg leading-relaxed text-navy-200">{{ $i->team_intro }}</p>@endif
                </div>
                @if ($i->team_points)
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach ($i->team_points as $point)
                            <li class="card-dark flex gap-3 p-4 text-sm text-navy-100" data-reveal><x-glyph name="check-circle" class="mt-0.5 size-5 shrink-0 text-signal-400" /> {{ $point }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @endif

    {{-- ============ WHAT YOU'LL WORK ON ============ --}}
    @if ($i->work_on)
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="What you'll work on" title="Real tasks, real tools, real feedback." />
                <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($i->work_on as $group)
                        <div class="card p-6" data-reveal>
                            <h3 class="text-lg font-bold text-ink">{{ $group['title'] ?? '' }}</h3>
                            <ul class="mt-4 space-y-2">
                                @foreach ((array) ($group['items'] ?? []) as $item)
                                    <li class="flex gap-2.5 text-sm text-body"><x-glyph name="arrow-right" class="mt-0.5 size-4 shrink-0 text-brand-600" /> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ TOOLKIT + SKILLS ============ --}}
    @if ($i->toolkit || $i->skills)
        <section class="section bg-white">
            <div class="container-x grid gap-12 lg:grid-cols-2">
                @if ($i->toolkit)
                    <div>
                        <x-section-heading eyebrow="Your professional toolkit" title="Tools professionals actually use." />
                        <ul class="mt-8 flex flex-wrap gap-2" data-reveal>
                            @foreach ($i->toolkit as $tool)
                                <li class="flex items-center gap-1.5 rounded-xl border border-line bg-white px-3.5 py-2 text-sm font-semibold text-ink shadow-xs"><span class="size-2 rounded-full bg-gradient-to-br from-brand-500 to-ai-500"></span>{{ $tool }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($i->skills)
                    <div>
                        <x-section-heading eyebrow="Skills you'll develop" title="Skills that compound for years." />
                        <ul class="mt-8 grid gap-2.5 sm:grid-cols-2" data-reveal>
                            @foreach ($i->skills as $skill)
                                <li class="flex items-center gap-2.5 rounded-xl bg-canvas px-4 py-3 text-sm font-semibold text-ink"><x-glyph name="trending-up" class="size-4 text-growth-600" /> {{ $skill }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============ REAL PROJECT EXPERIENCE (public projects only) ============ --}}
    @if ($projects->isNotEmpty())
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="Real project experience" title="The kind of work you could contribute to." />
                <div class="mt-12 grid gap-5 md:grid-cols-2">
                    @foreach ($projects as $link)
                        @php $p = $link->project; @endphp
                        <article class="card p-6" data-reveal>
                            <p class="text-xs font-semibold text-brand-700">{{ $p->publicClientLabel() }}</p>
                            <h3 class="mt-2 text-lg font-bold text-ink">{{ $p->title }}</h3>
                            @if ($p->description)<p class="mt-2 text-sm leading-relaxed text-muted">{{ $p->description }}</p>@endif
                            @if ($link->intern_contribution)
                                <div class="mt-4 rounded-xl bg-canvas p-3">
                                    <p class="text-xs font-bold text-ink">Intern contribution</p>
                                    <p class="mt-1 text-sm text-body">{{ $link->intern_contribution }}</p>
                                </div>
                            @endif
                            @if ($p->services || $p->technologies)
                                <ul class="mt-4 flex flex-wrap gap-1.5">
                                    @foreach ([...(array) $p->services, ...(array) $p->technologies] as $tag)<li class="chip">{{ $tag }}</li>@endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ REAL BRANDS. REAL WORK. ============ --}}
    @if ($i->show_brands && $brands->isNotEmpty())
        @include('internships.partials.brands')
    @endif

    {{-- ============ WHAT YOU'LL LEARN / WHO SHOULD APPLY / REQUIREMENTS ============ --}}
    @if ($i->learn || $i->who_should_apply || $i->requirements)
        <section class="section bg-white">
            <div class="container-x grid gap-6 lg:grid-cols-3">
                @foreach ([["What you'll learn", $i->learn, 'lightbulb', 'text-ai-600'], ['Who should apply', $i->who_should_apply, 'users', 'text-brand-600'], ['Requirements', $i->requirements, 'check-circle', 'text-growth-600']] as [$title, $items, $icon, $tone])
                    @if ($items)
                        <div class="card p-6 sm:p-7" data-reveal>
                            <span class="grid size-11 place-items-center rounded-xl bg-canvas {{ $tone }}"><x-glyph :name="$icon" /></span>
                            <h2 class="mt-4 text-xl font-bold text-ink">{{ $title }}</h2>
                            <ul class="mt-4 space-y-2.5">
                                @foreach ($items as $item)
                                    <li class="flex gap-2.5 text-sm leading-relaxed text-body"><x-glyph name="check" class="mt-0.5 size-4 shrink-0 {{ $tone }}" /> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ INTERNSHIP DETAILS ============ --}}
    @if ($facts)
        <section class="section-tight bg-canvas">
            <div class="container-x">
                <h2 class="text-2xl font-bold text-ink">Internship details</h2>
                <dl class="mt-6 grid overflow-hidden rounded-2xl border border-line bg-white sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($facts as [$label, $value, $icon])
                        <div class="flex items-center gap-3 border-b border-line p-4 sm:border-r">
                            <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700"><x-glyph :name="$icon" class="size-4" /></span>
                            <div><dt class="text-xs text-muted">{{ $label }}</dt><dd class="font-semibold text-ink">{{ $value }}</dd></div>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>
    @endif

    {{-- ============ WHAT YOU GET ============ --}}
    @if ($i->benefits)
        <section class="section bg-white">
            <div class="container-x">
                <x-section-heading eyebrow="What you get" title="More than a certificate." />
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($i->benefits as $benefit)
                        <div class="card p-6" data-reveal>
                            <x-glyph name="award" class="size-6 text-ai-600" />
                            <h3 class="mt-3 font-bold text-ink">{{ $benefit['title'] ?? '' }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $benefit['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ INTERNSHIP JOURNEY ============ --}}
    @if ($i->journey)
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="Internship journey" title="From day one to your final review." />
                <ol class="relative mt-12 space-y-4 before:absolute before:top-2 before:bottom-2 before:left-[19px] before:w-0.5 before:bg-gradient-to-b before:from-signal-500 before:via-ai-500 before:to-growth-600 md:grid md:gap-4 md:space-y-0 md:before:hidden {{ count($i->journey) >= 5 ? 'md:grid-cols-5' : 'md:grid-cols-4' }}">
                    @foreach ($i->journey as $n => $step)
                        <li class="relative flex gap-4 md:flex-col" data-reveal>
                            <span class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full bg-navy-900 font-mono text-xs font-bold text-white ring-4 ring-canvas">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="card flex-1 p-5">
                                <h3 class="font-bold text-ink">{{ $step['title'] ?? '' }}</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $step['text'] ?? '' }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- ============ CAREER GROWTH ============ --}}
    @if ($i->career_growth || $i->career_paths)
        <section class="section bg-white">
            <div class="container-x grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <x-section-heading eyebrow="Career growth" title="Where this can take you." :intro="$i->career_growth" />
                </div>
                @if ($i->career_paths)
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach ($i->career_paths as $path)
                            <li class="card flex items-center gap-3 p-4 font-semibold text-ink" data-reveal><x-glyph name="rocket" class="size-5 text-brand-600" /> {{ $path }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @endif

    <x-faq :items="$i->faqs ?? []" title="Internship FAQs" />

    {{-- ============ READY TO START? + APPLICATION FORM ============ --}}
    <section id="apply" class="section scroll-mt-20 bg-canvas">
        <div class="container-x grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
            <div>
                <p class="eyebrow">Ready to start?</p>
                <h2 class="h-section mt-3">Apply for the {{ $i->title }} internship.</h2>
                <p class="mt-5 text-lg leading-relaxed text-muted">At Advertally, you're not just learning about digital marketing and technology. You're getting exposure to how those skills are applied to real businesses.</p>
                <ul class="mt-8 space-y-3 text-[15px] text-ink">
                    @foreach (['Every application is reviewed by a person', 'Shortlisted candidates get a short conversation', 'We reply whether or not you are selected'] as $point)
                        <li class="flex gap-3"><x-glyph name="check-circle" class="mt-0.5 size-5 text-brand-600" /> {{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            @if (session('application_submitted'))
                {{-- ============ SUCCESS ============ --}}
                <div class="card self-start p-8 text-center sm:p-10" role="status">
                    <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-growth-50 text-growth-600"><x-glyph name="check-circle" class="size-8" /></span>
                    <h3 class="mt-5 text-2xl font-bold text-ink">Application received — thank you, {{ str(session('application_submitted'))->before(' ') }}!</h3>
                    <p class="mt-3 text-muted">We've emailed you a confirmation. Our team will review your application and get back to you.</p>
                    <a href="{{ route('internships.index') }}" class="btn-secondary mt-7">See other internships</a>
                </div>
            @elseif (! $open)
                <div class="card self-start p-8 text-center">
                    <h3 class="text-xl font-bold text-ink">Applications for this internship are closed.</h3>
                    <a href="{{ route('internships.index') }}" class="btn-primary mt-6">See open internships</a>
                </div>
            @else
                <form method="POST" action="{{ route('internships.apply', $i->slug) }}" enctype="multipart/form-data" class="card relative space-y-5 self-start p-6 sm:p-8" novalidate>
                    @csrf
                    <x-form.guard />
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">Please check the highlighted fields.</div>
                    @endif
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field name="name" label="Full name" required autocomplete="name" />
                        <x-form.field name="email" type="email" label="Email" required autocomplete="email" />
                        <x-form.field name="phone" type="tel" label="Phone" required autocomplete="tel" />
                        <x-form.field name="city" label="City" autocomplete="address-level2" />
                        <x-form.field name="education" label="College / course" required placeholder="e.g. BBA, Delhi University" />
                        <x-form.field name="graduation_year" label="Graduation year" inputmode="numeric" placeholder="2027" />
                        <x-form.field name="linkedin_url" type="url" label="LinkedIn profile" placeholder="https://linkedin.com/in/…" />
                        <x-form.field name="portfolio_url" type="url" label="Portfolio / GitHub" placeholder="https://…" />
                    </div>
                    <x-form.field name="availability" label="When can you start?" placeholder="e.g. Immediately, or from 1 June" />
                    <x-form.field name="motivation" type="textarea" label="Why do you want this internship?" required rows="4" placeholder="Tell us what excites you about this role and anything you've built, written or run." />
                    <div>
                        <label for="f-resume" class="field-label">Résumé (PDF or Word, max 5 MB) <span class="text-red-700" aria-hidden="true">*</span></label>
                        <input id="f-resume" type="file" name="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required
                            class="block w-full rounded-xl border border-dashed border-navy-200 bg-white p-3 text-sm text-body file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-brand-700">
                        @error('resume')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <x-form.consent text="I agree to Advertally storing my application and résumé to assess it for this and similar internships." />
                    <button class="btn-primary btn-lg w-full" data-track="internship_apply_submit">Submit application <x-glyph name="arrow-right" class="size-4" /></button>
                </form>
            @endif
        </div>
    </section>
@endsection
