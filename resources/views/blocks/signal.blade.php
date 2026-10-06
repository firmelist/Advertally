<section class="bg-navy-field relative overflow-hidden py-20 sm:py-24" aria-labelledby="signal-title">
    <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
    <div class="container-x relative">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? 'The Advertally Signal™'" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" align="center" dark id="signal-title" />
        <div class="mx-auto mt-14 max-w-5xl" data-reveal>
            <x-signal :steps="array_values(array_filter((array) ($data['steps'] ?? [])) ?: ['Customer Intent', 'Search', 'AI', 'Authority', 'Demand', 'Conversion', 'Revenue'])" dark />
        </div>
        @if (! empty($data['points']))
            <div class="mt-16 grid gap-4 sm:grid-cols-3">
                @foreach ($data['points'] as $point)
                    <div class="card-dark p-6" data-reveal>
                        <p class="font-bold text-white">{{ $point['title'] ?? '' }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-navy-200">{{ $point['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
