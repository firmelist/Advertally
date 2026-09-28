@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'center', 'dark' => false])
<div {{ $attributes->class(['max-w-3xl', 'mx-auto text-center' => $align === 'center']) }}>
    @if ($eyebrow)
        <span @class(['eyebrow', '!bg-white/10 !text-accent-300' => $dark])>{{ $eyebrow }}</span>
    @endif
    <h2 @class(['h-section mt-4', '!text-white' => $dark])>{!! $title !!}</h2>
    @if ($subtitle)
        <p @class(['lead-text mt-4', '!text-white/70' => $dark])>{{ $subtitle }}</p>
    @endif
</div>
