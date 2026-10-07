@props(['scene'])
{{--
    Service scene: a small animated story of what the service does, in a dark "live view" window.
    $scene = ['name' => view under components/scenes, 'label' => window title, 'data' => scene parameters].
--}}
<figure {{ $attributes->merge(['class' => 'orbit-panel relative overflow-hidden rounded-[2rem] p-4 shadow-[var(--shadow-glow)] sm:p-6']) }}
    aria-label="Illustration: {{ $scene['label'] }}">
    <div class="orbit-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="relative flex items-center justify-between gap-3" aria-hidden="true">
        <span class="flex items-center gap-1.5">
            <span class="size-2.5 rounded-full bg-white/15"></span><span class="size-2.5 rounded-full bg-white/15"></span><span class="size-2.5 rounded-full bg-white/15"></span>
        </span>
        <span class="truncate rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-semibold text-navy-100">
            <span class="mr-1.5 inline-block size-1.5 animate-pulse rounded-full bg-signal-400 align-middle"></span>{{ $scene['label'] }}
        </span>
        <span class="w-[42px]"></span>
    </div>
    <div class="relative mt-4 h-[19rem] overflow-hidden rounded-2xl sm:h-[22rem]" aria-hidden="true">
        @include('components.scenes.'.$scene['name'], ['d' => $scene['data'] ?? []])
    </div>
</figure>
