@php $terms = array_values(array_filter((array) ($data['terms'] ?? []))); @endphp
<section class="bg-navy-field relative overflow-hidden py-20 sm:py-24">
    <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
    <div class="container-x relative text-center">
        @if (! empty($data['eyebrow']))<p class="eyebrow-dark">{{ $data['eyebrow'] }}</p>@endif
        <h2 class="sr-only">{{ implode(' + ', $terms) }} = {{ $data['result'] ?? 'Revenue Growth' }}</h2>
        <p class="mx-auto mt-8 flex max-w-5xl flex-wrap items-center justify-center gap-x-3 gap-y-4 text-2xl font-extrabold tracking-tight text-white sm:text-4xl" aria-hidden="true" data-reveal>
            @foreach ($terms as $term)
                <span class="{{ strtoupper($term) === 'AI' ? 'text-ai-400' : '' }}">{{ $term }}</span>
                @unless ($loop->last)<span class="text-signal-400">+</span>@endunless
            @endforeach
            <span class="text-signal-400">=</span>
            <span class="rounded-2xl bg-growth-600 px-4 py-1.5 text-white">{{ $data['result'] ?? 'Revenue Growth' }}</span>
        </p>
        @if (! empty($data['text']))
            <p class="mx-auto mt-10 max-w-2xl text-lg leading-relaxed text-navy-200" data-reveal>{{ $data['text'] }}</p>
        @endif
    </div>
</section>
