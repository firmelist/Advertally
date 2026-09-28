@extends('layouts.app')

@section('title', 'Advertally — Digital Marketing, Websites, CRM & Software for Growing SMEs in India')
@section('description', 'One trusted digital partner for Indian SMEs: SEO, Google & Meta ads, websites, dedicated developers, CRM, WhatsApp automation and custom software. Get a free audit.')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-hero relative overflow-hidden">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_85%)]"></div>
    <div class="container-x relative grid items-center gap-12 pt-12 pb-16 lg:grid-cols-12 lg:pt-20 lg:pb-24">
        <div class="lg:col-span-7">
            <span class="eyebrow"><span class="size-1.5 rounded-full bg-accent-500"></span> Built for India's growing businesses</span>
            <h1 class="h-display mt-5">
                From leads to automation —
                <span class="relative whitespace-nowrap text-brand-600">
                    one digital partner
                    <svg class="absolute -bottom-2 left-0 w-full text-accent-500" viewBox="0 0 300 12" fill="none" preserveAspectRatio="none" aria-hidden="true"><path d="M2 9c60-6 180-8 296-3" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                </span>
                for growth.
            </h1>
            <p class="lead-text mt-6 max-w-2xl">
                Digital marketing, websites, dedicated teams, CRM and custom software for small and medium businesses.
                <strong class="font-semibold text-ink">One team. One point of contact. Measurable results.</strong>
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('audit.create') }}" class="btn-cta">Get Free Growth Audit <x-lucide name="arrow-right" class="size-4" /></a>
                <a href="{{ whatsapp_link('growing my business') }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-whatsapp-glyph class="size-5" /> WhatsApp Us</a>
            </div>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-muted">
                <span class="flex items-center gap-2"><span class="flex text-accent-500">@for ($i = 0; $i < 5; $i++)<svg class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>@endfor</span><strong class="text-ink">{{ setting('google_rating', '4.9') }}/5</strong> on Google</span>
                <span class="flex items-center gap-2"><x-lucide name="shield" class="size-4 text-teal-700" /> No lock-in contracts</span>
                <span class="flex items-center gap-2"><x-lucide name="rupee" class="size-4 text-teal-700" /> Transparent ₹ pricing</span>
            </div>
        </div>

        {{-- Hero card: live-looking results dashboard --}}
        <div class="relative lg:col-span-5">
            <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-br from-brand-200/60 via-transparent to-accent-200/50 blur-2xl"></div>
            <div class="card p-6 shadow-[var(--shadow-lift)]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-muted">Monthly growth report</p>
                        <p class="font-display text-lg font-bold">Your business, month 3</p>
                    </div>
                    <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-semibold text-teal-700">Illustrative</span>
                </div>
                <div class="mt-5 grid grid-cols-3 gap-3">
                    @foreach ([['Leads', '184', '+62%'], ['Cost / lead', '₹312', '-28%'], ['Calls', '97', '+41%']] as [$l, $v, $d])
                        <div class="rounded-xl bg-canvas p-3">
                            <p class="text-[11px] text-muted">{{ $l }}</p>
                            <p class="mt-1 font-display text-xl font-extrabold">{{ $v }}</p>
                            <p class="text-[11px] font-semibold text-teal-700">{{ $d }}</p>
                        </div>
                    @endforeach
                </div>
                {{-- Mini bar chart --}}
                <div class="mt-5 flex h-28 items-end gap-2 rounded-xl border border-line p-3" aria-hidden="true">
                    @foreach ([28, 35, 32, 44, 52, 49, 61, 70, 66, 78, 86, 94] as $b)
                        <div class="flex-1 rounded-t-md {{ $loop->last ? 'bg-accent-500' : 'bg-brand-500/80' }}" style="height: {{ $b }}%"></div>
                    @endforeach
                </div>
                <ul class="mt-5 space-y-2.5 text-sm">
                    @foreach ([['search', 'SEO', '31 keywords on page 1'], ['target', 'Google Ads', 'ROAS 4.1x'], ['message', 'WhatsApp bot', '212 chats auto-answered'], ['database', 'CRM', '0 leads missed']] as [$ic, $k, $v])
                        <li class="flex items-center justify-between gap-3">
                            <span class="flex items-center gap-2 text-muted"><x-lucide :name="$ic" class="size-4 text-brand-600" /> {{ $k }}</span>
                            <span class="font-medium text-ink">{{ $v }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Client logos --}}
    @if ($logos->isNotEmpty())
        <div class="relative border-y border-line bg-white/70">
            <div class="container-x flex flex-col items-center gap-4 py-6 lg:flex-row lg:gap-10">
                <p class="shrink-0 text-xs font-semibold tracking-wider text-muted uppercase">Trusted by growing brands</p>
                <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-3 lg:justify-start">
                    @foreach ($logos as $logo)
                        @if ($logo->logo)
                            <img src="{{ asset('storage/'.$logo->logo) }}" alt="{{ $logo->name }}" class="h-7 w-auto opacity-60 grayscale transition hover:opacity-100 hover:grayscale-0" loading="lazy">
                        @else
                            <span class="font-display text-base font-bold tracking-tight text-slate-400">{{ $logo->name }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>

{{-- ============ GROWTH LADDER ============ --}}
<section class="section">
    <div class="container-x">
        <x-section-heading eyebrow="The SME growth ladder"
            title="Start where you are. <span class='text-brand-600'>Grow step by step.</span>"
            subtitle="Most businesses begin with marketing to get more leads. As you grow, we add the website, people, CRM and software you need — all connected, all under one roof." />
        <div class="mt-14">
            <x-growth-ladder :hubs="$hubs" />
        </div>
    </div>
</section>

{{-- ============ PROBLEMS WE SOLVE ============ --}}
<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading eyebrow="Sound familiar?" title="Problems we solve for business owners every day" />
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['rupee', 'Money spent, no leads', 'You pay for ads or an agency but the phone doesn\'t ring.', 'Performance campaigns with cost-per-lead tracking.', 'digital-marketing'],
                ['monitor', 'Outdated website', 'Slow, not mobile-friendly and nobody fills the form.', 'Fast, conversion-focused websites with WhatsApp built in.', 'web-development'],
                ['database', 'Leads lost in Excel & WhatsApp', 'Follow-ups are missed and hot leads go cold.', 'CRM + automated WhatsApp follow-ups.', 'solutions'],
                ['users', 'Can\'t find reliable talent', 'Hiring takes months and freelancers disappear.', 'Dedicated developers & marketers in 48 hours.', 'hire'],
            ] as [$icon, $title, $pain, $fix, $hub])
                <a href="{{ route('services.hub', $hub) }}" class="card-hover group flex flex-col p-6">
                    <span class="grid size-11 place-items-center rounded-xl bg-accent-50 text-accent-600"><x-lucide :name="$icon" class="size-5" /></span>
                    <h3 class="mt-5 text-lg font-bold">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $pain }}</p>
                    <p class="mt-4 flex flex-1 items-start gap-2 border-t border-dashed border-line pt-4 text-sm font-medium text-ink">
                        <x-lucide name="check-circle" class="mt-0.5 size-4 shrink-0 text-teal-700" /> {{ $fix }}
                    </p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-700">See how <x-lucide name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" /></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ SERVICES OVERVIEW (tabs) ============ --}}
