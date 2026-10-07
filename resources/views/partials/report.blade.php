{{-- Shared report layout for the Growth Score and AI Visibility Audit. --}}
<section class="bg-navy-field hero-dark relative overflow-hidden pt-12 pb-16 sm:pt-16">
    <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
    <div class="container-x relative grid items-center gap-12 lg:grid-cols-[1.3fr_1fr]">
        <div>
            <p class="eyebrow-dark">{{ $eyebrow }}</p>
            <h1 class="mt-4 text-4xl leading-tight font-extrabold tracking-tight text-white sm:text-5xl">{{ $title }}</h1>
            @if ($audit->website)<p class="mt-3 font-mono text-sm text-navy-300">{{ $audit->website }}</p>@endif
            @if ($audit->summary)
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-navy-100">{{ $audit->summary }}</p>
            @endif
            <p class="mt-6 text-xs text-navy-300">Generated {{ $audit->completed_at?->format('j M Y, H:i') ?? $audit->created_at->format('j M Y') }} · {{ $note }}</p>
        </div>
        <div class="flex justify-center lg:justify-end">
            <div class="card-dark p-8 text-center">
                <x-score-ring :score="$audit->overall_score ?? 0" size="xl" dark />
                <p class="mt-3 text-sm font-semibold text-navy-100">{{ $scoreLabel }}</p>
            </div>
        </div>
    </div>
</section>

<section class="section bg-white" aria-labelledby="dimensions-title">
    <div class="container-x">
        <h2 id="dimensions-title" class="h-section">Your score by dimension</h2>
        <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($audit->scores->sortBy(fn ($s) => $s->dimension?->sort_order) as $score)
                <div class="card flex flex-col p-6" x-data="{ open: false }">
                    <div class="flex items-center gap-5">
                        <x-score-ring :score="$score->score" size="sm" />
                        <div>
                            <h3 class="font-bold text-ink">{{ $score->dimension?->name }}</h3>
                            <p class="text-xs font-semibold {{ ['strong' => 'text-growth-700', 'fair' => 'text-brand-700', 'weak' => 'text-ai-600'][score_tone($score->score)] ?? 'text-muted' }}">
                                {{ ['strong' => 'Strong', 'fair' => 'Developing', 'weak' => 'Priority gap'][score_tone($score->score)] ?? '' }}
                            </p>
                        </div>
                    </div>
                    <p class="mt-4 flex-1 text-sm leading-relaxed text-muted">{{ $score->summary ?: $score->dimension?->description }}</p>
                    @if ($score->checks)
                        <button type="button" class="link-arrow mt-4" @click="open = !open" :aria-expanded="open.toString()">
                            <span x-text="open ? 'Hide signals' : 'Show signals'">Show signals</span> <x-glyph name="chevron-down" class="size-4 transition" ::class="open && 'rotate-180'" />
                        </button>
                        <ul x-show="open" x-collapse x-cloak class="mt-4 space-y-3 border-t border-line pt-4">
                            @foreach ($score->checks as $check)
                                <li class="flex gap-2.5 text-sm">
                                    <span class="mt-1.5 size-2 shrink-0 rounded-full {{ ['pass' => 'bg-growth-600', 'warn' => 'bg-amber-500', 'fail' => 'bg-red-600'][$check['status']] ?? 'bg-line' }}" aria-hidden="true"></span>
                                    <span><span class="font-semibold text-ink">{{ $check['label'] }}</span> <span class="sr-only">({{ $check['status'] }})</span><span class="block text-muted">{{ $check['detail'] }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

@if ($audit->recommendations->isNotEmpty())
    <section class="section bg-canvas" aria-labelledby="opps-title">
        <div class="container-x grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow">Top opportunities</p>
                <h2 id="opps-title" class="h-section mt-3">What to fix first.</h2>
                <p class="mt-4 text-muted">Prioritised by likely impact on visibility, trust and pipeline.</p>
            </div>
            <ol class="space-y-3">
                @foreach ($audit->recommendations as $rec)
                    <li class="card flex gap-5 p-6">
                        <span class="font-mono text-lg font-bold text-brand-600">{{ $loop->iteration }}</span>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-ink">{{ $rec->title }}</h3>
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-[11px] font-semibold uppercase',
                                    'bg-ai-50 text-ai-600' => $rec->impact === 'high',
                                    'bg-brand-50 text-brand-700' => $rec->impact === 'medium',
                                    'bg-navy-50 text-navy-600' => $rec->impact === 'low',
                                ])>{{ $rec->impact }} impact</span>
                            </div>
                            <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $rec->description }}</p>
                            @if ($rec->dimension)<p class="mt-2 text-xs font-semibold text-muted">{{ $rec->dimension->name }}</p>@endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif

<section class="section-tight bg-white">
    <div class="container-x">
        <div class="card grid items-center gap-8 p-8 sm:p-10 lg:grid-cols-[1.4fr_1fr]">
            <div>
                <h2 class="text-2xl font-bold text-ink sm:text-3xl">{{ $nextTitle }}</h2>
                <p class="mt-3 text-muted">{{ $nextText }}</p>
            </div>
            <div class="flex flex-col gap-3 lg:items-end">
                <a href="{{ route('contact') }}" class="btn-primary btn-lg" data-track="report_discuss">Discuss My Growth Opportunities <x-glyph name="arrow-right" class="size-4" /></a>
                <a href="{{ $secondaryUrl }}" class="link-arrow">{{ $secondaryLabel }} <x-glyph name="arrow-right" class="size-4" /></a>
            </div>
        </div>
    </div>
</section>
