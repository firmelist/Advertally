{{-- Dedicated teams: role slots fill one by one until the team is complete and ready. --}}
@php $roles = $d['roles']; @endphp
<div class="flex h-full flex-col text-[11px]">
    <div class="grid flex-1 grid-cols-2 gap-2.5 sm:grid-cols-3">
        @foreach ($roles as $i => [$role, $skills])
            <div class="relative flex flex-col items-center justify-center rounded-xl border border-dashed border-white/20 p-2.5 text-center">
                <span class="text-[10px] text-navy-300">{{ $role }}</span>
                <div class="scn-pop absolute inset-0 flex flex-col items-center justify-center rounded-xl border border-white/15 bg-navy-800 p-2" style="--t:9s; --d:{{ .5 + $i * .6 }}s">
                    <span class="grid size-9 place-items-center rounded-full text-[11px] font-bold text-white {{ ['bg-signal-500', 'bg-brand-500', 'bg-ai-500', 'bg-growth-600', 'bg-brand-400', 'bg-ai-400'][$i % 6] }}">{{ collect(explode(' ', $role))->map(fn ($w) => $w[0])->take(2)->implode('') }}</span>
                    <p class="mt-1.5 font-bold text-white">{{ $role }}</p>
                    <p class="text-[9px] text-navy-300">{{ $skills }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="scn-pop mt-3 flex items-center justify-center gap-2 rounded-xl bg-growth-600 py-2 font-bold text-white" style="--t:9s; --d:{{ .5 + count($roles) * .6 }}s">
        <x-glyph name="check-circle" class="size-4" /> {{ $d['ready'] }}
    </div>
</div>
