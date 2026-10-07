{{-- Automation (engine): interlocking gears — AI and workflows doing the repetitive work while people decide. --}}
@php
    $gear = fn ($teeth) => collect(range(0, $teeth - 1))->map(function ($t) use ($teeth) {
        $a = 360 / $teeth * $t;
        return '<rect x="47" y="2" width="6" height="12" rx="1.5" transform="rotate('.$a.' 50 50)"/>';
    })->implode('');
@endphp
<div class="relative h-full text-[11px]">
    <svg class="scn-spin absolute top-[8%] left-[6%] size-[46%] max-w-[12rem] text-brand-500/70" style="--t:14s" viewBox="0 0 100 100" fill="currentColor">{!! $gear(12) !!}<circle cx="50" cy="50" r="38"/><circle cx="50" cy="50" r="14" fill="#071528"/></svg>
    <svg class="scn-spin-rev absolute top-[34%] left-[42%] size-[34%] max-w-[9rem] text-ai-500/75" style="--t:10s" viewBox="0 0 100 100" fill="currentColor">{!! $gear(10) !!}<circle cx="50" cy="50" r="36"/><circle cx="50" cy="50" r="13" fill="#071528"/></svg>
    <svg class="scn-spin absolute top-[4%] right-[6%] size-[26%] max-w-[7rem] text-signal-500/70" style="--t:7s" viewBox="0 0 100 100" fill="currentColor">{!! $gear(8) !!}<circle cx="50" cy="50" r="34"/><circle cx="50" cy="50" r="12" fill="#071528"/></svg>
    <div class="absolute top-[30%] left-[29%] -translate-x-1/2 -translate-y-1/2 text-center font-bold text-white">Workflows</div>
    <div class="absolute top-[51%] left-[59%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-navy-900/80 px-2 font-bold text-white">AI</div>
    <div class="absolute top-[17%] right-[19%] translate-x-1/2 -translate-y-1/2 text-[10px] font-bold text-white">CRM</div>
    {{-- conveyor of completed tasks --}}
    <div class="absolute inset-x-0 bottom-0 overflow-hidden rounded-xl border border-white/10 bg-white/[0.05] py-2">
        <div class="flex w-max gap-2 px-2" style="animation: scn-conveyor 12s linear infinite">
            @foreach ([...$d['tasks'], ...$d['tasks']] as $task)
                <span class="flex items-center gap-1 rounded-full bg-growth-600/20 px-2.5 py-1 font-semibold whitespace-nowrap text-growth-100"><x-glyph name="check" class="size-3" stroke="3" />{{ $task }}</span>
            @endforeach
        </div>
    </div>
    <div class="absolute right-0 bottom-14 rounded-xl border border-white/10 bg-navy-900/90 px-3 py-2 text-[10px] text-navy-100">
        <p class="font-bold text-white">Your team</p><p>Focus on decisions</p>
    </div>
</div>
<style>@keyframes scn-conveyor { to { transform: translateX(-50%); } }</style>
