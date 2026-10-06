@props(['items' => [], 'title' => 'Questions business owners ask', 'eyebrow' => 'FAQ'])
@php $items = array_values(array_filter($items, fn ($f) => filled($f['question'] ?? null))); @endphp
@if ($items)
    <section class="section bg-white" aria-labelledby="faq-heading">
        <div class="container-x grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
            <div>
                <p class="eyebrow">{{ $eyebrow }}</p>
                <h2 id="faq-heading" class="h-section mt-3">{{ $title }}</h2>
            </div>
            <div class="divide-y divide-line border-y border-line" x-data="{ open: 0 }">
                @foreach ($items as $i => $faq)
                    <div>
                        <h3>
                            <button type="button" class="flex w-full items-center justify-between gap-6 py-5 text-left text-base font-semibold text-ink hover:text-brand-700"
                                @click="open = open === {{ $i }} ? null : {{ $i }}" :aria-expanded="open === {{ $i }}" aria-controls="faq-{{ $i }}">
                                {{ $faq['question'] }}
                                <x-glyph name="chevron-down" class="size-5 text-muted transition" ::class="open === {{ $i }} && 'rotate-180'" />
                            </button>
                        </h3>
                        <div id="faq-{{ $i }}" x-show="open === {{ $i }}" x-collapse @if ($i !== 0) x-cloak @endif>
                            <div class="pb-6 text-[15px] leading-relaxed text-muted">{!! nl2br(e(strip_tags($faq['answer']))) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
