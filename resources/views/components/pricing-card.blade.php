@props(['plan', 'billing' => false])
{{-- When $billing is true the card reacts to the parent Alpine "billing" toggle (monthly/quarterly/yearly). --}}
<div @class([
    'relative flex flex-col rounded-[var(--radius-card)] p-7',
    'bg-brand-950 text-white shadow-[var(--shadow-lift)] ring-2 ring-brand-600 lg:-my-3 lg:py-10' => $plan->is_popular,
    'card' => ! $plan->is_popular,
])>
    @if ($plan->is_popular)
        <span class="absolute -top-3 left-7 rounded-full bg-accent-500 px-3 py-1 text-xs font-bold text-ink">Most popular</span>
    @endif
    <p @class(['text-sm font-semibold', 'text-accent-300' => $plan->is_popular, 'text-brand-700' => ! $plan->is_popular])>{{ $plan->name }}</p>
    <p @class(['mt-1 text-sm', 'text-white/70' => $plan->is_popular, 'text-muted' => ! $plan->is_popular])>{{ $plan->tagline }}</p>
    <p class="mt-5 flex items-baseline gap-1">
        @if ($billing && $plan->price_unit === 'month')
            <span class="font-display text-4xl font-extrabold tracking-tight"
                  x-text="'₹' + Math.round({{ $plan->price }} * (1 - (discounts[billing] || 0))).toLocaleString('en-IN')">{{ inr($plan->price) }}</span>
        @else
            <span class="font-display text-4xl font-extrabold tracking-tight">{{ inr($plan->price) }}</span>
        @endif
        <span @class(['text-sm', 'text-white/60' => $plan->is_popular, 'text-muted' => ! $plan->is_popular])>{{ $plan->unit_label }}</span>
    </p>
    @if ($plan->audience)
        <p @class(['mt-2 text-xs', 'text-white/60' => $plan->is_popular, 'text-muted' => ! $plan->is_popular])>Best for {{ strtolower(explode(' (', config('advertally.business_sizes')[$plan->audience] ?? $plan->audience)[0]) }} businesses · + GST</p>
    @endif
    <ul class="mt-6 flex-1 space-y-3">
        @foreach ($plan->features ?? [] as $f)
            <li class="flex gap-3 text-sm leading-relaxed">
                <x-lucide name="check" :class="'mt-0.5 size-4 shrink-0 '.($plan->is_popular ? 'text-accent-400' : 'text-teal-700')" />
                <span @class(['text-white/85' => $plan->is_popular, 'text-ink/85' => ! $plan->is_popular])>{{ $f }}</span>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('contact', ['plan' => $plan->name]) }}#quote" @class(['mt-8 w-full', 'btn-cta' => $plan->is_popular, 'btn-ghost' => ! $plan->is_popular])>{{ $plan->cta_label }}</a>
</div>
