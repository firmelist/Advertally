@php
    $research = \App\Models\AiResearch::query()->published()->latest('published_at')->take(3)->get();
    $posts = \App\Models\Post::query()->published()->with('category')->latest('published_at')->take(3)->get();
@endphp
@if ($research->isNotEmpty() || $posts->isNotEmpty())
    @if ($research->isNotEmpty())
        <section class="bg-navy-field relative overflow-hidden py-20 sm:py-24" aria-labelledby="lab-title">
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="container-x relative">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <x-section-heading :eyebrow="$data['lab_eyebrow'] ?? 'Advertally AI Search Lab'" :title="$data['lab_headline'] ?? 'Research on how AI discovers businesses.'" :intro="$data['lab_intro'] ?? null" dark id="lab-title" />
                    <a href="{{ route('lab.index') }}" class="inline-flex shrink-0 items-center gap-1.5 text-sm font-semibold text-signal-400 hover:text-white" data-reveal>Enter the Lab <x-glyph name="arrow-right" class="size-4" /></a>
                </div>
                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    @foreach ($research as $item)
                        <x-cards.research :research="$item" dark data-reveal />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($posts->isNotEmpty())
        <section class="section bg-white" aria-labelledby="insights-title">
            <div class="container-x">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Insights'" :title="$data['headline'] ?? 'Thinking for leaders who own growth.'" :intro="$data['intro'] ?? null" id="insights-title" />
                    <a href="{{ route('insights.index') }}" class="link-arrow shrink-0" data-reveal>All insights <x-glyph name="arrow-right" class="size-4" /></a>
                </div>
                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-cards.post :post="$post" data-reveal />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif
