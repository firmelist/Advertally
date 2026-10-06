@php $options = $data['options'] ?? []; @endphp
<section class="section-tight bg-canvas" aria-labelledby="talent-title">
    <div class="container-x">
        <div class="card grid gap-10 p-6 sm:p-10 lg:grid-cols-[1fr_1.2fr] lg:items-center" data-reveal>
            <div>
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-[0.14em] text-navy-500 uppercase">
                    <x-glyph name="users" class="size-4" /> {{ $data['eyebrow'] ?? 'Advertally Technology & Talent' }}
                </p>
                <h2 id="talent-title" class="mt-3 text-2xl leading-tight font-bold sm:text-3xl">{{ $data['headline'] ?? 'Need More Technical Capacity?' }}</h2>
                <p class="mt-3 leading-relaxed text-muted">{{ $data['intro'] ?? '' }}</p>
                <a href="{{ $data['cta_url'] ?? route('talent') }}" class="link-arrow mt-6">{{ $data['cta_label'] ?? 'Explore Technology & Talent' }} <x-glyph name="arrow-right" class="size-4" /></a>
            </div>
            <ul class="grid gap-2 sm:grid-cols-2">
                @foreach ($options as $option)
                    <li>
                        <a href="{{ url($option['url'] ?? 'technology-talent') }}" class="group flex items-center justify-between gap-3 rounded-xl border border-line px-4 py-3.5 text-sm font-semibold text-ink transition hover:border-navy-200 hover:bg-canvas">
                            {{ $option['label'] ?? '' }} <x-glyph name="arrow-right" class="size-4 text-muted transition group-hover:translate-x-0.5" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
