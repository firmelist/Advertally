@php
    $points = $data['points'] ?? [];
    $logos = ($data['show_logos'] ?? true)
        ? \App\Models\ClientLogo::query()->where('is_active', true)->orderBy('sort_order')->get()
        : collect();
@endphp
<section class="border-y border-line bg-white" aria-label="How we work">
    <div class="container-x">
        <ul class="grid divide-line sm:grid-cols-3 sm:divide-x">
            @foreach ($points as $point)
                <li class="flex items-start gap-4 py-7 sm:px-8 sm:first:pl-0 sm:last:pr-0">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl {{ $loop->index === 0 ? 'bg-ai-50 text-ai-600' : 'bg-brand-50 text-brand-700' }}">
                        <x-glyph :name="$point['icon'] ?? 'sparkles'" />
                    </span>
                    <div>
                        <p class="font-bold text-ink">{{ $point['title'] ?? '' }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-muted">{{ $point['text'] ?? '' }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

        @if ($logos->isNotEmpty())
            <div class="border-t border-line py-8">
                <p class="text-center text-xs font-semibold tracking-[0.14em] text-muted uppercase">{{ $data['logos_title'] ?? 'Trusted by growth-focused teams' }}</p>
                <ul class="mt-6 flex flex-wrap items-center justify-center gap-x-12 gap-y-6">
                    @foreach ($logos as $logo)
                        <li><img src="{{ media_url($logo->logo) }}" alt="{{ $logo->name }}" loading="lazy" class="h-8 w-auto opacity-70 grayscale transition hover:opacity-100 hover:grayscale-0"></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>
