@props(['service', 'dark' => false])
<a href="{{ $service->url() }}" {{ $attributes->merge(['class' => ($dark ? 'card-dark p-6 transition hover:bg-white/[0.07]' : 'card-hover p-6').' group flex flex-col']) }}>
    <span class="grid size-10 place-items-center rounded-xl {{ $dark ? 'bg-white/10 text-signal-400' : 'bg-brand-50 text-brand-700' }}">
        <x-glyph :name="$service->icon ?: 'sparkles'" />
    </span>
    <h3 class="mt-5 text-lg font-semibold {{ $dark ? 'text-white' : 'text-ink' }}">{{ $service->title }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed {{ $dark ? 'text-navy-200' : 'text-muted' }}">{{ $service->short_description }}</p>
    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold {{ $dark ? 'text-signal-400' : 'text-brand-700' }}">
        Explore <x-glyph name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
    </span>
</a>
