@props(['data' => []])
{{--
    Homepage hero visual: the Growth Intelligence dashboard on a 3D stage — tilts toward the cursor, sits inside a
    rotating orbit ring, with satellites (lead toast, AI citation, visibility sparkline) sliding in around it.
    All figures are sample data and labelled as such.
--}}
<div {{ $attributes->merge(['class' => 'hero-stage relative min-w-0']) }}
    x-data="{ rx: 6, ry: -10 }"
    @mousemove="if (window.innerWidth < 1024) return; const r = $el.getBoundingClientRect(); ry = ((($event.clientX - r.left) / r.width) - .5) * 14; rx = -((($event.clientY - r.top) / r.height) - .5) * 10"
    @mouseleave="rx = 6; ry = -10">

    {{-- orbit ring + beams behind the dashboard --}}
    <div class="pointer-events-none absolute top-1/2 left-1/2 -z-0 hidden aspect-square w-[118%] -translate-x-1/2 -translate-y-1/2 lg:block" aria-hidden="true">
        <svg class="size-full" viewBox="0 0 100 100" fill="none" style="animation: orbit-spin 50s linear infinite">
            <circle cx="50" cy="50" r="48" stroke="url(#hero-ring)" stroke-width=".35" stroke-dasharray="1 2.2" stroke-linecap="round"/>
            <circle cx="50" cy="2" r="1.1" fill="#7C3AED"/><circle cx="98" cy="50" r=".9" fill="#06B6D4"/><circle cx="14" cy="84" r=".8" fill="#2563EB"/>
            <defs><linearGradient id="hero-ring" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse"><stop stop-color="#06B6D4"/><stop offset=".5" stop-color="#7C3AED"/><stop offset="1" stop-color="#2563EB"/></linearGradient></defs>
        </svg>
        <div class="absolute inset-[18%] rounded-full bg-gradient-to-br from-brand-400/25 via-ai-400/20 to-signal-400/20 blur-3xl"></div>
    </div>

    {{-- the dashboard on a 3D tilt --}}
    <div class="hero-tilt relative z-10" :style="`--rx: ${rx}deg; --ry: ${ry}deg`">
        <x-growth-dashboard :data="$data" />
    </div>

    {{-- satellites --}}
    <div class="pointer-events-none absolute inset-0 z-20 hidden xl:block" aria-hidden="true">
        <div class="hero-sat absolute -top-5 -left-6 flex items-center gap-2.5 rounded-2xl border border-white bg-white/90 py-2 pr-4 pl-2 shadow-[var(--shadow-lift)] backdrop-blur" style="animation-delay: .4s">
            <span class="grid size-8 place-items-center rounded-xl bg-growth-50 text-growth-600"><x-glyph name="zap" class="size-4" /></span>
            <span class="text-left">
                <span class="block text-[11px] font-bold text-ink">New qualified lead</span>
                <span class="block text-[10px] text-muted">via LinkedIn · routed to owner</span>
            </span>
        </div>

        <div class="hero-sat absolute top-[38%] -right-8 w-52 rounded-2xl border border-white bg-white/90 p-3 shadow-[var(--shadow-lift)] backdrop-blur" style="animation-delay: 2.6s">
            <p class="flex items-center gap-1.5 text-[10px] font-bold text-ai-600"><x-glyph name="sparkles" class="size-3.5" /> AI answer</p>
            <p class="mt-1 text-[11px] leading-snug text-ink">“…teams often shortlist <span class="rounded bg-ai-50 px-1 font-bold text-ai-600">Your Company</span> for…”</p>
            <p class="mt-1.5 inline-block rounded-md border border-ai-200 bg-ai-50 px-1.5 text-[9px] font-semibold text-ai-600">[1] yourcompany.com</p>
        </div>

        <div class="hero-sat absolute -bottom-6 left-[8%] flex items-center gap-3 rounded-2xl border border-white bg-white/90 px-3 py-2 shadow-[var(--shadow-lift)] backdrop-blur" style="animation-delay: 4.8s">
            <span>
                <span class="block text-[10px] font-semibold text-muted">Search visibility</span>
                <span class="flex items-center gap-1 text-[11px] font-bold text-growth-700"><x-glyph name="trending-up" class="size-3.5" /> trending up</span>
            </span>
            <svg class="h-8 w-20" viewBox="0 0 80 32" fill="none"><path d="M2 28 C 14 26, 18 20, 28 21 S 44 12, 54 13 S 70 6, 78 3" stroke="#16A34A" stroke-width="2.4" stroke-linecap="round" class="scn-draw" style="--len:100; --t:9s; --d:4.8s"/></svg>
        </div>
    </div>
</div>
