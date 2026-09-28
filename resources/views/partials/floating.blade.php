@php $pageContext = trim($__env->yieldContent('whatsapp_context')) ?: null; @endphp

{{-- Floating WhatsApp (desktop) --}}
<a href="{{ whatsapp_link($pageContext) }}" target="_blank" rel="noopener"
   class="group fixed right-6 bottom-6 z-40 hidden items-center gap-2 rounded-full bg-[#128C4A] py-3 pr-5 pl-3 text-sm font-semibold text-white shadow-[0_10px_30px_-10px_rgb(18_140_74/0.8)] transition hover:-translate-y-0.5 lg:flex"
   aria-label="Chat with us on WhatsApp">
    <span class="grid size-8 place-items-center rounded-full bg-white/15"><x-whatsapp-glyph class="size-5" /></span>
    Chat on WhatsApp
</a>

{{-- Sticky mobile action bar --}}
<nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-3 border-t border-line bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur lg:hidden" aria-label="Quick actions">
    <a href="{{ tel_link() }}" class="flex flex-col items-center gap-1 py-2.5 text-xs font-semibold text-ink">
        <x-lucide name="call" class="size-5 text-brand-600" /> Call
    </a>
    <a href="{{ whatsapp_link($pageContext) }}" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 py-2.5 text-xs font-semibold text-[#128C4A]">
        <x-whatsapp-glyph class="size-5" /> WhatsApp
    </a>
    <a href="{{ route('contact') }}#quote" class="m-1.5 flex flex-col items-center justify-center gap-0.5 rounded-xl bg-accent-500 text-xs font-bold text-ink">
        <x-lucide name="file-text" class="size-5" /> Get Quote
    </a>
</nav>

{{-- Exit-intent popup (desktop, once per week) --}}
<div x-data="exitIntent" x-cloak x-show="open" x-transition.opacity
     class="fixed inset-0 z-[60] grid place-items-center bg-brand-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="exit-title"
     @keydown.escape.window="open = false">
    <div @click.outside="open = false" class="relative w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl md:grid md:grid-cols-5">
        <button type="button" @click="open = false" class="absolute top-3 right-3 z-10 grid size-9 place-items-center rounded-full bg-white/80 text-ink hover:bg-canvas" aria-label="Close">
            <x-lucide name="x" class="size-5" />
        </button>
        <div class="bg-navy-glow p-8 text-white md:col-span-2">
            <span class="inline-flex rounded-full bg-accent-500 px-3 py-1 text-xs font-bold text-ink">FREE · 30-second check</span>
            <p id="exit-title" class="mt-4 font-display text-2xl leading-tight font-extrabold text-white">Before you go — is your website losing you customers?</p>
            <ul class="mt-5 space-y-2 text-sm text-white/80">
                <li class="flex gap-2"><x-lucide name="check" class="size-4 shrink-0 text-accent-400" /> Speed, SEO & mobile check</li>
                <li class="flex gap-2"><x-lucide name="check" class="size-4 shrink-0 text-accent-400" /> Missing lead-capture elements</li>
                <li class="flex gap-2"><x-lucide name="check" class="size-4 shrink-0 text-accent-400" /> Actionable fixes, in plain English</li>
            </ul>
        </div>
        <div class="p-8 md:col-span-3">
            <x-lead-form form-type="popup" form-id="exit-popup" variant="compact" button="Send me the free audit" :show-website="true" />
        </div>
    </div>
</div>

{{-- Cookie consent --}}
<div x-data="cookieConsent" x-cloak x-show="show" x-transition
     class="fixed inset-x-3 bottom-20 z-50 mx-auto max-w-xl rounded-2xl border border-line bg-white p-4 shadow-[var(--shadow-lift)] lg:bottom-6 lg:left-6 lg:mx-0">
    <p class="text-sm text-muted">We use cookies to understand how visitors use our site and improve our services. See our <a href="{{ route('privacy') }}" class="font-medium text-brand-700 underline">privacy policy</a>.</p>
    <div class="mt-3 flex gap-2">
        <button type="button" @click="choose('all')" class="btn-primary !py-2 !text-sm">Accept all</button>
        <button type="button" @click="choose('essential')" class="btn-ghost !py-2 !text-sm">Essential only</button>
    </div>
</div>
