@props(['dark' => false])
<dl {{ $attributes->merge(['class' => 'grid grid-cols-2 gap-6 sm:grid-cols-4']) }}>
    @foreach ([
        [setting('years_experience', 12).'+', 'Years in business'],
        [setting('clients_count', 350).'+', 'SME clients served'],
        [setting('leads_generated', '2.4 Lakh+'), 'Leads generated'],
        [setting('retention_rate', '92%'), 'Client retention'],
    ] as [$value, $label])
        <div>
            <dt class="sr-only">{{ $label }}</dt>
            <dd @class(['font-display text-3xl font-extrabold tracking-tight sm:text-4xl', 'text-white' => $dark, 'text-ink' => ! $dark])>{{ $value }}</dd>
            <p @class(['mt-1 text-sm', 'text-white/60' => $dark, 'text-muted' => ! $dark])>{{ $label }}</p>
        </div>
    @endforeach
</dl>
