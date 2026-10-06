@props(['caseStudy'])
<article {{ $attributes->merge(['class' => 'card-hover group relative flex flex-col p-6 sm:p-8']) }}>
    <div class="flex flex-wrap items-center gap-2">
        @if ($caseStudy->industry)
            <span class="chip">{{ $caseStudy->industry->name }}</span>
        @endif
        @if ($caseStudy->is_sample)
            <span class="sample-badge">Sample case study</span>
        @endif
    </div>
    <h3 class="mt-4 text-xl leading-snug font-bold text-ink">
        <a href="{{ $caseStudy->url() }}" class="after:absolute after:inset-0 group-hover:text-brand-700">{{ $caseStudy->title }}</a>
    </h3>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-muted">{{ $caseStudy->summary }}</p>
    @if ($caseStudy->metrics->isNotEmpty())
        <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-line pt-5">
            @foreach ($caseStudy->metrics->take(2) as $metric)
                <div>
                    <dt class="text-xs text-muted">{{ $metric->label }}</dt>
                    <dd class="mt-1 text-2xl font-extrabold text-growth-700">{{ $metric->value }}</dd>
                </div>
            @endforeach
        </dl>
    @endif
    <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">Read the growth story <x-glyph name="arrow-right" class="size-4" /></span>
</article>
