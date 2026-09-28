@props([
    'title' => 'Ready to grow with one trusted digital partner?',
    'subtitle' => 'Get a free audit of your website and marketing, plus a practical 90-day growth plan. No obligation.',
    'context' => null,
])
<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="bg-navy-glow relative mx-auto max-w-7xl overflow-hidden rounded-[2rem] px-6 py-14 sm:px-12 lg:py-16">
        <div class="bg-grid pointer-events-none absolute inset-0 opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]"></div>
        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <h2 class="text-3xl leading-tight font-extrabold text-white sm:text-4xl">{{ $title }}</h2>
                <p class="mt-4 text-lg text-white/75">{{ $subtitle }}</p>
            </div>
            <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                <a href="{{ route('audit.create') }}" class="btn-cta">Get Free Audit <x-lucide name="arrow-right" class="size-4" /></a>
                <a href="{{ whatsapp_link($context) }}" target="_blank" rel="noopener" class="btn-on-dark"><x-whatsapp-glyph class="size-5" /> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>
