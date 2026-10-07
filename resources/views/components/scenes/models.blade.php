{{-- AI integration: one feature, any model — providers switch behind a server-side gateway; keys never reach the browser. --}}
@php $providers = ['OpenAI', 'Anthropic', 'Gemini']; @endphp
<div class="relative flex h-full flex-col gap-3 text-[11px]">
    <div class="rounded-xl border border-white/10 bg-white/[0.06] p-3">
        <p class="text-[9px] font-bold tracking-wider text-navy-300 uppercase">Feature</p>
        <p class="mt-0.5 font-bold text-white">{{ $d['feature'] }}</p>
        <div class="mt-2 space-y-1">
            <div class="scn-grow-x h-1.5 w-[92%] rounded bg-white/25" style="--t:9s"></div>
            <div class="scn-grow-x h-1.5 w-[70%] rounded bg-white/25" style="--t:9s; --d:.3s"></div>
        </div>
    </div>
    <div class="flex flex-1 items-center justify-center gap-3">
        <div class="relative grid place-items-center rounded-2xl border border-brand-400/50 bg-navy-800 px-4 py-3 text-center shadow-[0_0_30px_rgb(37_99_235/.35)]">
            <x-glyph name="lock" class="size-5 text-brand-300" />
            <p class="mt-1 font-bold text-white">AI gateway</p>
            <p class="text-[9px] text-navy-300">server-side keys</p>
        </div>
        <div class="flex flex-col gap-2">
            @foreach ($providers as $i => $p)
                <div class="relative flex items-center gap-2">
                    <span class="h-px w-6 bg-white/20"></span>
                    <span class="relative rounded-lg border border-white/10 bg-white/[0.05] px-3 py-1.5 font-semibold text-navy-200">
                        {{ $p }}
                        <span class="absolute inset-0 rounded-lg border-2 border-ai-400 bg-ai-500/25" style="animation: scn-provider 9s ease-in-out infinite; animation-delay: {{ $i * 3 }}s; opacity: 0"></span>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="grid grid-cols-3 gap-2 text-center text-[10px]">
        @foreach ([['Human review', 'users'], ['Data minimised', 'shield-check'], ['Evaluated', 'check-circle']] as [$label, $icon])
            <span class="flex flex-col items-center gap-1 rounded-lg bg-white/[0.05] py-2 font-semibold text-navy-100"><x-glyph :name="$icon" class="size-4 text-growth-100" />{{ $label }}</span>
        @endforeach
    </div>
</div>
<style>@keyframes scn-provider { 0%, 30% { opacity: 1; } 34%, 100% { opacity: 0; } }</style>
