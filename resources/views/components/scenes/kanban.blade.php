{{-- CRM automation: deals move themselves through the pipeline as fields and tasks update automatically. --}}
@php $columns = $d['columns']; @endphp
<div class="relative grid h-full grid-cols-4 gap-2 text-[10px]">
    @foreach ($columns as $c => $column)
        <div class="flex flex-col rounded-xl border border-white/10 bg-white/[0.04] p-1.5">
            <p class="mb-1.5 flex items-center justify-between px-1 font-bold text-navy-100"><span class="truncate">{{ $column }}</span><span class="size-1.5 rounded-full {{ ['bg-signal-400', 'bg-brand-400', 'bg-ai-400', 'bg-growth-600'][$c] }}"></span></p>
            @for ($k = 0; $k < 2; $k++)
                <div class="mb-1.5 rounded-lg border border-white/5 bg-white/[0.05] p-1.5"><div class="h-1.5 w-3/4 rounded bg-white/15"></div><div class="mt-1 h-1 w-1/2 rounded bg-white/10"></div></div>
            @endfor
        </div>
    @endforeach
    {{-- the moving deal --}}
    <div class="absolute top-[38%] left-0 w-[calc(25%-0.4rem)] px-1.5" style="animation: scn-deal 9s cubic-bezier(.6,0,.2,1) infinite">
        <div class="rounded-lg border border-brand-400/70 bg-navy-800 p-2 shadow-[0_10px_30px_rgb(37_99_235/.45)]">
            <p class="truncate font-bold text-white">{{ $d['deal'] }}</p>
            <p class="mt-0.5 truncate text-navy-300">Source: {{ $d['source'] }}</p>
            <div class="mt-1.5 flex flex-wrap gap-1">
                @foreach (['Owner', 'Fit', 'Next step'] as $f => $field)
                    <span class="scn-pop rounded bg-growth-600/25 px-1 text-[8px] font-semibold text-growth-100" style="--t:9s; --d:{{ 1 + $f * 2.2 }}s">{{ $field }} ✓</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>@keyframes scn-deal { 0%, 14% { transform: translateX(0); } 24%, 38% { transform: translateX(100%); } 48%, 62% { transform: translateX(200%); } 72%, 92% { transform: translateX(300%); } 100% { transform: translateX(300%); opacity: 0; } }</style>
