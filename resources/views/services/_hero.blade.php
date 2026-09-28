{{-- Shared hero for service hub + child pages. Expects $service, $crumbs, $formType, $formServices --}}
<section class="bg-hero relative overflow-hidden">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent_80%)]"></div>
    <div class="container-x relative grid gap-12 pt-8 pb-16 lg:grid-cols-12 lg:pb-20">
        <div class="lg:col-span-7 lg:pt-6">
            <x-breadcrumbs :items="$crumbs" />
            <span class="eyebrow mt-8"><x-lucide :name="$service->icon" class="size-3.5" /> Step {{ $service->ladder['step'] ?? '' }} · {{ $service->ladder['label'] ?? '' }}</span>
            <h1 class="h-display mt-5 !text-4xl sm:!text-5xl">{{ $service->hero_title ?: $service->title }}</h1>
            <p class="lead-text mt-6 max-w-2xl">{{ $service->hero_subtitle ?: $service->short_description }}</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="#quote" class="btn-cta">Get a Free Proposal <x-lucide name="arrow-right" class="size-4" /></a>
                <a href="{{ whatsapp_link($service->title) }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-whatsapp-glyph class="size-5" /> WhatsApp Us</a>
            </div>
            @if ($service->starting_price)
                <p class="mt-6 text-sm text-muted">
                    Starting at <strong class="text-lg text-ink">{{ inr($service->starting_price) }}</strong>{{ ['month' => ' / month', 'hour' => ' / hour', 'project' => ' / project'][$service->price_unit] ?? '' }} <span class="text-xs">+ GST</span>
                </p>
            @endif
        </div>
        <div class="lg:col-span-5" id="quote">
            <div class="card p-6 shadow-[var(--shadow-lift)] sm:p-7">
                <x-lead-form :form-type="$formType ?? 'quote'" :form-id="'svc-'.$service->id" :variant="$formVariant ?? 'compact'"
                             :services="$formServices ?? []" :show-website="($formVariant ?? 'compact') === 'compact'"
                             title="Get a free proposal" subtitle="Reply within 2 working hours. No obligation."
                             :button="$formButton ?? 'Get My Free Proposal'" />
            </div>
        </div>
    </div>
</section>
