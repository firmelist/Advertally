@props(['score' => 0, 'size' => 'md', 'label' => null, 'dark' => false])
@php
    $tone = score_tone($score);
    $stroke = match ($tone) { 'strong' => '#16A34A', 'fair' => '#2563EB', default => '#7C3AED' };
    [$px, $text] = match ($size) { 'sm' => [56, 'text-sm'], 'lg' => [168, 'text-5xl'], 'xl' => [208, 'text-6xl'], default => [96, 'text-2xl'] };
    $r = 42; $c = 2 * M_PI * $r;
@endphp
<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center']) }} x-data="scoreRing({{ (int) $score }})" x-intersect.once="start()">
    <div class="relative" style="width: {{ $px }}px; height: {{ $px }}px">
        <svg viewBox="0 0 100 100" class="size-full -rotate-90" aria-hidden="true">
            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="{{ $dark ? 'rgba(255,255,255,.1)' : '#E2E8F0' }}" stroke-width="8"/>
            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="{{ $stroke }}" stroke-width="8" stroke-linecap="round"
                stroke-dasharray="{{ $c }}" :stroke-dashoffset="{{ $c }} * (1 - value / 100)" stroke-dashoffset="{{ $c * (1 - $score / 100) }}"
                style="transition: stroke-dashoffset 1.4s cubic-bezier(.2,.7,.2,1)"/>
        </svg>
        <div class="absolute inset-0 grid place-items-center">
            <span class="{{ $text }} font-extrabold tabular-nums {{ $dark ? 'text-white' : 'text-ink' }}">{{ (int) $score }}<span class="sr-only"> out of 100</span></span>
        </div>
    </div>
    @if ($label)
        <span class="mt-2 text-center text-xs font-semibold {{ $dark ? 'text-navy-200' : 'text-muted' }}">{{ $label }}</span>
    @endif
</div>
