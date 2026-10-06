@php $items = $data['items'] ?? []; @endphp
<section class="section bg-white" aria-labelledby="story-title">
    <div class="container-x grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
        <div class="lg:sticky lg:top-28 lg:self-start">
            <x-section-heading :eyebrow="$data['eyebrow'] ?? null" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" id="story-title" />
        </div>
        <ol class="relative space-y-4">
            @foreach ($items as $i => $item)
                <li class="card relative flex gap-5 p-6 sm:p-7" data-reveal>
                    <span class="font-mono text-sm font-bold {{ $loop->last ? 'text-growth-600' : 'text-brand-600' }}">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h3 class="text-lg font-bold text-ink">{{ $item['title'] ?? '' }}</h3>
                        @if (! empty($item['text']))
                            <p class="mt-2 text-[15px] leading-relaxed text-muted">{{ $item['text'] }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
