{{-- Funnels: one entry, two paths — ready buyers book a call, not-yet buyers are nurtured until they are. --}}
<div class="relative h-full text-[11px]">
    <svg class="absolute inset-0 size-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <path id="br-a" d="M50 14 V30 C50 40, 22 38, 22 50 V64" stroke="rgba(255,255,255,.15)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
        <path id="br-b" d="M50 14 V30 C50 40, 78 38, 78 50 V64" stroke="rgba(255,255,255,.15)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
        <path id="br-c" d="M78 72 C78 92, 50 92, 50 80" stroke="rgba(255,255,255,.12)" stroke-width=".5" stroke-dasharray="1.5 2" vector-effect="non-scaling-stroke"/>
        <path id="br-d" d="M22 72 V88" stroke="rgba(255,255,255,.15)" stroke-width=".5" vector-effect="non-scaling-stroke"/>
        @foreach ([0, 1.2, 2.4] as $k)
            <circle r="1.4" fill="#22D3EE"><animateMotion dur="3.6s" begin="-{{ $k }}s" repeatCount="indefinite"><mpath href="#br-a"/></animateMotion></circle>
            <circle r="1.4" fill="#A78BFA"><animateMotion dur="3.6s" begin="-{{ $k + .6 }}s" repeatCount="indefinite"><mpath href="#br-b"/></animateMotion></circle>
        @endforeach
        <circle r="1.2" fill="#A78BFA"><animateMotion dur="4s" begin="-1s" repeatCount="indefinite"><mpath href="#br-c"/></animateMotion></circle>
        <circle r="1.4" fill="#16A34A"><animateMotion dur="2.4s" begin="-.5s" repeatCount="indefinite"><mpath href="#br-d"/></animateMotion></circle>
    </svg>
    <div class="absolute top-[4%] left-1/2 -translate-x-1/2 rounded-xl border border-white/15 bg-navy-800 px-3 py-2 text-center font-bold whitespace-nowrap text-white">
        <x-glyph name="users" class="mx-auto size-4 text-brand-300" /> {{ $d['entry'] }}
    </div>
    <div class="absolute top-[40%] left-1/2 -translate-x-1/2 rounded-full border border-amber-300/50 bg-amber-300/15 px-2.5 py-1 text-[10px] font-bold text-amber-100">Ready to talk?</div>
    <div class="absolute top-[64%] left-[22%] w-[38%] -translate-x-1/2 rounded-xl border border-signal-400/40 bg-signal-500/15 px-2 py-2 text-center">
        <p class="font-bold text-signal-200">Yes</p><p class="text-[10px] text-navy-100">{{ $d['yes'] }}</p>
    </div>
    <div class="absolute top-[64%] left-[78%] w-[38%] -translate-x-1/2 rounded-xl border border-ai-400/40 bg-ai-500/15 px-2 py-2 text-center">
        <p class="font-bold text-ai-200">Not yet</p><p class="text-[10px] text-navy-100">{{ $d['no'] }}</p>
    </div>
    <div class="absolute bottom-[1%] left-[22%] -translate-x-1/2 rounded-full bg-growth-600 px-3 py-1 text-[10px] font-bold whitespace-nowrap text-white">Sales conversation</div>
    <div class="absolute bottom-[10%] left-1/2 -translate-x-1/2 text-[9px] text-navy-300">re-engaged when ready</div>
</div>
