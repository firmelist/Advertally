@props(['categories' => collect()])
{{-- Insights hero: a fanned stack of article cards that gently float; topics cycle on the front card. --}}
@php $topics = $categories->pluck('name')->take(5)->values()->all() ?: ['AI Search', 'B2B Growth', 'Conversion']; @endphp
<div {{ $attributes->merge(['class' => 'relative mx-auto h-[22rem] w-full max-w-[28rem]']) }} aria-hidden="true">
    <div class="absolute inset-[8%] rounded-full bg-gradient-to-br from-brand-300/30 via-ai-300/25 to-signal-300/25 blur-3xl"></div>
    @foreach ([[-14, -8, 'from-signal-100 to-white'], [8, -4, 'from-ai-100 to-white'], [-3, 0, 'from-brand-100 to-white']] as $i => [$rot, $dy, $tone])
        <div class="absolute inset-x-[10%] top-[10%]" style="rotate: {{ $rot }}deg; translate: {{ $i * 12 - 12 }}px {{ $dy }}px">
            <div class="scn-float rounded-3xl border border-white bg-gradient-to-br {{ $tone }} p-5 shadow-[var(--shadow-lift)]" style="--t:{{ 6 + $i }}s; --d:-{{ $i * 1.3 }}s">
                <div class="h-28 rounded-2xl bg-gradient-to-br from-navy-800 via-brand-700 to-ai-600 opacity-90"></div>
                <div class="mt-4 h-2 w-1/3 rounded bg-brand-300"></div>
                <div class="mt-2.5 h-3 w-5/6 rounded bg-navy-200"></div>
                <div class="mt-2 h-3 w-2/3 rounded bg-navy-100"></div>
            </div>
        </div>
    @endforeach
    {{-- front card --}}
    <div class="absolute inset-x-[6%] top-[22%]">
        <div class="scn-float rounded-3xl border border-white bg-white p-5 shadow-[var(--shadow-lift)]" style="--t:7s">
            <div class="relative h-6 overflow-hidden">
                @foreach ($topics as $t => $topic)
                    <span class="absolute inset-0 text-xs font-bold tracking-[0.14em] text-brand-700 uppercase" style="animation: art-topic {{ count($topics) * 2.5 }}s ease-in-out infinite; animation-delay: {{ $t * 2.5 }}s; opacity: 0">{{ $topic }}</span>
                @endforeach
            </div>
            <p class="mt-1 text-lg leading-snug font-extrabold text-ink">Thinking for leaders who own growth</p>
            <div class="mt-3 space-y-1.5">
                <div class="scn-grow-x h-2 w-full rounded bg-navy-100" style="--t:7s"></div>
                <div class="scn-grow-x h-2 w-11/12 rounded bg-navy-100" style="--t:7s; --d:.3s"></div>
                <div class="scn-grow-x h-2 w-3/5 rounded bg-navy-100" style="--t:7s; --d:.6s"></div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-[11px] text-muted"><span class="size-6 rounded-full bg-gradient-to-br from-signal-400 to-ai-500"></span> Advertally team · 6 min read</div>
        </div>
    </div>
</div>
<style>@keyframes art-topic { 0% { opacity: 0; transform: translateY(100%); } 4%, {{ 100 / max(1, count($topics)) - 4 }}% { opacity: 1; transform: none; } {{ 100 / max(1, count($topics)) }}%, 100% { opacity: 0; transform: translateY(-100%); } }</style>
