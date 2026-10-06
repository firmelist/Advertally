@php
    $caseStudies = \App\Models\CaseStudy::query()->published()->with('industry', 'metrics')
        ->orderByDesc('is_featured')->latest('published_at')->take(2)->get();
@endphp
@if ($caseStudies->isNotEmpty())
<section class="section bg-canvas" aria-labelledby="stories-title">
    <div class="container-x">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Growth Stories'" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" id="stories-title" />
            <a href="{{ route('case-studies.index') }}" class="link-arrow shrink-0" data-reveal>All growth stories <x-glyph name="arrow-right" class="size-4" /></a>
        </div>
        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            @foreach ($caseStudies as $caseStudy)
                <x-cards.case-study :case-study="$caseStudy" data-reveal />
            @endforeach
        </div>
    </div>
</section>
@endif
