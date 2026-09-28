{{-- "Next step on the growth ladder" upsell block. Expects $next (Service|null) --}}
@if ($next)
<section class="py-16">
    <div class="container-x">
        <div class="relative overflow-hidden rounded-[2rem] border border-brand-100 bg-gradient-to-br from-brand-50 via-white to-accent-50 p-8 sm:p-12">
            <div class="grid items-center gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8">
                    <span class="eyebrow !bg-white">Your next step · Step {{ $next->ladder['step'] ?? '' }}: {{ $next->ladder['label'] ?? '' }}</span>
                    <h2 class="mt-4 text-2xl font-bold sm:text-3xl">{{ $next->ladder['line'] ?? '' }} Add <span class="text-brand-600">{{ $next->title }}</span>.</h2>
                    <p class="mt-3 max-w-2xl text-muted">{{ $next->short_description }} Because the same team handles both, everything stays connected — and bundles save up to 10%.</p>
                </div>
                <div class="flex flex-col gap-3 lg:col-span-4 lg:items-end">
                    <a href="{{ $next->url }}" class="btn-primary">Explore {{ $next->title }} <x-lucide name="arrow-right" class="size-4" /></a>
                    <a href="{{ route('pricing') }}#builder" class="text-sm font-semibold text-brand-700 hover:underline">Build a bundle & save →</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
