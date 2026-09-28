@props(['dark' => false, 'class' => 'h-8 w-auto'])
{{-- Advertally mark: three rising steps (the SME growth ladder) + an orange "growth" dot. --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 168 32" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Advertally">
    <rect x="0" y="18" width="7" height="12" rx="2" fill="{{ $dark ? '#93AFFF' : '#93AFFF' }}"/>
    <rect x="9.5" y="11" width="7" height="19" rx="2" fill="{{ $dark ? '#5F84F5' : '#3B63E3' }}"/>
    <rect x="19" y="4" width="7" height="26" rx="2" fill="{{ $dark ? '#FFFFFF' : '#1F3FA6' }}"/>
    <circle cx="30.5" cy="4.5" r="3.5" fill="#F26B1D"/>
    <text x="40" y="24" font-family="'Plus Jakarta Sans', Inter, sans-serif" font-size="21" font-weight="800" letter-spacing="-0.6" fill="{{ $dark ? '#FFFFFF' : '#0B1B4D' }}">advertally</text>
</svg>
