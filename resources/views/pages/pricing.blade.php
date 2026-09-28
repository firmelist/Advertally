@extends('layouts.app')

@section('title', 'Pricing — Digital Marketing, Website, CRM & Developer Packages | Advertally')
@section('description', 'Transparent ₹ pricing for SEO, ads, social media, websites, e-commerce, CRM automation and dedicated developers. Build your own plan and save up to 30%.')
@section('whatsapp_context', 'pricing')

@php
    $discounts = ['monthly' => 0, 'quarterly' => 0.10, 'yearly' => 0.20];
    $tabs = \App\Models\PricingPlan::CATEGORIES;
@endphp

@section('content')

<section class="bg-hero relative overflow-hidden" x-data="{ billing: 'monthly', discounts: @js($discounts), tab: 'marketing' }">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_70%)]"></div>
    <div class="container-x relative pt-12 pb-20">
        <x-breadcrumbs :items="[['label' => 'Home', 'url' => route('home')], ['label' => 'Pricing']]" />
        <div class="mx-auto mt-8 max-w-3xl text-center">
            <span class="eyebrow">Pricing</span>
            <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">Simple, honest pricing for growing businesses</h1>
            <p class="lead-text mt-5">No hidden charges. No long lock-ins. GST invoice for every payment. Start with one service and add more as you grow.</p>
        </div>

        {{-- Billing toggle --}}
        <div class="mt-10 flex justify-center">
            <div class="inline-flex rounded-full border border-line bg-white p-1 shadow-[var(--shadow-card)]" role="radiogroup" aria-label="Billing period">
                @foreach (['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly'] as $k => $label)
                    <button type="button" role="radio" :aria-checked="billing === '{{ $k }}'" @click="billing = '{{ $k }}'"
                            class="flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold transition sm:px-5"
                            :class="billing === '{{ $k }}' ? 'bg-brand-600 text-white' : 'text-muted hover:text-ink'">
                        {{ $label }}
                        @if ($discounts[$k] > 0)<span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold" :class="billing === '{{ $k }}' ? 'bg-accent-500 text-ink' : 'bg-accent-50 text-accent-700'">-{{ $discounts[$k] * 100 }}%</span>@endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Category tabs --}}
        <div class="mt-8 -mx-4 overflow-x-auto px-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="mx-auto flex w-max gap-2" role="tablist">
                @foreach ($tabs as $k => $label)
                    @if ($plans->has($k))
                        <button type="button" role="tab" @click="tab = '{{ $k }}'" :aria-selected="tab === '{{ $k }}'"
                                class="rounded-xl border px-4 py-2 text-sm font-semibold whitespace-nowrap transition"
                                :class="tab === '{{ $k }}' ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-line bg-white text-muted hover:text-ink'">{{ $label }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        @foreach ($plans as $category => $group)
            <div x-show="tab === '{{ $category }}'" @if ($category !== 'marketing') x-cloak @endif
                 @class(['mx-auto mt-14 grid gap-6 lg:items-center', 'max-w-6xl lg:grid-cols-3' => $group->count() >= 3, 'max-w-4xl lg:grid-cols-2' => $group->count() < 3])>
                @foreach ($group as $plan)
                    <x-pricing-card :plan="$plan" :billing="true" />
                @endforeach
            </div>
        @endforeach
        <p class="mt-10 text-center text-xs text-muted">* Ad spend is paid directly to Google/Meta from your own account. Prices exclude 18% GST. Quarterly/yearly discounts apply to monthly plans paid in advance.</p>
    </div>
</section>

{{-- ============ BUILD YOUR OWN PLAN ============ --}}
<section id="builder" class="section bg-canvas" x-data="planBuilder(@js($builderItems), @js($discounts))">
    <div class="container-x">
        <x-section-heading eyebrow="Build your own plan" title="Pick what you need. See your price instantly." subtitle="Combine marketing, a website and CRM to unlock the SME Bundle — an extra 10% off every month." />

        <div class="mt-12 grid gap-8 lg:grid-cols-12">
            <div class="space-y-8 lg:col-span-8">
                @foreach (['marketing' => ['trending-up', 'Digital marketing'], 'websites' => ['monitor', 'Websites'], 'crm' => ['workflow', 'CRM & automation'], 'hire' => ['users', 'Dedicated resources']] as $group => [$icon, $label])
                    <div>
                        <p class="flex items-center gap-2 text-sm font-bold text-ink"><x-lucide :name="$icon" class="size-4 text-brand-600" /> {{ $label }}</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <template x-for="item in items.filter(i => i.group === '{{ $group }}')" :key="item.key">
                                <button type="button" @click="toggle(item.key)"
                                        class="flex items-center justify-between gap-3 rounded-xl border bg-white p-4 text-left transition"
                                        :class="selected.includes(item.key) ? 'border-brand-600 ring-2 ring-brand-600/15' : 'border-line hover:border-brand-300'"
                                        :aria-pressed="selected.includes(item.key)">
                                    <span class="flex items-center gap-3">
                                        <span class="grid size-5 shrink-0 place-items-center rounded-md border transition"
                                              :class="selected.includes(item.key) ? 'border-brand-600 bg-brand-600 text-white' : 'border-line'">
                                            <svg x-show="selected.includes(item.key)" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        <span class="text-sm font-medium text-ink" x-text="item.label"></span>
                                    </span>
                                    <span class="shrink-0 text-sm font-semibold text-ink"><span x-text="inr(item.price)"></span><span class="text-xs font-normal text-muted" x-text="item.unit === 'month' ? '/mo' : ' once'"></span></span>
                                </button>
                            </template>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-4">
                <div class="sticky top-28 rounded-[var(--radius-card)] bg-brand-950 p-6 text-white shadow-[var(--shadow-lift)]">
                    <p class="text-sm font-semibold text-accent-300">Your plan</p>
                    <div class="mt-4 flex rounded-full bg-white/10 p-1 text-xs font-semibold">
                        @foreach (['monthly' => 'Monthly', 'quarterly' => 'Quarterly -10%', 'yearly' => 'Yearly -20%'] as $k => $label)
                            <button type="button" @click="billing = '{{ $k }}'" class="flex-1 rounded-full px-2 py-1.5 transition" :class="billing === '{{ $k }}' ? 'bg-white text-ink' : 'text-white/70'">{{ $label }}</button>
                        @endforeach
                    </div>
                    <dl class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-white/70">Monthly services</dt><dd x-text="inr(monthly)"></dd></div>
                        <div class="flex justify-between" x-show="bundleDiscount > 0" x-cloak><dt class="text-accent-300">SME Bundle discount</dt><dd class="text-accent-300">-10%</dd></div>
                        <div class="flex justify-between" x-show="discounts[billing] > 0" x-cloak><dt class="text-accent-300">Advance billing discount</dt><dd class="text-accent-300" x-text="'-' + (discounts[billing] * 100) + '%'"></dd></div>
                        <div class="flex items-baseline justify-between border-t border-white/10 pt-3"><dt class="font-semibold">Per month</dt><dd class="font-display text-3xl font-extrabold" x-text="inr(effectiveMonthly)"></dd></div>
                        <div class="flex justify-between"><dt class="text-white/70">One-time setup</dt><dd x-text="inr(oneTime)"></dd></div>
                    </dl>
                    <p class="mt-4 text-xs text-white/50" x-show="!selected.length">Select services on the left to build your plan.</p>
                    <div x-show="selected.length" x-cloak class="mt-6 border-t border-white/10 pt-6">
                        <p class="text-sm font-semibold">Get this as a formal quote</p>
                        <x-lead-form form-type="plan_builder" form-id="plan-builder" variant="compact" button="Send Me This Quote" dark class="mt-4 space-y-3 [&_.field-label]:text-white/80">
                            <input type="hidden" name="plan_summary" :value="summary">
                            <input type="hidden" name="plan_total" :value="inr(effectiveMonthly) + '/mo (' + billing + ') + ' + inr(oneTime) + ' one-time'">
                        </x-lead-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Comparison / trust --}}
<section class="section">
    <div class="container-x grid gap-6 md:grid-cols-3">
        @foreach ([
            ['rupee', 'No hidden charges', 'What you see is what you pay. Ad spend goes directly to Google/Meta from your own account.'],
            ['calendar', 'No long lock-ins', 'Month-to-month after the first 3 months. Upgrade or pause anytime.'],
            ['file-text', 'GST invoice & agreement', 'Proper invoice and a simple service agreement for every engagement.'],
        ] as [$icon, $title, $text])
            <div class="card p-6">
                <x-lucide :name="$icon" class="size-6 text-brand-600" />
                <h3 class="mt-4 text-lg font-bold">{{ $title }}</h3>
                <p class="mt-2 text-sm text-muted">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

<x-faq :faqs="$faqs" title="Pricing questions" />

<x-cta-band title="Not sure which plan fits?" subtitle="Tell us your goals and budget — we'll recommend the smallest plan that can hit them." context="pricing" />

@endsection
