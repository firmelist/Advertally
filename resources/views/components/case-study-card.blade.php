@props(['cs'])
<a href="{{ route('case-studies.show', $cs) }}" class="card-hover group flex flex-col overflow-hidden">
    <div class="relative bg-brand-950 p-6 text-white">
        <div class="bg-grid pointer-events-none absolute inset-0 opacity-20"></div>
        <p class="relative text-xs font-semibold tracking-wider text-accent-300 uppercase">{{ $cs->industry_label }}{{ $cs->city ? ' · '.$cs->city : '' }}</p>
        <div class="relative mt-5 grid grid-cols-3 gap-3">
            @foreach (collect($cs->results)->take(3) as $r)
                <div>
                    <p class="font-display text-2xl font-extrabold text-white">{{ $r['value'] }}</p>
                    <p class="mt-1 text-[11px] leading-snug text-white/60">{{ $r['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <h3 class="text-lg leading-snug font-bold group-hover:text-brand-700">{{ $cs->title }}</h3>
        <p class="mt-2 flex-1 text-sm text-muted">{{ $cs->summary }}</p>
        <div class="mt-4 flex flex-wrap gap-1.5">
            @foreach ($cs->services ?? [] as $s)<span class="chip">{{ $s }}</span>@endforeach
        </div>
        <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-700">Read case study <x-lucide name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" /></span>
    </div>
</a>
