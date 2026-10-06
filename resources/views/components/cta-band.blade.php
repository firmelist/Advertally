@props([
    'title' => 'Know exactly where your growth is leaking.',
    'text' => 'The Advertally Growth Score™ benchmarks your business across AI Visibility, Search Visibility, Authority, Demand, Conversion and Intelligence — in about four minutes.',
])
<section class="section-tight" aria-label="Get your Growth Score">
    <div class="container-x">
        <div class="bg-navy-field relative overflow-hidden rounded-[2rem] px-6 py-12 sm:px-12 sm:py-16 lg:px-16" data-reveal>
            <div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="relative grid items-center gap-10 lg:grid-cols-[1.3fr_1fr]">
                <div>
                    <p class="eyebrow-dark">Advertally Growth Score™</p>
                    <h2 class="h-section mt-3 !text-white">{{ $title }}</h2>
                    <p class="mt-4 max-w-xl text-lg leading-relaxed text-navy-200">{{ $text }}</p>
                    <x-cta-buttons class="mt-8" dark />
                </div>
                <div class="hidden justify-end gap-4 sm:flex" aria-hidden="true">
                    @foreach ([['AI', 64], ['Search', 78], ['Demand', 52]] as [$label, $score])
                        <div class="card-dark flex flex-col items-center p-4">
                            <x-score-ring :score="$score" size="sm" dark />
                            <span class="mt-2 text-[11px] font-semibold text-navy-200">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <p class="relative mt-8 text-[11px] text-navy-300 sm:text-right">Scores shown are illustrative.</p>
        </div>
    </div>
</section>
