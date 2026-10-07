{{-- Hire SEO specialists: a hands-on technical audit — a scan sweeps the site and the checklist resolves. --}}
<div class="grid h-full grid-cols-[1fr_1.15fr] gap-3 text-[11px]">
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-white p-2.5">
        <div class="h-2 w-14 rounded bg-navy-900"></div>
        <div class="mt-2 h-10 rounded bg-slate-100"></div>
        <div class="mt-2 space-y-1"><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-3/4 rounded bg-slate-200"></div><div class="h-1.5 w-5/6 rounded bg-slate-200"></div></div>
        <div class="mt-2 grid grid-cols-2 gap-1.5"><span class="h-8 rounded bg-slate-100"></span><span class="h-8 rounded bg-slate-100"></span></div>
        <span class="scn-fall absolute inset-x-0 top-0 h-10 bg-gradient-to-b from-transparent via-signal-400/40 to-transparent" style="--t:4s; --y:15rem; --end:0"></span>
    </div>
    <div class="flex flex-col rounded-xl border border-white/10 bg-white/[0.05] p-3">
        <p class="font-bold text-white">Audit checklist</p>
        <ul class="mt-2 flex-1 space-y-2">
            @foreach ($d['checks'] as $i => $check)
                <li class="flex items-center gap-2 text-navy-100">
                    <span class="relative size-4 shrink-0 rounded-full border border-amber-300/60 bg-amber-300/15">
                        <span class="scn-pop absolute inset-[-1px] grid place-items-center rounded-full bg-growth-600" style="--t:9s; --d:{{ .8 + $i * .8 }}s"><x-glyph name="check" class="size-2.5 text-white" stroke="3" /></span>
                    </span>
                    {{ $check }}
                </li>
            @endforeach
        </ul>
        <p class="mt-2 flex items-center gap-1.5 text-[10px] text-navy-300"><x-glyph name="user-search" class="size-3.5 text-signal-400" /> Embedded specialist · your tools</p>
    </div>
</div>
