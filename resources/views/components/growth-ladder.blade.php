@props(['hubs'])
{{-- Interactive 5-step SME growth ladder: Grow → Build → Scale → Automate → Transform --}}
<div x-data="{ active: 0 }" class="grid gap-8 lg:grid-cols-12 lg:gap-12">
    <div class="lg:col-span-7">
        <div class="flex h-72 items-end gap-2 sm:h-80 sm:gap-3" role="tablist" aria-label="Growth ladder">
            @foreach ($hubs as $i => $hub)
                @php $h = 44 + $i * 14; @endphp
                <button type="button" role="tab" :aria-selected="active === {{ $i }}"
                        @mouseenter="active = {{ $i }}" @focus="active = {{ $i }}" @click="active = {{ $i }}"
                        class="group relative flex flex-1 flex-col justify-end text-left focus-visible:outline-none"
                        style="height: {{ $h }}%">
                    <span class="mb-2 flex items-center gap-1.5 text-[11px] font-semibold tracking-wider uppercase transition sm:text-xs"
                          :class="active === {{ $i }} ? 'text-accent-600' : 'text-muted'">
                        <span class="hidden sm:inline">Step</span> {{ $i + 1 }}
                    </span>
                    <span class="relative flex h-full flex-col justify-between overflow-hidden rounded-2xl border p-3 transition duration-300 sm:p-4"
                          :class="active === {{ $i }} ? 'border-brand-600 bg-brand-600 text-white shadow-[var(--shadow-lift)] -translate-y-1' : 'border-line bg-white text-ink group-hover:border-brand-300'">
                        <x-lucide :name="$hub->icon" class="size-5 sm:size-6" />
                        <span>
                            <span class="block font-display text-sm font-bold sm:text-lg">{{ $hub->ladder['label'] ?? $hub->title }}</span>
                            <span class="hidden text-xs opacity-80 sm:block">{{ \Illuminate\Support\Str::limit($hub->title, 22) }}</span>
                        </span>
                    </span>
                </button>
            @endforeach
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-muted">
            <x-lucide name="trending-up" class="size-4 text-accent-500" /> Most clients start at Step 1 and add the next step when they're ready.
        </div>
    </div>

    <div class="lg:col-span-5">
        @foreach ($hubs as $i => $hub)
            <div x-show="active === {{ $i }}" @if ($i) x-cloak @endif x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 translate-y-2" class="card h-full p-7">
                <span class="eyebrow">Step {{ $i + 1 }} · {{ $hub->ladder['label'] ?? '' }}</span>
                <h3 class="mt-4 text-2xl font-bold">{{ $hub->title }}</h3>
                <p class="mt-2 text-muted">{{ $hub->short_description }}</p>
                <ul class="mt-5 grid gap-2 sm:grid-cols-2">
                    @foreach ($hub->children->take(6) as $child)
                        <li><a href="{{ route('services.show', [$hub->slug, $child->slug]) }}" class="flex items-center gap-2 text-sm text-ink/85 hover:text-brand-700"><x-lucide name="check" class="size-4 text-teal-700" /> {{ $child->title }}</a></li>
                    @endforeach
                </ul>
                <div class="mt-6 flex flex-wrap items-center gap-4 border-t border-line pt-5">
                    <a href="{{ $hub->url }}" class="btn-primary !py-2.5 !text-sm">Explore {{ $hub->ladder['label'] ?? '' }} <x-lucide name="arrow-right" class="size-4" /></a>
                    @if ($hub->starting_price)
                        <span class="text-sm text-muted">From <strong class="text-ink">{{ inr($hub->starting_price) }}</strong>{{ $hub->price_unit === 'month' ? '/mo' : ($hub->price_unit === 'hour' ? '/hr' : '') }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
