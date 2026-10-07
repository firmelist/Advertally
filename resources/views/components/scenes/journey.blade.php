{{-- Conversion (engine): one visitor walks the path from first visit to customer — every step designed. --}}
@php $steps = [['Visitor', 'eye'], ['Engagement', 'pointer'], ['Lead', 'mail'], ['Qualified lead', 'target'], ['Opportunity', 'briefcase'], ['Customer', 'check-circle']]; @endphp
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path id="jr-path" d="M12 86 C 12 60, 40 70, 40 50 S 70 40, 70 26 S 88 10, 88 12" stroke="rgba(255,255,255,.14)" stroke-width="1.2" stroke-dasharray="2 2.5" vector-effect="non-scaling-stroke"/>
        <path d="M12 86 C 12 60, 40 70, 40 50 S 70 40, 70 26 S 88 10, 88 12" stroke="url(#jr-g)" stroke-width="2" class="scn-draw" style="--len:180; --t:8s" vector-effect="non-scaling-stroke"/>
        <defs><linearGradient id="jr-g" x1="12" y1="86" x2="88" y2="12" gradientUnits="userSpaceOnUse"><stop stop-color="#22D3EE"/><stop offset=".6" stop-color="#7C3AED"/><stop offset="1" stop-color="#16A34A"/></linearGradient></defs>
    </svg>
    @foreach ([[12, 86], [26, 68], [40, 50], [56, 40], [70, 26], [88, 12]] as $i => [$x, $y])
        <div class="absolute -translate-x-1/2 -translate-y-1/2" style="left: {{ $x }}%; top: {{ $y }}%">
            <div class="scn-pop flex flex-col items-center" style="--t:8s; --d:{{ $i * .55 }}s">
                <span @class(['grid size-9 place-items-center rounded-full border-2 shadow-lg', 'border-growth-600 bg-growth-600 text-white' => $loop->last, 'border-white/30 bg-navy-800 text-brand-200' => ! $loop->last])>
                    <x-glyph :name="$steps[$i][1]" class="size-4" />
                </span>
                <span class="mt-1 rounded-full bg-navy-950/80 px-1.5 text-[10px] font-semibold whitespace-nowrap text-white">{{ $steps[$i][0] }}</span>
            </div>
        </div>
    @endforeach
    <div class="absolute right-0 bottom-0 max-w-[55%] rounded-xl border border-white/10 bg-white/[0.05] p-2.5 text-navy-100">
        Clarity, proof and a low-risk next step at every stage.
    </div>
</div>
