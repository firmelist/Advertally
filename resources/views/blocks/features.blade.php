@php $items = $data['items'] ?? []; $cols = (int) ($data['columns'] ?? 3); @endphp
<section class="section {{ ($data['background'] ?? 'white') === 'canvas' ? 'bg-canvas' : 'bg-white' }}">
    <div class="container-x">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? null" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" />
        <div @class(['mt-14 grid gap-5 sm:grid-cols-2', 'lg:grid-cols-3' => $cols === 3, 'lg:grid-cols-4' => $cols === 4])>
            @foreach ($items as $item)
                <div class="card p-7" data-reveal>
                    <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-glyph :name="$item['icon'] ?? 'sparkles'" /></span>
                    <h3 class="mt-5 text-lg font-bold text-ink">{{ $item['title'] ?? '' }}</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-muted">{{ $item['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