<section class="section" x-data="{ tab: 0 }">
    <div class="container-x">
        <x-section-heading eyebrow="What we do" title="Everything your business needs to grow online" subtitle="Five connected service lines. Pick one, or let us build your complete lead-to-sale system." />
        <div class="mt-10 -mx-4 overflow-x-auto px-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="mx-auto flex w-max gap-2 rounded-2xl border border-line bg-canvas p-1.5" role="tablist">
                @foreach ($hubs as $i => $hub)
                    <button type="button" role="tab" @click="tab = {{ $i }}" :aria-selected="tab === {{ $i }}"
                            class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold whitespace-nowrap transition"
                            :class="tab === {{ $i }} ? 'bg-white text-brand-700 shadow-[var(--shadow-card)]' : 'text-muted hover:text-ink'">
                        <x-lucide :name="$hub->icon" class="size-4" /> {{ $hub->title }}
                    </button>
                @endforeach
            </div>
        </div>
        @foreach ($hubs as $i => $hub)
            <div x-show="tab === {{ $i }}" @if ($i) x-cloak @endif class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($hub->children->take(7) as $child)
                    <a href="{{ route('services.show', [$hub->slug, $child->slug]) }}" class="card-hover group p-6">
                        <span class="grid size-10 place-items-center rounded-lg bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-lucide :name="$child->icon" class="size-5" /></span>
                        <h3 class="mt-4 font-sans text-base font-bold">{{ $child->title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $child->short_description }}</p>
                        @if ($child->starting_price)
                            <p class="mt-4 text-xs text-muted">From <strong class="text-ink">{{ inr($child->starting_price) }}</strong>{{ ['month' => '/mo', 'hour' => '/hr'][$child->price_unit] ?? '' }}</p>
                        @endif
                    </a>
                @endforeach
                <a href="{{ $hub->url }}" class="flex flex-col justify-between rounded-[var(--radius-card)] bg-brand-600 p-6 text-white transition hover:bg-brand-700">
                    <x-lucide :name="$hub->icon" class="size-7 opacity-80" />
                    <div>
                        <p class="font-display text-lg font-bold">All {{ $hub->title }}</p>
                        <p class="mt-1 inline-flex items-center gap-1 text-sm text-white/80">View overview & pricing <x-lucide name="arrow-right" class="size-4" /></p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ RESULTS / CASE STUDIES ============ --}}
