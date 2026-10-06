@props(['data' => []])
@php
    // Demo figures only — always rendered with a visible "Sample data" label.
    $scores = $data['scores'] ?? [
        ['label' => 'AI Visibility', 'value' => 82],
        ['label' => 'Search Visibility', 'value' => 91],
        ['label' => 'Authority', 'value' => 76],
        ['label' => 'Conversion Readiness', 'value' => 84],
    ];
    $funnel = $data['funnel'] ?? [
        ['label' => 'Qualified Leads', 'value' => '428'],
        ['label' => 'Opportunities', 'value' => '76'],
        ['label' => 'Customers', 'value' => '19'],
        ['label' => 'Revenue', 'value' => '₹42L'],
    ];
    $widths = [100, 62, 38, 26];
@endphp
<figure {{ $attributes->merge(['class' => 'relative']) }} aria-label="Sample Advertally Growth Intelligence dashboard (illustrative data)">
    {{-- soft glow --}}
    <div class="absolute -inset-6 -z-10 rounded-[2.5rem] bg-gradient-to-br from-brand-200/50 via-ai-100/50 to-signal-200/40 blur-2xl" aria-hidden="true"></div>

    <div class="overflow-hidden rounded-3xl border border-line bg-white shadow-[var(--shadow-glow)]">
        {{-- window bar --}}
        <div class="flex items-center justify-between gap-3 border-b border-line bg-canvas/70 px-4 py-3 sm:px-5">
            <div class="flex items-center gap-2.5">
                <span class="grid size-7 place-items-center rounded-lg bg-navy-900 text-white"><x-glyph name="radar" class="size-4" /></span>
                <div>
                    <p class="text-[13px] leading-tight font-bold text-ink">Advertally Growth Intelligence</p>
                    <p class="text-[11px] leading-tight text-muted">Growth system overview · last 90 days</p>
                </div>
            </div>
            <span class="sample-badge"><x-glyph name="info" class="size-3" /> Sample data</span>
        </div>

        <div class="space-y-4 p-4 sm:p-5">
            {{-- visibility scores --}}
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($scores as $i => $s)
                    @php $tone = $i === 0 ? 'ai' : 'brand'; @endphp
                    <div class="rounded-2xl border border-line p-3">
                        <p class="text-[11px] font-semibold text-muted">{{ $s['label'] }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-ink tabular-nums">
                            <span x-data="counter({{ (int) $s['value'] }})" x-intersect.once="start()" x-text="display">{{ $s['value'] }}</span><span class="text-sm font-semibold text-muted">/100</span>
                        </p>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-navy-50">
                            <div class="h-full rounded-full {{ $tone === 'ai' ? 'bg-gradient-to-r from-ai-600 to-ai-400' : 'bg-gradient-to-r from-brand-600 to-signal-500' }}" style="width: {{ (int) $s['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- revenue funnel --}}
            <div class="rounded-2xl border border-line bg-gradient-to-b from-white to-canvas p-4">
                <div class="mb-3 flex items-center justify-between">
                    <p class="text-xs font-bold text-ink">Pipeline from connected channels</p>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-growth-700"><x-glyph name="trending-up" class="size-3.5" /> Revenue attributed</span>
                </div>
                <div class="space-y-2">
                    @foreach ($funnel as $i => $f)
                        @php $isRevenue = $loop->last; @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-28 shrink-0 text-[11px] font-semibold text-muted sm:w-32">{{ $f['label'] }}</span>
                            <div class="relative h-7 flex-1">
                                <div class="flex h-full items-center rounded-lg px-2.5 {{ $isRevenue ? 'bg-growth-600' : ($i === 0 ? 'bg-navy-900' : ($i === 1 ? 'bg-navy-700' : 'bg-brand-600')) }}" style="width: {{ $widths[$i] ?? 30 }}%; min-width: 4.5rem">
                                    <span class="text-xs font-bold text-white tabular-nums">{{ $f['value'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- system flow --}}
            <div class="rounded-2xl bg-navy-900 px-4 pt-4 pb-3">
                <p class="mb-3 text-[11px] font-semibold tracking-wider text-signal-400 uppercase">Growth signal</p>
                <x-signal :steps="['Search', 'AI', 'Authority', 'Demand', 'Conversion', 'Automation', 'Revenue']" dark compact />
            </div>
        </div>
    </div>
    <figcaption class="sr-only">Illustrative dashboard showing sample visibility scores and a sample pipeline. Figures are not real client data.</figcaption>
</figure>
