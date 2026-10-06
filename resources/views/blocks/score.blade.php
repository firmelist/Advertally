@php
    $dimensions = \App\Models\AuditDimension::query()->ofType('growth_score')->get();
    $sample = [64, 78, 58, 49, 71, 42];
@endphp
<section class="section bg-white" aria-labelledby="score-title">
    <div class="container-x grid items-center gap-14 lg:grid-cols-[1fr_1.05fr]">
        <div class="order-2 lg:order-1" data-reveal>
            <div class="card relative overflow-hidden p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold tracking-[0.14em] text-muted uppercase">Advertally Growth Score™</p>
                        <p class="mt-1 text-sm text-muted">Example report</p>
                    </div>
                    <span class="sample-badge">Sample data</span>
                </div>
                <div class="mt-6 grid items-center gap-8 sm:grid-cols-[auto_1fr]">
                    <x-score-ring :score="61" size="lg" label="Overall Growth Score" class="mx-auto" />
                    <ul class="space-y-3">
                        @foreach ($dimensions as $i => $dimension)
                            @php $value = $sample[$i] ?? 50; @endphp
                            <li>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-semibold text-ink">{{ $dimension->name }}</span>
                                    <span class="font-bold text-ink tabular-nums">{{ $value }}</span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-navy-50">
                                    <div class="h-full rounded-full {{ $value >= 75 ? 'bg-growth-600' : ($value >= 50 ? 'bg-brand-600' : 'bg-ai-600') }}" style="width: {{ $value }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-1 lg:order-2">
            <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Measure the result'" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" id="score-title" />
            <ul class="mt-8 grid grid-cols-2 gap-3 text-sm font-semibold text-ink" data-reveal>
                @foreach ($dimensions as $dimension)
                    <li class="flex items-center gap-2"><x-glyph name="check-circle" class="size-[18px] text-brand-600" /> {{ $dimension->name }}</li>
                @endforeach
            </ul>
            <x-cta-buttons class="mt-10" :primary-label="$data['cta_label'] ?? 'Get Your Growth Score'" secondary-label="Run AI Visibility Audit" :secondary-url="route('ai-audit')" data-reveal />
        </div>
    </div>
</section>
