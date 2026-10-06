@php
    $old = array_filter((array) ($data['old_path'] ?? []));
    $new = array_filter((array) ($data['new_path'] ?? []));
@endphp
<section class="section bg-canvas" aria-labelledby="journey-title">
    <div class="container-x">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? null" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" id="journey-title" />

        <div class="mt-14 grid gap-6 lg:grid-cols-[0.75fr_1.25fr]">
            {{-- then --}}
            <div class="card p-6 sm:p-8" data-reveal>
                <p class="text-xs font-bold tracking-[0.14em] text-muted uppercase">{{ $data['old_label'] ?? 'Then' }}</p>
                <ol class="mt-6 flex items-center gap-2 sm:gap-3">
                    @foreach ($old as $step)
                        <li class="flex items-center gap-2 sm:gap-3">
                            <span class="rounded-lg border border-line bg-canvas px-3 py-2 text-sm font-semibold text-navy-500">{{ $step }}</span>
                            @unless ($loop->last)<x-glyph name="arrow-right" class="size-4 text-navy-300" />@endunless
                        </li>
                    @endforeach
                </ol>
                <p class="mt-6 text-sm leading-relaxed text-muted">{{ $data['old_text'] ?? '' }}</p>
            </div>

            {{-- now --}}
            <div class="card relative overflow-hidden p-6 sm:p-8" data-reveal>
                <div class="absolute -top-24 -right-24 size-64 rounded-full bg-ai-50 blur-2xl" aria-hidden="true"></div>
                <p class="relative text-xs font-bold tracking-[0.14em] text-brand-700 uppercase">{{ $data['new_label'] ?? 'Now' }}</p>
                <ol class="relative mt-6 flex flex-wrap items-center gap-x-2 gap-y-3">
                    @foreach ($new as $i => $step)
                        <li class="flex items-center gap-2">
                            <span @class([
                                'rounded-lg px-3 py-2 text-sm font-semibold',
                                'bg-ai-600 text-white' => strtolower($step) === 'ai',
                                'bg-growth-600 text-white' => $loop->last,
                                'bg-navy-900 text-white' => strtolower($step) !== 'ai' && ! $loop->last && $i === 0,
                                'border border-brand-100 bg-brand-50 text-navy-800' => strtolower($step) !== 'ai' && ! $loop->last && $i !== 0,
                            ])>{{ $step }}</span>
                            @unless ($loop->last)<x-glyph name="arrow-right" class="size-3.5 text-brand-400" />@endunless
                        </li>
                    @endforeach
                </ol>
                <p class="relative mt-6 text-sm leading-relaxed text-muted">{{ $data['new_text'] ?? '' }}</p>
            </div>
        </div>

        @if (! empty($data['message']))
            <p class="mt-10 text-center text-xl font-bold text-ink sm:text-2xl" data-reveal>{{ $data['message'] }}</p>
        @endif
    </div>
</section>
