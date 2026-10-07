{{-- Authority (engine): content, mentions, reviews and expert voices all feed one thing — trust. --}}
@php $signals = [['Content', 'file-text', 10, 18], ['Digital PR', 'megaphone', 90, 18], ['Reviews', 'award', 8, 78], ['Founder voice', 'user-search', 92, 78], ['Research', 'flask', 50, 6]]; @endphp
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        @foreach ($signals as $i => [$label, $icon, $x, $y])
            <path id="tr-{{ $i }}" d="M{{ $x }} {{ $y }} Q {{ ($x + 50) / 2 }} {{ $y < 50 ? 30 : 70 }} 50 52" stroke="rgba(255,255,255,.1)" stroke-width=".5" stroke-dasharray="1.5 2" vector-effect="non-scaling-stroke"/>
            <circle r="1.2" fill="#A78BFA"><animateMotion dur="2.8s" begin="-{{ $i * .55 }}s" repeatCount="indefinite"><mpath href="#tr-{{ $i }}"/></animateMotion></circle>
        @endforeach
    </svg>
    {{-- the shield fills up --}}
    <div class="absolute top-[52%] left-1/2 -translate-x-1/2 -translate-y-1/2">
        <svg class="h-36 w-32 drop-shadow-[0_0_30px_rgb(124_58_237/.55)]" viewBox="0 0 24 28" fill="none">
            <defs>
                <clipPath id="shield-clip"><path d="M12 1l10 3.8v7.4c0 6.2-4.2 11.6-10 13.8C6.2 23.8 2 18.4 2 12.2V4.8z"/></clipPath>
                <linearGradient id="shield-fill" x1="0" y1="28" x2="0" y2="0" gradientUnits="userSpaceOnUse"><stop stop-color="#16A34A"/><stop offset=".6" stop-color="#2563EB"/><stop offset="1" stop-color="#7C3AED"/></linearGradient>
            </defs>
            <path d="M12 1l10 3.8v7.4c0 6.2-4.2 11.6-10 13.8C6.2 23.8 2 18.4 2 12.2V4.8z" fill="#0B1F3A" stroke="rgba(255,255,255,.35)" stroke-width=".5"/>
            <g clip-path="url(#shield-clip)"><rect class="scn-grow-y" x="0" y="0" width="24" height="28" fill="url(#shield-fill)" style="--t:7s; transform-box: fill-box"/></g>
            <path d="M8 13.5l3 3 5.5-6" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="scn-draw" style="--len:14; --t:7s; --d:1.6s"/>
        </svg>
        <p class="mt-1 text-center text-[12px] font-extrabold text-white">Trusted</p>
    </div>
    @foreach ($signals as [$label, $icon, $x, $y])
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ max(12, min(88, $x)) }}%; top: {{ $y }}%">
            <div class="scn-float flex items-center gap-1.5 rounded-full border border-white/15 bg-navy-900/90 px-2.5 py-1 font-semibold whitespace-nowrap text-white" style="--d:-{{ $x / 30 }}s">
                <x-glyph :name="$icon" class="size-3.5 text-ai-200" /> {{ $label }}
            </div>
        </div>
    @endforeach
</div>
