{{-- ABM: a targeting reticle locks onto your ideal accounts, one by one. --}}
@php
    $accounts = ['NX', 'QB', 'AV', 'LM', 'TR', 'KP', 'ZE', 'OR', 'HV', 'SY', 'MD', 'BL'];
    $targets = [1 => 0, 6 => 1, 10 => 2, 3 => 3]; // index => order in sequence
@endphp
<div class="relative flex h-full flex-col text-[11px]">
    <div class="grid flex-1 grid-cols-4 grid-rows-3 gap-2">
        @foreach ($accounts as $i => $code)
            @php $t = $targets[$i] ?? null; @endphp
            <div class="relative grid place-items-center rounded-xl border border-white/10 bg-white/[0.04]">
                <span class="text-sm font-extrabold text-navy-300">{{ $code }}</span>
                @if ($t !== null)
                    <span class="scn-pop absolute inset-0 rounded-xl border-2 border-ai-400 bg-ai-500/20 shadow-[0_0_24px_rgb(124_58_237/.5)]" style="--t:8s; --d:{{ .8 + $t * 1.2 }}s"></span>
                    <span class="scn-pop absolute top-1 right-1 rounded-full bg-ai-500 px-1.5 text-[8px] font-bold text-white" style="--t:8s; --d:{{ 1.1 + $t * 1.2 }}s">Tier 1</span>
                    <span class="scn-pop absolute bottom-1 left-1/2 -translate-x-1/2 text-[8px] font-semibold whitespace-nowrap text-growth-100" style="--t:8s; --d:{{ 1.4 + $t * 1.2 }}s">engaged</span>
                @endif
            </div>
        @endforeach
    </div>
    {{-- reticle --}}
    <div class="pointer-events-none absolute inset-0" style="animation: scn-reticle 8s ease-in-out infinite">
        <svg class="absolute size-14 -translate-x-1/2 -translate-y-1/2" viewBox="0 0 40 40" fill="none" stroke="#22D3EE" stroke-width="1.5">
            <circle cx="20" cy="20" r="12"/><path d="M20 2v8M20 30v8M2 20h8M30 20h8"/>
        </svg>
    </div>
    <div class="mt-3 flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.05] px-3 py-2 text-navy-100">
        <span>Ideal customer profile</span><span class="font-semibold text-white">Sales &amp; marketing on one account list</span>
    </div>
</div>
<style>
    @keyframes scn-reticle {
        0%, 100% { transform: translate(12.5%, 40%); }
        10%, 18% { transform: translate(37.5%, 13%); }
        25%, 33% { transform: translate(62.5%, 40%); }
        40%, 48% { transform: translate(62.5%, 67%); }
        55%, 70% { transform: translate(87.5%, 13%); }
        85% { transform: translate(37.5%, 67%); }
    }
</style>
