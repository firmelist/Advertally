{{--
    REAL BRANDS. REAL WORK.
    $brands contains ONLY brands that are public + approved + active + enabled for internship pages (Brand::scopeDisplayable).
    Confidential clients are never loaded, so they cannot leak into this markup or structured data.
--}}
@php
    $heading = $internship->brands_heading ?: 'Real Brands. Real Work. Real Experience.';
    $industries = $brands->pluck('industry')->filter()->unique()->values();
@endphp
<section id="brands" class="section relative overflow-hidden bg-white" aria-labelledby="brands-title">
    <div class="pointer-events-none absolute -top-32 left-1/2 h-72 w-[48rem] -translate-x-1/2 rounded-full bg-gradient-to-r from-brand-200/40 via-ai-200/40 to-signal-200/40 blur-3xl" aria-hidden="true"></div>
    <div class="container-x relative">
        <div class="mx-auto max-w-3xl text-center" data-reveal>
            <p class="eyebrow justify-center">Clients &amp; brands</p>
            <h2 id="brands-title" class="h-section mt-3">{{ $heading }}</h2>
            <p class="mt-3 text-lg font-semibold text-ink">{{ $internship->brands_tagline ?: 'Experience the kind of work that happens beyond the classroom.' }}</p>
            <p class="mt-4 leading-relaxed text-muted">{{ $internship->brands_description ?: \App\Models\Internship::DEFAULT_BRANDS_DESCRIPTION }}</p>
        </div>

        {{-- logo grid: 2 columns on phones, more on larger screens; tap or hover reveals details --}}
        <ul class="mt-12 flex flex-wrap justify-center gap-3 sm:gap-4" x-data="{ open: null }">
            @foreach ($brands as $brand)
                <li class="relative w-[calc(50%-0.375rem)] sm:w-[calc(50%-0.5rem)] md:w-[calc(33.333%-0.667rem)] lg:w-[calc(25%-0.75rem)]" data-reveal>
                    <button type="button" @click="open = open === {{ $brand->id }} ? null : {{ $brand->id }}" @keydown.escape="open = null"
                        :aria-expanded="(open === {{ $brand->id }}).toString()" aria-controls="brand-{{ $brand->id }}"
                        class="group flex h-32 w-full flex-col items-center justify-center gap-2 rounded-2xl border border-line bg-white p-4 shadow-[var(--shadow-card)] transition duration-300 hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-[var(--shadow-lift)] focus-visible:border-brand-400 sm:h-36">
                        @if ($brand->logo)
                            <img src="{{ media_url($brand->logo) }}" @if ($srcset = $brand->logoSrcset()) srcset="{{ $srcset }}" sizes="160px" @endif
                                alt="{{ $brand->name }} logo" loading="lazy" decoding="async"
                                class="max-h-12 w-auto max-w-[80%] object-contain opacity-70 grayscale transition duration-300 group-hover:opacity-100 group-hover:grayscale-0 sm:max-h-14">
                        @else
                            <span class="text-lg font-extrabold text-navy-400 group-hover:text-ink">{{ $brand->name }}</span>
                        @endif
                        <span class="text-center text-xs font-semibold text-muted transition group-hover:text-ink">{{ $brand->name }}</span>
                        @if ($brand->industry)<span class="text-[10px] text-muted opacity-0 transition group-hover:opacity-100">{{ $brand->industry }}</span>@endif
                    </button>

                    {{-- detail card --}}
                    <div id="brand-{{ $brand->id }}" x-show="open === {{ $brand->id }}" x-cloak x-transition.opacity @click.outside="open = null"
                        class="absolute inset-x-0 top-full z-20 mt-2 rounded-2xl border border-line bg-white p-4 text-left shadow-[var(--shadow-lift)] sm:inset-x-[-12%]">
                        <p class="font-bold text-ink">{{ $brand->name }}</p>
                        @if ($brand->industry)<p class="mt-1 text-xs"><span class="font-semibold text-ink">Industry:</span> <span class="text-muted">{{ $brand->industry }}</span></p>@endif
                        @if ($brand->work_summary)<p class="mt-0.5 text-xs"><span class="font-semibold text-ink">Work:</span> <span class="text-muted">{{ $brand->work_summary }}</span></p>@endif
                        @if ($brand->description)<p class="mt-2 text-xs leading-relaxed text-muted">{{ $brand->description }}</p>@endif
                        @if ($brand->website_url)
                            <a href="{{ $brand->website_url }}" target="_blank" rel="noopener nofollow" class="link-arrow mt-3 text-xs">Visit website <x-glyph name="arrow-up-right" class="size-3.5" /></a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        @if ($internship->show_industries && $industries->isNotEmpty())
            <div class="mt-10 text-center" data-reveal>
                <p class="text-xs font-semibold tracking-[0.14em] text-muted uppercase">Industries</p>
                <ul class="mt-3 flex flex-wrap justify-center gap-2">
                    @foreach ($industries as $industry)<li class="chip">{{ $industry }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if ($stats->isNotEmpty())
            <dl class="mx-auto mt-12 grid max-w-4xl grid-cols-2 gap-4 sm:grid-cols-4" data-reveal>
                @foreach ($stats as $stat)
                    <div class="flex flex-col-reverse rounded-2xl border border-line bg-canvas p-4 text-center">
                        <dt class="mt-1 text-xs font-semibold text-muted">{{ $stat->label }}</dt>
                        <dd class="text-gradient-anim text-3xl font-extrabold">{{ $stat->value }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        @if ($internship->exposure)
            <div class="mx-auto mt-12 max-w-3xl rounded-2xl bg-navy-900 p-6 text-center sm:p-8" data-reveal>
                <p class="text-xs font-semibold tracking-[0.14em] text-signal-400 uppercase">Depending on your role, you may gain exposure to</p>
                <ul class="mt-4 flex flex-wrap justify-center gap-2">
                    @foreach ($internship->exposure as $item)<li class="chip-dark">{{ $item }}</li>@endforeach
                </ul>
            </div>
        @endif

        <p class="mx-auto mt-8 max-w-3xl text-center text-xs text-muted italic">* {{ \App\Models\Internship::DISCLAIMER }}</p>
    </div>
</section>
