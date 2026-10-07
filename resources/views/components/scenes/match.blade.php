{{-- Technology & Talent (vertical): profiles shuffle past your brief — the right specialist matches and joins your team. --}}
@php $profiles = $d['profiles']; @endphp
<div class="grid h-full grid-cols-[1fr_1.1fr] items-center gap-3 text-[11px]">
    <div class="rounded-xl border border-white/10 bg-white/[0.06] p-3">
        <p class="text-[9px] font-bold tracking-wider text-navy-300 uppercase">Your brief</p>
        <p class="mt-1 font-bold text-white">{{ $d['brief'] }}</p>
        <div class="mt-2 flex flex-wrap gap-1">
            @foreach ($d['needs'] as $n)<span class="rounded-full bg-brand-600/30 px-2 py-0.5 font-semibold text-brand-100">{{ $n }}</span>@endforeach
        </div>
        <p class="mt-3 text-[10px] text-navy-300">Time zone · start date · engagement</p>
    </div>
    <div class="relative h-[85%]">
        @foreach ($profiles as $i => [$name, $role, $match])
            <div class="absolute inset-x-0 top-1/2 rounded-xl border p-3 {{ $match ? 'border-growth-600 bg-navy-800 shadow-[0_0_30px_rgb(22_163_74/.4)]' : 'border-white/10 bg-navy-900' }}"
                style="animation: scn-shuffle 8s ease-in-out infinite; animation-delay: {{ $i * 2 - 8 }}s; z-index: {{ $i }}">
                <div class="flex items-center gap-2.5">
                    <span class="grid size-9 place-items-center rounded-full bg-gradient-to-br from-signal-400 to-ai-500 font-bold text-white">{{ mb_substr($name, 0, 1) }}</span>
                    <div><p class="font-bold text-white">{{ $name }}</p><p class="text-[10px] text-navy-300">{{ $role }}</p></div>
                </div>
                @if ($match)<p class="mt-2 flex items-center gap-1 font-bold text-growth-100"><x-glyph name="check-circle" class="size-4" /> Matched to your brief</p>@endif
            </div>
        @endforeach
    </div>
</div>
<style>@keyframes scn-shuffle { 0% { transform: translate(60%, -50%) rotate(6deg); opacity: 0; } 10%, 22% { transform: translate(0, -50%); opacity: 1; } 30%, 100% { transform: translate(-60%, -50%) rotate(-6deg); opacity: 0; } }</style>
