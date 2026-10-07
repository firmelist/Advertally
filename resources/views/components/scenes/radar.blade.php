{{-- AI Search (engine): a radar sweep finds your brand across every discovery surface. --}}
@php
    // [label, angle (deg, clockwise from top), radius %]
    $blips = [['Google', 20, 34], ['AI Overviews', 75, 40], ['ChatGPT', 130, 30], ['Gemini', 190, 38], ['Perplexity', 245, 32], ['Claude', 300, 40], ['Copilot', 345, 26]];
@endphp
<div class="relative grid h-full place-items-center">
    <div class="relative aspect-square h-full max-h-[21rem]">
        @foreach ([100, 72, 44] as $size)
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full border border-signal-400/20" style="width: {{ $size }}%; height: {{ $size }}%"></div>
        @endforeach
        <div class="absolute inset-0 rounded-full" style="background: conic-gradient(from 0deg, rgb(34 211 238 / .35), transparent 22%, transparent); animation: orbit-spin 6s linear infinite"></div>
        @foreach ($blips as $i => [$label, $angle, $r])
            @php $x = 50 + $r * sin(deg2rad($angle)); $y = 50 - $r * cos(deg2rad($angle)); $delay = $angle / 360 * 6; @endphp
            <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
                <span class="scn-pulse absolute top-1/2 left-1/2 -mt-1.5 -ml-1.5 size-3 rounded-full bg-signal-400" style="--t:6s; --d:{{ $delay }}s"></span>
                <span class="relative block size-2.5 rounded-full bg-signal-300 shadow-[0_0_10px_rgb(34_211_238)]"></span>
                <span class="absolute top-3 left-1/2 -translate-x-1/2 text-[10px] font-semibold whitespace-nowrap text-navy-100">{{ $label }}</span>
            </div>
        @endforeach
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-2xl border border-white/20 bg-navy-900 px-3 py-2 text-center shadow-[0_0_40px_rgb(37_99_235/.6)]">
            <x-glyph name="search" class="mx-auto size-5 text-brand-300" />
            <span class="mt-0.5 block text-[11px] font-bold text-white">{{ $d['brand'] }}</span>
        </div>
    </div>
</div>
