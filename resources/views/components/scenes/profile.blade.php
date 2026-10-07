{{-- Founder branding: a leader's profile, a post being written in their voice, and the network responding. --}}
<div class="flex h-full flex-col gap-3 text-[11px]">
    <div class="overflow-hidden rounded-xl border border-white/10 bg-white/[0.05]">
        <div class="h-12 bg-gradient-to-r from-brand-600 via-ai-500 to-signal-500"></div>
        <div class="flex items-end gap-3 px-3 pb-3">
            <span class="relative -mt-6 grid size-14 place-items-center rounded-full border-4 border-navy-900 bg-gradient-to-br from-navy-600 to-navy-800 text-sm font-bold text-white">
                {{ $d['initials'] }}
                <span class="scn-pulse absolute inset-0 rounded-full border-2 border-ai-400" style="--t:3s"></span>
            </span>
            <div class="min-w-0 flex-1 pt-1">
                <p class="truncate text-[13px] font-bold text-white">{{ $d['name'] }}</p>
                <p class="truncate text-navy-200">{{ $d['headline'] }}</p>
            </div>
            <span class="scn-glow rounded-full bg-brand-600 px-2.5 py-1 text-[10px] font-bold text-white">Follow</span>
        </div>
    </div>
    <div class="flex-1 rounded-xl border border-white/10 bg-white/[0.04] p-3">
        <p class="text-[10px] font-semibold text-navy-300">New post · drafted from your interview</p>
        <p class="mt-1.5 leading-relaxed text-white"><span class="scn-seq" style="--t:9s; --d:.4s">{{ $d['post'] }}</span><span class="scn-blink text-signal-400">|</span></p>
        <div class="mt-3 flex items-center gap-3 border-t border-white/10 pt-2.5 text-navy-200">
            @foreach ([['👍', 'Insightful', 4], ['💬', 'Comments', 4.6], ['🔁', 'Reposts', 5.2]] as [$emoji, $label, $delay])
                <span class="scn-pop flex items-center gap-1" style="--t:9s; --d:{{ $delay }}s">{{ $emoji }} <span class="text-[10px]">{{ $label }}</span></span>
            @endforeach
        </div>
    </div>
    <div class="scn-seq flex items-center gap-2 rounded-xl border border-growth-600/40 bg-growth-600/10 px-3 py-2 text-growth-100" style="--t:9s; --d:5.8s">
        <x-glyph name="message" class="size-4" /> “Loved this — can we talk about our pipeline?”
    </div>
</div>