@if ($caseStudies->isNotEmpty())
<section class="section bg-brand-950">
    <div class="container-x">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <x-section-heading dark eyebrow="Results" title="Real growth for real Indian businesses" align="left" subtitle="We measure success in enquiries, sales and time saved — not likes." />
            <a href="{{ route('case-studies.index') }}" class="btn-on-dark shrink-0">All case studies <x-lucide name="arrow-right" class="size-4" /></a>
        </div>
        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($caseStudies as $cs)
                <x-case-study-card :cs="$cs" />
            @endforeach
        </div>
        <div class="mt-14 border-t border-white/10 pt-10">
            <x-trust-strip dark />
        </div>
    </div>
</section>
@endif

{{-- ============ INDUSTRIES ============ --}}
<section class="section">
    <div class="container-x">
        <x-section-heading eyebrow="Industries" title="We speak your industry's language" subtitle="Proven playbooks for the sectors that power India's SME economy." />
        <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach (collect(config('advertally.industries'))->except('other') as $key => $label)
                <a href="{{ route('contact', ['industry' => $key]) }}#quote" class="card-hover group flex items-center gap-4 p-5">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-lucide :name="$key" class="size-5" /></span>
                    <span class="text-sm font-semibold text-ink">{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ PACKAGES TEASER ============ --}}
@if ($plans->isNotEmpty())
<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading eyebrow="Simple pricing" title="Marketing plans that fit a growing business" subtitle="Transparent monthly pricing in ₹. Start small, upgrade anytime. Ad spend is paid directly to Google/Meta from your own account." />
        <div class="mx-auto mt-14 grid max-w-6xl gap-6 lg:grid-cols-3 lg:items-center">
            @foreach ($plans as $plan)
                <x-pricing-card :plan="$plan" />
            @endforeach
        </div>
        <p class="mt-10 text-center text-sm text-muted">
            Need a website, CRM or developers too? <a href="{{ route('pricing') }}" class="font-semibold text-brand-700 underline underline-offset-4">See all pricing & build your own plan →</a>
        </p>
    </div>
</section>
@endif

