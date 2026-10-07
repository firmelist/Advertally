{{-- E-commerce: a product is added, flies to the cart, and the order syncs with stock and CRM. --}}
<div class="grid h-full grid-cols-[1fr_7.5rem] gap-3 text-[11px]">
    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-white p-2.5">
        <div class="flex items-center justify-between">
            <span class="h-2 w-12 rounded bg-navy-900"></span>
            <span class="relative" style="animation: scn-cart-bump 6s ease-in-out infinite">
                <x-glyph name="cart" class="size-5 text-navy-900" />
                <span class="scn-pop absolute -top-1.5 -right-1.5 grid size-4 place-items-center rounded-full bg-brand-600 text-[9px] font-bold text-white" style="--t:6s; --d:2.1s">1</span>
            </span>
        </div>
        <div class="mt-2.5 grid grid-cols-2 gap-2">
            @foreach ($d['products'] as $i => $p)
                <div class="relative rounded-lg border border-slate-200 p-1.5">
                    <div class="h-12 rounded bg-gradient-to-br {{ ['from-brand-100 to-violet-100', 'from-cyan-50 to-blue-100', 'from-green-50 to-emerald-100', 'from-violet-50 to-fuchsia-100'][$i % 4] }}"></div>
                    <p class="mt-1 truncate text-[10px] font-semibold text-navy-900">{{ $p }}</p>
                    <span class="mt-1 block rounded bg-navy-900 py-0.5 text-center text-[8px] font-bold text-white">Add to cart</span>
                    @if ($i === 0)
                        <span class="absolute top-4 left-4 size-6 rounded bg-gradient-to-br from-brand-400 to-ai-400 shadow-lg" style="animation: scn-fly 6s ease-in-out infinite"></span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex flex-col gap-2">
        @foreach ([['Order placed', 'check-circle', 2.6], ['Stock updated', 'database', 3.2], ['Customer in CRM', 'users', 3.8], ['Feed to ads', 'megaphone', 4.4]] as [$label, $icon, $delay])
            <div class="scn-pop flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.06] px-2 py-2 font-semibold text-white" style="--t:6s; --d:{{ $delay }}s">
                <x-glyph :name="$icon" class="size-4 text-growth-100" /> <span class="leading-tight">{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>
<style>
    @keyframes scn-fly { 0%, 15% { transform: none; opacity: 0; } 18% { opacity: 1; } 35% { transform: translate(260%, -120%) scale(.4); opacity: 1; } 38%, 100% { transform: translate(260%, -120%) scale(.2); opacity: 0; } }
    @keyframes scn-cart-bump { 0%, 33%, 45%, 100% { transform: none; } 37% { transform: scale(1.3) rotate(-8deg); } 41% { transform: scale(.95); } }
</style>
