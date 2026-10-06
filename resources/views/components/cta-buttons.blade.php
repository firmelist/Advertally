@props(['dark' => false, 'primaryLabel' => 'Get Your Growth Score', 'primaryUrl' => null, 'secondaryLabel' => 'Talk to an Expert', 'secondaryUrl' => null, 'size' => 'lg'])
{{-- The site's single CTA pair: one primary (Growth Score) and one secondary (expert conversation). --}}
<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-center']) }}>
    <a href="{{ $primaryUrl ?? route('growth-score') }}" class="btn-primary {{ $size === 'lg' ? 'btn-lg' : '' }}" data-track="cta_primary">
        {{ $primaryLabel }} <x-glyph name="arrow-right" class="size-4" />
    </a>
    @if ($secondaryLabel)
        <a href="{{ $secondaryUrl ?? route('contact') }}" class="{{ $dark ? 'btn-on-dark' : 'btn-secondary' }} {{ $size === 'lg' ? 'btn-lg' : '' }}" data-track="cta_secondary">
            {{ $secondaryLabel }}
        </a>
    @endif
</div>