{{-- ============ HOW WE WORK ============ --}}
<section class="section">
    <div class="container-x">
        <x-section-heading eyebrow="How we work" title="A simple, transparent process" />
        <ol class="relative mt-14 grid gap-8 md:grid-cols-4">
            <div class="absolute top-6 right-[12%] left-[12%] hidden h-px bg-gradient-to-r from-brand-200 via-brand-400 to-accent-400 md:block" aria-hidden="true"></div>
            @foreach ([
                ['search', 'Free audit', 'We review your website, ads, Google presence and competitors.'],
                ['file-text', 'Growth plan', 'You get a clear 90-day plan with targets and budget.'],
                ['zap', 'Execute', 'Specialists deliver; one account manager keeps you updated.'],
                ['bar-chart', 'Report monthly', 'Leads, cost and ROI every month — then we improve.'],
            ] as $i => [$icon, $title, $text])
                <li class="relative text-center">
                    <span class="relative mx-auto grid size-12 place-items-center rounded-full bg-white text-brand-700 ring-8 ring-white shadow-[var(--shadow-card)]"><x-lucide :name="$icon" class="size-5" /></span>
                    <p class="mt-5 text-xs font-bold tracking-wider text-accent-600 uppercase">Step {{ $i + 1 }}</p>
                    <h3 class="mt-1 text-lg font-bold">{{ $title }}</h3>
                    <p class="mx-auto mt-2 max-w-60 text-sm text-muted">{{ $text }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<x-testimonials :testimonials="$testimonials" />

{{-- ============ FREE TOOLS ============ --}}
<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading eyebrow="Free tools" title="Check where you stand — in under a minute" />
        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            <div class="card flex flex-col p-8">
                <span class="grid size-12 place-items-center rounded-xl bg-accent-50 text-accent-600"><x-lucide name="gauge" class="size-6" /></span>
                <h3 class="mt-5 text-2xl font-bold">Free Website Audit</h3>
                <p class="mt-2 text-muted">Get an instant score for speed, SEO, mobile-readiness and lead capture — plus a PDF report with fixes, in plain English.</p>
                <form action="{{ route('audit.create') }}" method="GET" class="mt-6 flex flex-col gap-2 sm:flex-row">
                    <label for="home-audit-url" class="sr-only">Your website</label>
                    <input id="home-audit-url" name="url" type="text" inputmode="url" placeholder="yourbusiness.com" class="field flex-1">
                    <button class="btn-cta shrink-0">Audit my website</button>
                </form>
            </div>

            <div class="card p-8" x-data="roiCalc">
                <span class="grid size-12 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-lucide name="calculator" class="size-6" /></span>
                <h3 class="mt-5 text-2xl font-bold">Marketing ROI Calculator</h3>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    @foreach ([['spend', 'Monthly ad spend (₹)', 5000, 300000, 5000], ['cpl', 'Cost per lead (₹)', 50, 3000, 50], ['close', 'Leads that buy (%)', 1, 50, 1], ['order', 'Average order value (₹)', 1000, 500000, 1000]] as [$m, $label, $min, $max, $step])
                        <label class="block">
                            <span class="flex justify-between text-xs font-medium text-muted">{{ $label }} <strong class="text-ink" x-text="{{ $m === 'close' ? $m.' + \'%\'' : 'inr('.$m.')' }}"></strong></span>
                            <input type="range" x-model.number="{{ $m }}" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" class="mt-2 w-full accent-brand-600">
                        </label>
                    @endforeach
                </div>
                <div class="mt-6 grid grid-cols-3 gap-3 rounded-xl bg-brand-950 p-4 text-white">
                    <div><p class="text-[11px] text-white/60">Leads / month</p><p class="font-display text-xl font-extrabold" x-text="leads"></p></div>
                    <div><p class="text-[11px] text-white/60">Revenue</p><p class="font-display text-xl font-extrabold" x-text="inr(revenue)"></p></div>
                    <div><p class="text-[11px] text-white/60">ROI</p><p class="font-display text-xl font-extrabold text-accent-400" x-text="roi + '%'"></p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-faq :faqs="$faqs" />

{{-- ============ FINAL CTA WITH FORM ============ --}}
<section class="section" id="quote">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <x-section-heading align="left" eyebrow="Let's talk" title="Get your free growth plan" subtitle="Tell us about your business. Within 2 working hours, a senior strategist will call you with honest recommendations — even if we're not the right fit." />
            <ul class="mt-8 space-y-4">
                @foreach (['Free audit of your website & marketing', 'A 90-day plan with targets and budget', 'Transparent quote — no hidden charges', 'No obligation, no pushy sales calls'] as $li)
                    <li class="flex items-center gap-3 font-medium"><span class="grid size-6 place-items-center rounded-full bg-teal-50 text-teal-700"><x-lucide name="check" class="size-4" /></span> {{ $li }}</li>
                @endforeach
            </ul>
            <div class="mt-8 flex items-center gap-4 rounded-2xl border border-line p-4">
                <span class="grid size-12 place-items-center rounded-full bg-[#128C4A] text-white"><x-whatsapp-glyph class="size-6" /></span>
                <div>
                    <p class="text-sm text-muted">Prefer WhatsApp?</p>
                    <a href="{{ whatsapp_link('a free growth plan') }}" target="_blank" rel="noopener" class="font-semibold text-ink hover:text-brand-700">Chat with a strategist now →</a>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7">
            <div class="card p-6 sm:p-8">
                <x-lead-form form-type="quote" form-id="home-quote" variant="full" />
            </div>
        </div>
    </div>
</section>

@endsection
