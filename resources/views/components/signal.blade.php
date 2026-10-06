@props([
    'steps' => ['Customer Intent', 'Search', 'AI', 'Authority', 'Demand', 'Conversion', 'Revenue'],
    'dark' => false,
    'compact' => false,
])
{{--
    THE ADVERTALLY SIGNAL™ — the brand's signature motif: intent travelling through the growth system to revenue.
    Horizontal on desktop, vertical on mobile. The final node is the outcome (green = positive outcome).
--}}
@php $last = count($steps) - 1; @endphp
<div {{ $attributes->merge(['class' => 'relative']) }} role="img" aria-label="The Advertally Signal: {{ implode(' to ', $steps) }}">
    {{-- desktop / tablet --}}
    <ol class="relative hidden items-start justify-between sm:flex" aria-hidden="true">
        <svg class="pointer-events-none absolute inset-x-[3%] {{ $compact ? 'top-[7px]' : 'top-[11px]' }} h-1 w-[94%] overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 2">
            <line x1="0" y1="1" x2="100" y2="1" stroke="{{ $dark ? 'rgba(255,255,255,.14)' : '#E2E8F0' }}" stroke-width="2" vector-effect="non-scaling-stroke"/>
            <line x1="0" y1="1" x2="100" y2="1" stroke="url(#signal-g-{{ $dark ? 'd' : 'l' }})" stroke-width="2" vector-effect="non-scaling-stroke" class="signal-line"/>
            <defs>
                <linearGradient id="signal-g-{{ $dark ? 'd' : 'l' }}" x1="0" x2="100" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset=".85" stop-color="#2563EB"/><stop offset="1" stop-color="#16A34A"/>
                </linearGradient>
            </defs>
        </svg>
        @foreach ($steps as $i => $step)
            <li class="relative z-10 flex flex-1 flex-col items-center text-center">
                <span @class([
                    'grid place-items-center rounded-full ring-4',
                    'size-4' => $compact, 'size-6' => ! $compact,
                    $dark ? 'ring-navy-900' : 'ring-white',
                    'bg-signal-500' => $i === 0,
                    'bg-growth-600' => $i === $last,
                    'bg-ai-600' => $i > 0 && $i < $last && $i % 2 === 0,
                    'bg-brand-600' => $i > 0 && $i < $last && $i % 2 === 1,
                ])>
                    @unless ($compact)<span class="size-2 rounded-full bg-white"></span>@endunless
                </span>
                <span @class([
                    'mt-3 font-semibold',
                    'text-[11px]' => $compact, 'text-xs lg:text-sm' => ! $compact,
                    $dark ? 'text-navy-100' : 'text-ink',
                    '!text-growth-600' => $i === $last && ! $dark,
                    '!text-growth-100' => $i === $last && $dark,
                ])>{{ $step }}</span>
            </li>
        @endforeach
    </ol>

    {{-- mobile --}}
    <ol class="relative space-y-3 pl-7 sm:hidden" aria-hidden="true">
        <span class="absolute top-2 bottom-2 left-[7px] w-0.5 rounded bg-gradient-to-b from-signal-500 via-ai-600 to-growth-600"></span>
        @foreach ($steps as $i => $step)
            <li class="relative text-sm font-semibold {{ $dark ? 'text-navy-100' : 'text-ink' }}">
                <span @class([
                    'absolute top-1 -left-7 size-4 rounded-full ring-4',
                    $dark ? 'ring-navy-900' : 'ring-white',
                    'bg-signal-500' => $i === 0,
                    'bg-growth-600' => $i === $last,
                    'bg-brand-600' => $i > 0 && $i < $last,
                ])></span>
                {{ $step }}
            </li>
        @endforeach
    </ol>
</div>
