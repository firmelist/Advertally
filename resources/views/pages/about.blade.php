@extends('layouts.app')

@section('title', 'About Advertally — Your Trusted Digital Growth Partner')
@section('description', 'Advertally helps Indian small and medium businesses grow with digital marketing, websites, dedicated teams, CRM and custom software — one team, one point of contact.')

@section('content')
<section class="bg-hero relative overflow-hidden">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_80%)]"></div>
    <div class="container-x relative pt-8 pb-20">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'About']]" />
        <div class="mt-10 grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <span class="eyebrow">About Advertally</span>
                <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">We help India's growing businesses win online.</h1>
                <p class="lead-text mt-6">Small and medium businesses are the backbone of India's economy — yet most are underserved by agencies that either chase big brands or sell cheap, one-size-fits-all packages.</p>
                <p class="lead-text mt-4">We started Advertally to be the partner we wished existed: one accountable team that understands business, speaks plainly, and connects marketing, websites, people and software into a single growth engine.</p>
            </div>
            <div class="lg:col-span-5">
                <div class="card p-8">
                    <x-trust-strip class="!grid-cols-2" />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-x">
        <x-section-heading eyebrow="Why SMEs trust us" title="What makes us different" />
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['users', 'One team, one contact', 'A dedicated account manager coordinates marketers, designers and developers for you.'],
                ['bar-chart', 'Results you can see', 'Monthly reports on leads, cost per lead and ROI — not vanity metrics.'],
                ['rupee', 'Honest ₹ pricing', 'Published prices, GST invoices and no hidden charges.'],
                ['workflow', 'Everything connected', 'Ads → website → CRM → WhatsApp follow-up. Nothing falls through the cracks.'],
                ['lock', 'You own everything', 'Your domain, code, ad accounts and data always belong to you.'],
                ['heart', 'Long-term partnership', 'We grow with you — from your first campaign to your custom ERP.'],
            ] as [$icon, $title, $text])
                <div class="card p-6">
                    <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-lucide :name="$icon" class="size-5" /></span>
                    <h3 class="mt-5 text-lg font-bold">{{ $title }}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-brand-950">
    <div class="container-x grid gap-12 lg:grid-cols-2">
        <x-section-heading dark align="left" eyebrow="Our values" title="How we work, every single day" />
        <ul class="space-y-5">
            @foreach ([
                ['Clarity over jargon', 'If we can\'t explain it simply, we don\'t sell it.'],
                ['Outcomes over outputs', 'Posts and pages matter only if they bring business.'],
                ['Speed with care', 'Fast replies, thoughtful work, no shortcuts on quality or security.'],
                ['Honesty, always', 'We\'ll tell you when something isn\'t working — or isn\'t worth doing.'],
            ] as [$t, $d])
                <li class="flex gap-4">
                    <span class="mt-1 grid size-6 shrink-0 place-items-center rounded-full bg-accent-500 text-ink"><x-lucide name="check" class="size-4" /></span>
                    <div><p class="font-semibold text-white">{{ $t }}</p><p class="mt-1 text-sm text-white/70">{{ $d }}</p></div>
                </li>
            @endforeach
        </ul>
    </div>
</section>

<x-testimonials :testimonials="$testimonials" />
<x-cta-band />
@endsection
