@props(['testimonials', 'title' => 'Business owners who trust us with their growth'])
@if ($testimonials->isNotEmpty())
    <section class="section overflow-hidden" x-data="{ scroll(dir) { $refs.track.scrollBy({ left: dir * ($refs.track.clientWidth * 0.8), behavior: 'smooth' }) } }">
        <div class="container-x">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <x-section-heading eyebrow="Client stories" :title="$title" align="left" />
                <div class="flex shrink-0 gap-2">
                    <button type="button" @click="scroll(-1)" class="grid size-11 place-items-center rounded-full border border-line hover:border-brand-300 hover:text-brand-700" aria-label="Previous"><x-lucide name="arrow-right" class="size-5 rotate-180" /></button>
                    <button type="button" @click="scroll(1)" class="grid size-11 place-items-center rounded-full border border-line hover:border-brand-300 hover:text-brand-700" aria-label="Next"><x-lucide name="arrow-right" class="size-5" /></button>
                </div>
            </div>
            <div x-ref="track" class="-mx-4 mt-10 flex snap-x snap-mandatory gap-5 overflow-x-auto px-4 pb-4 [scrollbar-width:none] sm:mx-0 sm:px-0 [&::-webkit-scrollbar]:hidden">
                @foreach ($testimonials as $t)
                    <figure class="card flex w-[85%] shrink-0 snap-start flex-col p-7 sm:w-[46%] lg:w-[31.5%]">
                        <div class="flex gap-0.5 text-accent-500" aria-label="{{ $t->rating }} out of 5 stars">
                            @for ($s = 0; $s < $t->rating; $s++)<svg class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>@endfor
                        </div>
                        <blockquote class="mt-4 flex-1 text-[15px] leading-relaxed text-ink/85">“{{ $t->quote }}”</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                            @if ($t->photo)
                                <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->name }}" class="size-11 rounded-full object-cover" loading="lazy" width="44" height="44">
                            @else
                                <span class="grid size-11 place-items-center rounded-full bg-brand-100 font-display text-sm font-bold text-brand-800">{{ $t->initials }}</span>
                            @endif
                            <span>
                                <span class="block text-sm font-semibold text-ink">{{ $t->name }}</span>
                                <span class="block text-xs text-muted">{{ $t->designation }}, {{ $t->company }}{{ $t->city ? ' · '.$t->city : '' }}</span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
