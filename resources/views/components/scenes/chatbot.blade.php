{{-- AI assistant on your website: answers a real question, qualifies, and books the call. --}}
<div class="relative h-full overflow-hidden rounded-xl border border-white/10 bg-white text-[11px]">
    {{-- faded website behind --}}
    <div class="space-y-2 p-3 opacity-40">
        <div class="h-2 w-16 rounded bg-navy-900"></div><div class="h-10 rounded bg-slate-100"></div>
        <div class="grid grid-cols-3 gap-1.5"><span class="h-8 rounded bg-slate-100"></span><span class="h-8 rounded bg-slate-100"></span><span class="h-8 rounded bg-slate-100"></span></div>
    </div>
    <div class="absolute right-3 bottom-3 left-3 rounded-2xl border border-slate-200 bg-white shadow-[0_20px_50px_rgb(11_31_58/.35)] sm:left-auto sm:w-[17rem]">
        <div class="flex items-center gap-2 rounded-t-2xl bg-navy-900 px-3 py-2 text-white">
            <span class="grid size-6 place-items-center rounded-full bg-gradient-to-br from-ai-500 to-signal-500"><x-glyph name="bot" class="size-3.5" /></span>
            <span class="font-bold">{{ $d['bot'] }}</span><span class="ml-auto flex items-center gap-1 text-[9px] text-growth-100"><span class="size-1.5 rounded-full bg-growth-600"></span>Online</span>
        </div>
        <div class="space-y-2 p-2.5">
            <p class="scn-seq ml-auto w-fit max-w-[85%] rounded-xl rounded-br-sm bg-brand-600 px-2.5 py-1.5 text-white" style="--t:10s; --d:.4s">{{ $d['question'] }}</p>
            <div class="scn-seq flex w-fit gap-1 rounded-xl bg-slate-100 px-2.5 py-2" style="--t:10s; --d:1.2s; animation-name: scn-typing">
                @for ($i = 0; $i < 3; $i++)<span class="size-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay: {{ $i * .15 }}s"></span>@endfor
            </div>
            <p class="scn-seq w-fit max-w-[90%] rounded-xl rounded-bl-sm bg-slate-100 px-2.5 py-1.5 text-navy-900" style="--t:10s; --d:2.4s">{{ $d['answer'] }}</p>
            <div class="scn-seq flex flex-wrap gap-1" style="--t:10s; --d:3.4s">
                @foreach ($d['options'] as $o)<span class="rounded-full border border-brand-300 px-2 py-0.5 font-semibold text-brand-700">{{ $o }}</span>@endforeach
            </div>
            <p class="scn-pop flex items-center gap-1.5 rounded-lg bg-growth-50 px-2.5 py-1.5 font-semibold text-growth-700" style="--t:10s; --d:5s"><x-glyph name="check-circle" class="size-4" /> {{ $d['outcome'] }}</p>
        </div>
    </div>
</div>
<style>@keyframes scn-typing { 0% { opacity: 0; } 4%, 10% { opacity: 1; } 12%, 100% { opacity: 0; height: 0; padding: 0; } }</style>
