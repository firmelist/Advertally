@props(['dark' => false])
{{-- Wordmark + "signal" mark: a rising path through three nodes (found → trusted → chosen). --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <svg class="size-8 shrink-0" viewBox="0 0 32 32" fill="none" aria-hidden="true">
        <rect width="32" height="32" rx="9" fill="{{ $dark ? '#FFFFFF' : '#0B1F3A' }}" fill-opacity="{{ $dark ? '0.08' : '1' }}"/>
        <path d="M7 22.5 13.5 16l4 3.5L25 10" stroke="url(#adv-logo-g)" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="7" cy="22.5" r="2" fill="#06B6D4"/>
        <circle cx="17.5" cy="19.5" r="2" fill="#7C3AED"/>
        <circle cx="25" cy="10" r="2.4" fill="#FFFFFF"/>
        <defs>
            <linearGradient id="adv-logo-g" x1="7" y1="22" x2="25" y2="10" gradientUnits="userSpaceOnUse">
                <stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#60A5FA"/>
            </linearGradient>
        </defs>
    </svg>
    <span class="text-[1.2rem] font-extrabold tracking-[-0.02em] {{ $dark ? 'text-white' : 'text-navy-900' }}">Advertally</span>
</span>
