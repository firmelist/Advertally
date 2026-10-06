@php $industries = \App\Models\Industry::query()->published()->orderBy('sort_order')->get(); @endphp
@if ($industries->isNotEmpty())
<section class="section bg-white">
    <div class="container-x">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <x-section-heading :eyebrow="$data['eyebrow'] ?? 'Industries'" :title="$data['headline'] ?? 'Built for markets where trust decides the deal.'" :intro="$data['intro'] ?? null" />
            <a href="{{ route('industries.index') }}" class="link-arrow shrink-0" data-reveal>All industries <x-glyph name="arrow-right" class="size-4" /></a>
        </div>
        <ul class="mt-12 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($industries as $industry)
                <li data-reveal>
                    <a href="{{ $industry->url() }}" class="card-hover group flex h-full flex-col p-6">
                        <x-glyph :name="$industry->icon ?: 'building'" class="size-6 text-brand-600" />
                        <span class="mt-4 font-bold text-ink group-hover:text-brand-700">{{ $industry->name }}</span>
                        <span class="mt-1 line-clamp-2 text-sm text-muted">{{ $industry->summary }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif
