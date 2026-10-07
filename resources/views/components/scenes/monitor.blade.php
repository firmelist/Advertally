{{-- AI Visibility: presence across assistants, week by week, getting stronger. --}}
@php $assistants = ['ChatGPT', 'Gemini', 'Perplexity', 'Claude', 'Copilot']; @endphp
<div class="flex h-full flex-col text-[11px]">
    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.05] px-3 py-2 text-navy-100">
        <span class="truncate">Prompt: <span class="font-semibold text-white">“{{ $d['prompt'] }}”</span></span>
        <x-glyph name="eye" class="size-4 shrink-0 text-signal-400" />
    </div>
    <div class="mt-3 flex-1 space-y-2">
        @foreach ($assistants as $r => $name)
            <div class="grid grid-cols-[4.6rem_1fr] items-center gap-2">
                <span class="truncate font-semibold text-navy-100">{{ $name }}</span>
                <div class="grid grid-cols-8 gap-1">
                    @for ($w = 0; $w < 8; $w++)
                        @php $strength = min(1, max(.15, ($w + ($r % 3)) / 8)); @endphp
                        <span class="relative h-6 overflow-hidden rounded-md bg-white/[0.06]">
                            <span class="scn-pop absolute inset-0 rounded-md" style="--t:8s; --d:{{ .25 * $w + .1 * $r }}s; background: rgb(124 58 237 / {{ $strength }})"></span>
                        </span>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-3 flex items-center justify-between text-[10px] text-navy-300">
        <span>Week 1</span>
        <span class="flex items-center gap-1 font-semibold text-growth-100"><x-glyph name="trending-up" class="size-3.5" /> Brand mentioned more often</span>
        <span>Week 8</span>
    </div>
</div>
