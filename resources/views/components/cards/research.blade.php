@props(['research', 'dark' => false])
<article {{ $attributes->merge(['class' => ($dark ? 'card-dark hover:bg-white/[0.07]' : 'card-hover').' group relative flex flex-col p-6 transition']) }}>
    <p class="font-mono text-[11px] font-semibold tracking-wider uppercase {{ $dark ? 'text-signal-400' : 'text-ai-600' }}">
        {{ $research->categoryLabel() }}
    </p>
    <h3 class="mt-3 text-lg leading-snug font-semibold {{ $dark ? 'text-white' : 'text-ink' }}">
        <a href="{{ $research->url() }}" class="after:absolute after:inset-0">{{ $research->title }}</a>
    </h3>
    <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed {{ $dark ? 'text-navy-200' : 'text-muted' }}">{{ $research->summary }}</p>
    <p class="mt-5 flex items-center justify-between text-xs {{ $dark ? 'text-navy-300' : 'text-muted' }}">
        <time datetime="{{ $research->published_at?->toDateString() }}">{{ $research->published_at?->format('M Y') }}</time>
        <x-glyph name="arrow-up-right" class="size-4 transition group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
    </p>
</article>
