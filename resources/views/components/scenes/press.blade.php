{{-- Digital PR: coverage lands in the publications your buyers read — each one mentioning you. --}}
@php $pubs = $d['publications']; @endphp
<div class="relative h-full text-[11px]">
    @foreach ($pubs as $i => $pub)
        <div class="scn-seq absolute inset-x-[6%] rounded-xl border border-white/10 bg-[#f4f1ea] p-3 text-navy-900 shadow-[0_14px_40px_rgb(0_0_0/.35)]"
            style="top: {{ 4 + $i * 21 }}%; z-index: {{ $i + 1 }}; rotate: {{ [-2, 1.5, -1, 2][$i % 4] }}deg; --t:10s; --d:{{ $i * 1.4 }}s; animation-name: scn-press">
            <div>
                <div class="flex items-center justify-between border-b border-navy-900/15 pb-1.5">
                    <span class="font-serif text-[13px] font-black tracking-tight">{{ $pub }}</span>
                    <span class="text-[9px] text-navy-500">{{ ['Business', 'Technology', 'Industry', 'Opinion'][$i % 4] }}</span>
                </div>
                <p class="mt-1.5 text-[11px] leading-snug">
                    {{ ['Why AI search is reshaping B2B buying —', 'Experts weigh in: what buyers expect now,', 'The new playbook for measurable growth,', 'Leaders on building trust before the first call,'][$i % 4] }}
                    <mark class="rounded bg-ai-200 px-1 font-bold text-navy-900">{{ $d['brand'] }}</mark> {{ ['explains', 'says', 'shares', 'argues'][$i % 4] }}
                </p>
            </div>
        </div>
    @endforeach
    <div class="scn-pop absolute right-2 bottom-2 z-20 flex items-center gap-1.5 rounded-full bg-growth-600 px-3 py-1.5 text-[10px] font-bold text-white shadow-lg" style="--t:10s; --d:6s">
        <x-glyph name="award" class="size-3.5" /> Earned mention · authority link
    </div>
</div>
<style>@keyframes scn-press { 0% { opacity: 0; transform: translateY(-30px) scale(1.05); } 5% { opacity: 1; transform: none; } 88% { opacity: 1; } 96%, 100% { opacity: 0; } }</style>
