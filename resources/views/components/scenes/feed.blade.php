{{-- LinkedIn / social: a scrolling feed where your post stops the thumb and collects reactions. --}}
@php
    $accent = $d['accent'] ?? 'brand';
    $posts = [false, true, false, false, true, false];
@endphp
<div class="relative mx-auto h-full max-w-[19rem] overflow-hidden rounded-2xl border border-white/10 bg-navy-950/70">
    <div class="absolute inset-x-0 top-0 z-10 flex items-center gap-2 border-b border-white/10 bg-navy-900/95 px-3 py-2 text-[11px] font-bold text-white">
        <x-glyph :name="$d['icon'] ?? 'linkedin'" class="size-4 text-brand-300" /> {{ $d['network'] }}
    </div>
    <div class="scn-scroll-y space-y-2.5 px-3 pt-11" style="--t:16s">
        @foreach ([...$posts, ...$posts] as $mine)
            <div @class(['relative rounded-xl border p-2.5', 'border-brand-400/60 bg-navy-800 shadow-[0_0_24px_rgb(37_99_235/.35)]' => $mine, 'border-white/5 bg-white/[0.03]' => ! $mine])>
                <div class="flex items-center gap-2">
                    <span @class(['size-6 rounded-full', 'bg-gradient-to-br from-signal-400 to-ai-500' => $mine, 'bg-white/15' => ! $mine])></span>
                    <div class="flex-1">
                        <p @class(['text-[11px] font-bold', 'text-white' => $mine, 'text-transparent' => ! $mine])>{{ $mine ? $d['author'] : '·' }}</p>
                        <p class="text-[9px] text-navy-300">{{ $mine ? $d['tag'] : '' }}</p>
                    </div>
                </div>
                @if ($mine)
                    <p class="mt-2 text-[11px] leading-snug text-navy-100">{{ $d['post'] }}</p>
                    <div class="mt-2 h-14 rounded-lg bg-gradient-to-br from-brand-600/50 via-ai-500/40 to-signal-500/30"></div>
                    @foreach (['👍', '💡', '👏'] as $e => $emoji)
                        <span class="scn-fall absolute right-3 bottom-3 text-sm" style="--t:2.6s; --d:{{ $e * .8 }}s; --y:-70px; --end:0">{{ $emoji }}</span>
                    @endforeach
                @else
                    <div class="mt-2 h-1.5 w-[90%] rounded bg-white/10"></div>
                    <div class="mt-1.5 h-1.5 w-[70%] rounded bg-white/10"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
