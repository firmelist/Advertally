@php
    $tech = \App\Models\ServiceCategory::query()->published()->where('group', 'technology')
        ->with(['services' => fn ($q) => $q->published()])->first();
@endphp
@if ($tech)
<section class="section bg-canvas" aria-labelledby="tech-title">
    <div class="container-x grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div>
            <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Growth Technology'" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" id="tech-title" />
            @if (! empty($data['message']))
                <blockquote class="mt-8 border-l-2 border-brand-600 pl-5 text-lg font-semibold text-ink" data-reveal>{{ $data['message'] }}</blockquote>
            @endif
            <a href="{{ $tech->url() }}" class="btn-dark mt-9" data-reveal>{{ $data['cta_label'] ?? 'Explore Growth Technology' }} <x-glyph name="arrow-right" class="size-4" /></a>
        </div>

        {{-- engine-room diagram --}}
        <div class="relative" data-reveal>
            <div class="card overflow-hidden p-2">
                <div class="rounded-[1rem] bg-gradient-to-b from-canvas to-white p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold tracking-[0.14em] text-muted uppercase">Growth infrastructure</p>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-signal-600"><span class="size-1.5 animate-pulse-soft rounded-full bg-signal-500"></span> Connected</span>
                    </div>
                    <ul class="mt-5 grid grid-cols-2 gap-3">
                        @foreach ($tech->services as $service)
                            <li>
                                <a href="{{ $service->url() }}" class="group flex h-full items-center gap-3 rounded-xl border border-line bg-white p-3 transition hover:border-brand-200">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-navy-900 text-signal-400"><x-glyph :name="$service->icon ?: 'code'" class="size-[18px]" /></span>
                                    <span class="text-sm leading-tight font-semibold text-ink group-hover:text-brand-700">{{ $service->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 flex items-center justify-between gap-3 rounded-xl bg-navy-900 px-4 py-3 text-sm">
                        <span class="font-semibold text-white">Marketing</span>
                        <span class="h-px flex-1 bg-gradient-to-r from-signal-500 via-ai-500 to-growth-600"></span>
                        <span class="font-semibold text-white">Data</span>
                        <span class="h-px flex-1 bg-gradient-to-r from-signal-500 via-ai-500 to-growth-600"></span>
                        <span class="font-semibold text-growth-100">Revenue</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
