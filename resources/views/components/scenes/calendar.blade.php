{{-- Hire digital marketers: the campaign calendar fills up — planned, launched and optimised by your new specialists. --}}
@php
    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    // [day index, row, span, label, tone]
    $items = [[0, 0, 2, 'Search campaign', 'bg-brand-600/70'], [2, 0, 3, 'LinkedIn ABM', 'bg-ai-500/70'], [0, 1, 1, 'Blog', 'bg-signal-500/60'], [1, 1, 2, 'Webinar promo', 'bg-growth-600/70'], [3, 1, 2, 'Newsletter', 'bg-brand-500/60'], [0, 2, 3, 'Retargeting test', 'bg-ai-400/60'], [3, 2, 2, 'Weekly report', 'bg-signal-500/60']];
@endphp
<div class="flex h-full flex-col text-[10px]">
    <div class="grid grid-cols-5 gap-1.5 text-center font-bold text-navy-200">@foreach ($days as $day)<span>{{ $day }}</span>@endforeach</div>
    <div class="relative mt-2 grid flex-1 grid-cols-5 grid-rows-3 gap-1.5">
        @for ($cell = 0; $cell < 15; $cell++)<span class="rounded-lg border border-white/5 bg-white/[0.03]"></span>@endfor
        @foreach ($items as $i => [$day, $row, $span, $label, $tone])
            <div class="absolute p-0.5" style="left: {{ $day * 20 }}%; width: {{ $span * 20 }}%; top: {{ $row * 33.33 }}%; height: 33.33%">
                <div class="scn-pop flex h-full items-center rounded-lg px-2 font-semibold text-white {{ $tone }}" style="--t:9s; --d:{{ .4 + $i * .5 }}s">{{ $label }}</div>
            </div>
        @endforeach
    </div>
    <div class="mt-2.5 flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.05] px-3 py-2 text-navy-100">
        <span class="flex -space-x-1.5">@foreach (['bg-signal-500', 'bg-brand-500', 'bg-ai-500'] as $a)<span class="size-5 rounded-full ring-2 ring-navy-900 {{ $a }}"></span>@endforeach</span>
        <span>{{ $d['team'] }}</span>
    </div>
</div>
