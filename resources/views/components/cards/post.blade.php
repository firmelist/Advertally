@props(['post'])
<article {{ $attributes->merge(['class' => 'card-hover group relative flex flex-col overflow-hidden']) }}>
    @if ($post->featured_image)
        <img src="{{ media_url($post->featured_image) }}" alt="" loading="lazy" decoding="async" class="aspect-[16/9] w-full object-cover">
    @else
        <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-brand-50 via-white to-ai-50" aria-hidden="true">
            <div class="bg-dots absolute inset-0"></div>
            <x-glyph :name="$post->type === 'report' ? 'file-text' : ($post->type === 'framework' ? 'layers' : 'lightbulb')" class="absolute right-6 bottom-6 size-14 text-brand-200" stroke="1.25" />
        </div>
    @endif
    <div class="flex flex-1 flex-col p-6">
        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
            <span class="text-brand-700">{{ $post->category?->name ?? 'Insights' }}</span>
            <span class="text-line">•</span>
            <span class="text-muted">{{ \App\Models\Post::TYPES[$post->type] ?? 'Article' }}</span>
        </div>
        <h3 class="mt-3 text-lg leading-snug font-semibold text-ink">
            <a href="{{ $post->url() }}" class="after:absolute after:inset-0 group-hover:text-brand-700">{{ $post->title }}</a>
        </h3>
        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-muted">{{ $post->excerpt }}</p>
        <p class="mt-5 text-xs text-muted">
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time>
            · {{ $post->reading_time }} min read
        </p>
    </div>
</article>
