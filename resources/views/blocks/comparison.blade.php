<section class="section bg-canvas">
    <div class="container-x">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? null" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" />
        <div class="mt-14 grid items-stretch gap-6 lg:grid-cols-[1fr_auto_1.2fr]">
            <div class="card p-7 sm:p-9" data-reveal>
                <p class="text-xs font-bold tracking-[0.14em] text-muted uppercase">{{ $data['left_title'] ?? 'Before' }}</p>
                <ul class="mt-6 space-y-3">
                    @foreach ((array) ($data['left_items'] ?? []) as $item)
                        <li class="flex items-center gap-3 rounded-xl border border-line bg-canvas px-4 py-3 font-semibold text-navy-500">{{ $item }}</li>
                    @endforeach
                </ul>
                @if (! empty($data['left_text']))<p class="mt-6 text-sm leading-relaxed text-muted">{{ $data['left_text'] }}</p>@endif
            </div>
            <div class="grid place-items-center" aria-hidden="true">
                <span class="grid size-12 place-items-center rounded-full bg-navy-900 text-white lg:rotate-0"><x-glyph name="arrow-right" class="size-5 rotate-90 lg:rotate-0" /></span>
            </div>
            <div class="card relative overflow-hidden p-7 sm:p-9" data-reveal>
                <div class="absolute -top-20 -right-20 size-64 rounded-full bg-ai-50 blur-2xl" aria-hidden="true"></div>
                <p class="relative text-xs font-bold tracking-[0.14em] text-brand-700 uppercase">{{ $data['right_title'] ?? 'After' }}</p>
                <ul class="relative mt-6 grid gap-3 sm:grid-cols-2">
                    @foreach ((array) ($data['right_items'] ?? []) as $item)
                        <li class="flex items-center gap-3 rounded-xl border border-brand-100 bg-brand-50/60 px-4 py-3 font-semibold text-ink">
                            <x-glyph name="check-circle" class="size-[18px] text-brand-600" /> {{ $item }}
                        </li>
                    @endforeach
                </ul>
                @if (! empty($data['right_text']))<p class="relative mt-6 text-sm leading-relaxed text-muted">{{ $data['right_text'] }}</p>@endif
            </div>
        </div>
        @if (! empty($data['conclusion']))
            <p class="mx-auto mt-12 max-w-3xl text-center text-2xl leading-snug font-bold text-ink sm:text-3xl" data-reveal>{{ $data['conclusion'] }}</p>
        @endif
    </div>
</section>
