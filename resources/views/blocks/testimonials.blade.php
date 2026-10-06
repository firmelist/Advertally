@php $testimonials = \App\Models\Testimonial::query()->where('is_published', true)->orderBy('sort_order')->take(3)->get(); @endphp
@if ($testimonials->isNotEmpty())
<section class="section bg-white" aria-labelledby="testimonials-title">
    <div class="container-x">
        <x-section-heading :eyebrow="$data['eyebrow'] ?? 'In their words'" :title="$data['headline'] ?? 'What leaders say about working with us.'" align="center" id="testimonials-title" />
        <div @class(['mt-14 grid gap-6', 'md:grid-cols-2' => $testimonials->count() === 2, 'md:grid-cols-3' => $testimonials->count() >= 3, 'mx-auto max-w-2xl' => $testimonials->count() === 1])>
            @foreach ($testimonials as $t)
                <figure class="card flex flex-col p-7" data-reveal>
                    <x-glyph name="quote" class="size-8 text-brand-200" />
                    <blockquote class="mt-4 flex-1 text-[15px] leading-relaxed text-ink">“{{ $t->quote }}”</blockquote>
                    <figcaption class="mt-6 flex items-center gap-3 border-t border-line pt-5">
                        @if ($t->photo)
                            <img src="{{ media_url($t->photo) }}" alt="" class="size-10 rounded-full object-cover" loading="lazy">
                        @endif
                        <span>
                            <span class="block text-sm font-bold text-ink">{{ $t->name }}</span>
                            <span class="block text-xs text-muted">{{ collect([$t->role, $t->company])->filter()->implode(', ') }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif
