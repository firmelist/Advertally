@extends('layouts.app')

@section('content')
    <article>
        <header class="bg-navy-field hero-dark relative overflow-hidden pt-10 pb-14 sm:pt-14">
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="container-narrow relative">
                <x-breadcrumbs class="mb-10" dark />
                <p class="font-mono text-xs font-semibold tracking-widest text-signal-400 uppercase">{{ $research->categoryLabel() }}</p>
                <h1 class="mt-4 text-4xl leading-[1.1] font-extrabold tracking-tight text-white sm:text-5xl">{{ $research->title }}</h1>
                <dl class="mt-8 grid grid-cols-2 gap-4 border-t border-white/10 pt-6 font-mono text-xs text-navy-300 sm:grid-cols-3">
                    <div><dt class="uppercase">Published</dt><dd class="mt-1 text-navy-100"><time datetime="{{ $research->published_at?->toDateString() }}">{{ $research->published_at?->format('j M Y') }}</time></dd></div>
                    @if ($research->author)
                        <div><dt class="uppercase">Author</dt><dd class="mt-1"><a href="{{ $research->author->url() }}" class="text-navy-100 hover:text-white" rel="author">{{ $research->author->name }}</a></dd></div>
                    @endif
                    <div><dt class="uppercase">Updated</dt><dd class="mt-1 text-navy-100">{{ $research->updated_at?->format('j M Y') }}</dd></div>
                </dl>
            </div>
        </header>

        <div class="bg-white py-14 sm:py-16">
            <div class="container-narrow space-y-12">
                @if ($research->summary)
                    <section aria-labelledby="abstract" class="rounded-2xl border-l-4 border-brand-600 bg-canvas p-6 sm:p-8">
                        <h2 id="abstract" class="font-mono text-xs font-semibold tracking-widest text-muted uppercase">Abstract</h2>
                        <p class="mt-3 text-lg leading-relaxed text-ink">{{ $research->summary }}</p>
                    </section>
                @endif

                @if ($research->key_findings)
                    <section aria-labelledby="findings">
                        <h2 id="findings" class="text-2xl font-bold text-ink">Key findings</h2>
                        <ol class="mt-5 space-y-3">
                            @foreach ($research->key_findings as $finding)
                                <li class="flex gap-4 rounded-xl border border-line p-4 text-[15px] leading-relaxed text-ink">
                                    <span class="font-mono text-sm font-bold text-ai-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span> {{ $finding }}
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                <div class="prose-adv prose-lg">{!! str($research->body)->sanitizeHtml() !!}</div>

                @foreach ((array) $research->charts as $chart)
                    @php $max = max(1, ...array_map('floatval', (array) ($chart['values'] ?? [1]))); @endphp
                    <figure class="card p-6">
                        <figcaption class="text-sm font-bold text-ink">{{ $chart['title'] ?? 'Chart' }}</figcaption>
                        <div class="mt-5 space-y-2.5" role="img" aria-label="{{ $chart['title'] ?? 'Chart' }}">
                            @foreach ((array) ($chart['values'] ?? []) as $i => $value)
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="w-36 shrink-0 text-muted">{{ $chart['labels'][$i] ?? '' }}</span>
                                    <div class="h-6 flex-1 rounded-md bg-navy-50"><div class="h-full rounded-md bg-gradient-to-r from-brand-600 to-signal-500" style="width: {{ (float) $value / $max * 100 }}%"></div></div>
                                    <span class="w-14 text-right font-semibold text-ink tabular-nums">{{ $value }}{{ $chart['unit'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </figure>
                @endforeach

                @if (! empty($research->data['columns']) && ! empty($research->data['rows']))
                    <section aria-labelledby="data">
                        <h2 id="data" class="text-2xl font-bold text-ink">Data</h2>
                        <div class="mt-5 overflow-x-auto rounded-xl border border-line">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-canvas text-xs tracking-wider text-muted uppercase">
                                    <tr>@foreach ($research->data['columns'] as $column)<th scope="col" class="px-4 py-3 font-semibold">{{ $column }}</th>@endforeach</tr>
                                </thead>
                                <tbody class="divide-y divide-line">
                                    @foreach ($research->data['rows'] as $row)
                                        <tr>@foreach ((array) $row as $cell)<td class="px-4 py-3 text-ink">{{ $cell }}</td>@endforeach</tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                @if ($research->methodology)
                    <section aria-labelledby="method" class="rounded-2xl bg-canvas p-6 sm:p-8">
                        <h2 id="method" class="font-mono text-xs font-semibold tracking-widest text-muted uppercase">Methodology</h2>
                        <div class="prose-adv mt-3 text-[15px]">{!! str($research->methodology)->sanitizeHtml() !!}</div>
                    </section>
                @endif

                @if ($research->sources)
                    <section aria-labelledby="sources">
                        <h2 id="sources" class="font-mono text-xs font-semibold tracking-widest text-muted uppercase">Sources</h2>
                        <ol class="mt-4 list-decimal space-y-2 pl-5 text-sm text-muted">
                            @foreach ($research->sources as $source)
                                <li>
                                    @if (! empty($source['url']))
                                        <a href="{{ $source['url'] }}" class="font-medium text-brand-700 hover:underline" rel="noopener nofollow" target="_blank">{{ $source['title'] ?? $source['url'] }}</a>
                                    @else
                                        {{ $source['title'] ?? '' }}
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                @if ($research->services->isNotEmpty())
                    <aside class="card p-6" aria-label="Related solutions">
                        <p class="text-sm font-bold text-ink">Related Advertally solutions</p>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($research->services as $service)
                                <li><a href="{{ $service->url() }}" class="chip hover:border-brand-200 hover:text-brand-700">{{ $service->title }}</a></li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
            </div>
        </div>
    </article>

    @if ($more->isNotEmpty())
        <section class="section bg-canvas">
            <div class="container-x">
                <x-section-heading eyebrow="More from the Lab" title="Continue reading." />
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($more as $item)<x-cards.research :research="$item" />@endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band title="Make AI understand your business." text="Run the AI Visibility Audit or the full Growth Score to see where you stand — and what to fix first." />
@endsection
