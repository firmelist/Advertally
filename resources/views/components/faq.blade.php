@props(['faqs', 'title' => 'Frequently asked questions', 'eyebrow' => 'FAQ'])
@if ($faqs->isNotEmpty())
    <section class="section bg-canvas" id="faq">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-section-heading :eyebrow="$eyebrow" :title="$title" align="left" subtitle="Can't find your answer? WhatsApp us — a real person replies within minutes during business hours." />
                <a href="{{ whatsapp_link('a question') }}" target="_blank" rel="noopener" class="btn-whatsapp mt-6"><x-whatsapp-glyph class="size-5" /> Ask on WhatsApp</a>
            </div>
            <div class="space-y-3 lg:col-span-8" x-data="{ open: 0 }">
                @foreach ($faqs as $i => $faq)
                    <div class="card overflow-hidden">
                        <h3>
                            <button type="button" @click="open = open === {{ $i }} ? null : {{ $i }}" :aria-expanded="open === {{ $i }}"
                                    class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left font-sans text-base font-semibold text-ink">
                                {{ $faq->question }}
                                <span class="grid size-7 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-700 transition" :class="open === {{ $i }} && 'rotate-45 !bg-brand-600 !text-white'">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                </span>
                            </button>
                        </h3>
                        <div x-show="open === {{ $i }}" x-collapse @if ($i !== 0) x-cloak @endif>
                            <p class="px-5 pb-5 text-sm leading-relaxed text-muted">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('schema')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($f) => [
                    '@type' => 'Question',
                    'name' => $f->question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
                ])->values(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush
@endif
