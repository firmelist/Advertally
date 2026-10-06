@props(['eyebrow' => null, 'title', 'intro' => null, 'align' => 'left', 'dark' => false, 'as' => 'h2'])
<div {{ $attributes->merge(['class' => ($align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl')]) }} data-reveal>
    @if ($eyebrow)
        <p class="{{ $dark ? 'eyebrow-dark' : 'eyebrow' }}">{{ $eyebrow }}</p>
    @endif
    <{{ $as }} class="h-section mt-3 {{ $dark ? '!text-white' : '' }}">{!! $title !!}</{{ $as }}>
    @if ($intro)
        <p class="mt-5 text-lg leading-relaxed {{ $dark ? 'text-navy-200' : 'text-muted' }}">{!! $intro !!}</p>
    @endif
    {{ $slot }}
</div>
