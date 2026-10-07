@php
    $engines = \App\Models\ServiceCategory::query()->published()->solutions()
        ->with(['services' => fn ($q) => $q->published()])->get();
@endphp
<section class="section bg-white" aria-labelledby="growth-os-title" id="growth-os">
    <div class="container-x">
        <div class="grid items-end gap-6 lg:grid-cols-[1.2fr_1fr]">
            <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Advertally Growth OS'" :title="$data['headline'] ?? 'One Growth System. Six Engines.'" id="growth-os-title" />
            @if (! empty($data['intro']))
                <p class="text-lg leading-relaxed text-muted lg:pb-2" data-reveal>{{ $data['intro'] }}</p>
            @endif
        </div>

        <div class="mt-14 grid grid-cols-1 gap-6 lg:grid-cols-[22rem_minmax(0,1fr)]" x-data="{ active: 0 }">
            {{-- engine selector --}}
            <div class="relative min-w-0" role="tablist" aria-label="Growth OS engines" aria-orientation="vertical">
                <span class="absolute top-6 bottom-6 left-[27px] hidden w-px bg-gradient-to-b from-signal-500 via-ai-600 to-brand-600 lg:block" aria-hidden="true"></span>
                <div class="-mx-4 flex snap-x gap-2 overflow-x-auto px-4 pb-2 sm:mx-0 sm:px-0 lg:flex-col lg:overflow-visible lg:pb-0 [scrollbar-width:none]">
                    @foreach ($engines as $i => $engine)
                        <button type="button" role="tab" id="engine-tab-{{ $i }}" aria-controls="engine-panel-{{ $i }}"
                            :aria-selected="(active === {{ $i }}).toString()" :tabindex="active === {{ $i }} ? 0 : -1"
                            @click="active = {{ $i }}; $el.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' })" @keydown.arrow-down.prevent="active = (active + 1) % {{ $engines->count() }}; $el.nextElementSibling?.focus()"
                            @keydown.arrow-up.prevent="active = (active + {{ $engines->count() - 1 }}) % {{ $engines->count() }}; $el.previousElementSibling?.focus()"
                            class="relative flex shrink-0 snap-start items-center gap-4 rounded-2xl border px-3 py-3 text-left transition lg:w-full"
                            :class="active === {{ $i }} ? 'border-brand-200 bg-brand-50/60 shadow-[var(--shadow-card)]' : 'border-transparent hover:bg-canvas'">
                            <span class="relative z-10 grid size-8 shrink-0 place-items-center rounded-full text-xs font-bold ring-4 ring-white transition"
                                :class="active === {{ $i }} ? 'bg-brand-600 text-white' : 'bg-navy-50 text-navy-600'">{{ $engine->number }}</span>
                            <span class="pr-2">
                                <span class="block text-[15px] font-bold whitespace-nowrap text-ink">{{ $engine->name }}</span>
                                <span class="hidden text-sm text-muted lg:block">{{ $engine->tagline }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- engine detail --}}
            <div class="relative min-w-0">
                @foreach ($engines as $i => $engine)
                    <div role="tabpanel" id="engine-panel-{{ $i }}" aria-labelledby="engine-tab-{{ $i }}"
                        x-show="active === {{ $i }}" @if ($i) x-cloak @endif
                        x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 translate-y-1"
                        class="card relative h-full overflow-hidden p-6 sm:p-10">
                        <div class="absolute -top-20 -right-20 size-72 rounded-full {{ $engine->slug === 'ai-search' || $engine->slug === 'automation' ? 'bg-ai-50' : 'bg-brand-50' }} blur-2xl" aria-hidden="true"></div>
                        <div class="relative">
                            <div class="flex items-center gap-3">
                                <span class="grid size-12 place-items-center rounded-2xl bg-navy-900 text-white"><x-glyph :name="$engine->icon ?: 'sparkles'" class="size-6" /></span>
                                <div>
                                    <p class="font-mono text-xs font-semibold text-brand-700">{{ $engine->number }} — {{ strtoupper($engine->name) }}</p>
                                    <h3 class="text-2xl font-extrabold text-ink sm:text-3xl">{{ $engine->tagline }}</h3>
                                </div>
                            </div>
                            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-muted">{{ $engine->summary }}</p>

                            <ul class="mt-8 grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($engine->services as $service)
                                    <li>
                                        <a href="{{ $service->url() }}" class="group flex items-center justify-between gap-2 rounded-xl border border-line bg-white px-4 py-3 text-sm font-semibold text-ink transition hover:border-brand-200 hover:text-brand-700">
                                            {{ $service->title }} <x-glyph name="arrow-up-right" class="size-4 text-muted transition group-hover:text-brand-600" />
                                        </a>
                                    </li>
                                @endforeach
                                @foreach (array_diff((array) $engine->capabilities, $engine->services->pluck('title')->all()) as $capability)
                                    <li class="flex items-center gap-2 rounded-xl border border-dashed border-line px-4 py-3 text-sm font-medium text-muted">
                                        <x-glyph name="check" class="size-4 text-brand-500" /> {{ $capability }}
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ $engine->url() }}" class="btn-dark mt-8">Explore {{ $engine->name }} <x-glyph name="arrow-right" class="size-4" /></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
