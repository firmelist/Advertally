@php $steps = $data['steps'] ?? []; $dark = (bool) ($data['dark'] ?? false); @endphp
<section class="{{ $dark ? 'bg-navy-field relative overflow-hidden py-20 sm:py-28' : 'section bg-white' }}">
    @if ($dark)<div class="bg-dots-dark absolute inset-0 opacity-60" aria-hidden="true"></div>@endif
    <div class="container-x relative">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? null" :title="$data['headline'] ?? ''" :intro="$data['intro'] ?? null" :dark="$dark" />
        <ol class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($steps as $i => $step)
                <li class="{{ $dark ? 'card-dark' : 'card' }} relative p-7" data-reveal>
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-bold {{ $dark ? 'text-signal-400' : 'text-brand-600' }}">STEP {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        @if (! empty($step['icon']))
                            <x-glyph :name="$step['icon']" class="size-5 {{ $dark ? 'text-navy-300' : 'text-navy-300' }}" />
                        @endif
                    </div>
                    <h3 class="mt-4 text-xl font-bold {{ $dark ? 'text-white' : 'text-ink' }}">{{ $step['title'] ?? '' }}</h3>
                    <p class="mt-2 text-[15px] leading-relaxed {{ $dark ? 'text-navy-200' : 'text-muted' }}">{{ $step['text'] ?? '' }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
