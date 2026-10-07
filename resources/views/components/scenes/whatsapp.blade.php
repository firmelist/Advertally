{{-- WhatsApp: an enquiry gets an instant, approved-template reply, ticks turn blue, and sales picks it up. --}}
<div class="relative grid h-full place-items-center">
    <div class="relative flex h-full max-h-[21rem] w-[13.5rem] flex-col overflow-hidden rounded-[1.8rem] border-4 border-navy-700 bg-[#0b141a] shadow-[0_20px_60px_rgb(22_163_74/.35)]">
        <div class="flex items-center gap-2 bg-[#202c33] px-3 py-2.5 text-[11px] text-white">
            <span class="grid size-7 place-items-center rounded-full bg-growth-600 font-bold">{{ mb_substr($d['brand'], 0, 1) }}</span>
            <div><p class="font-bold">{{ $d['brand'] }}</p><p class="text-[9px] text-growth-100">Business account</p></div>
        </div>
        <div class="flex-1 space-y-2 bg-[radial-gradient(rgb(255_255_255/.04)_1px,transparent_1px)] [background-size:12px_12px] p-2.5 text-[11px]">
            <p class="scn-seq ml-auto w-fit max-w-[85%] rounded-lg rounded-tr-none bg-[#005c4b] px-2.5 py-1.5 text-white" style="--t:9s; --d:.3s">{{ $d['incoming'] }}</p>
            <div class="scn-seq w-fit max-w-[88%] rounded-lg rounded-tl-none bg-[#202c33] px-2.5 py-1.5 text-white" style="--t:9s; --d:1.3s">
                {{ $d['reply'] }}
                <span class="mt-1 flex items-center justify-end gap-1 text-[9px] text-white/50">auto-reply · template ✓
                    <span class="relative inline-block w-4"><span class="text-white/50">✓✓</span><span class="scn-pop absolute inset-0 text-signal-400" style="--t:9s; --d:2.4s">✓✓</span></span>
                </span>
            </div>
            <div class="scn-seq flex flex-wrap gap-1" style="--t:9s; --d:2.8s">
                @foreach ($d['buttons'] as $b)<span class="rounded-full border border-growth-600/60 px-2 py-0.5 text-[10px] text-growth-100">{{ $b }}</span>@endforeach
            </div>
            <p class="scn-seq ml-auto w-fit rounded-lg rounded-tr-none bg-[#005c4b] px-2.5 py-1.5 text-white" style="--t:9s; --d:3.8s">{{ $d['choice'] }}</p>
        </div>
    </div>
    <div class="scn-pop absolute right-1 bottom-6 rounded-xl border border-white/10 bg-navy-900/95 px-3 py-2 text-[10px] text-navy-100 sm:right-4" style="--t:9s; --d:4.6s">
        <p class="font-bold text-white">Logged to CRM</p><p>Owner alerted instantly</p>
    </div>
</div>
