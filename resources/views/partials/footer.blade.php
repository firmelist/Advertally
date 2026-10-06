<footer class="bg-navy-field relative overflow-hidden text-navy-200" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="bg-dots-dark absolute inset-0 opacity-50" aria-hidden="true"></div>

    <div class="container-x relative">
        {{-- Brand + newsletter --}}
        <div class="grid gap-12 border-b border-white/10 py-14 lg:grid-cols-[1.1fr_1fr] lg:py-16">
            <div>
                <x-logo dark />
                <p class="mt-6 text-sm font-semibold tracking-[0.14em] text-signal-400 uppercase">AI-Native Growth &amp; Revenue Partner</p>
                <p class="mt-3 text-3xl leading-tight font-bold text-white sm:text-4xl">Be Found.<br>Be Trusted.<br>Be Chosen.</p>
            </div>
            <div id="newsletter" class="scroll-mt-28 lg:pt-2">
                <p class="text-lg font-bold text-white">Get the AI Growth Intelligence Brief</p>
                <p class="mt-2 text-sm leading-relaxed">A concise monthly briefing on AI search, discovery and B2B growth — research, experiments and what to act on. No fluff.</p>
                @if (session('newsletter'))
                    <p class="mt-5 flex items-center gap-2 rounded-xl bg-growth-600/15 px-4 py-3 text-sm font-semibold text-growth-100" role="status">
                        <x-glyph name="check-circle" class="size-5" /> {{ session('newsletter') }}
                    </p>
                @else
                    <form method="POST" action="{{ route('newsletter.store') }}" class="relative mt-5">
                        @csrf
                        <x-form.guard />
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <label for="newsletter-email" class="sr-only">Work email</label>
                            <input id="newsletter-email" type="email" name="email" required autocomplete="email" placeholder="Your work email"
                                class="block w-full rounded-xl border-white/15 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-navy-300 focus:border-signal-400 focus:ring-signal-400">
                            <button class="btn-primary shrink-0" data-track="newsletter_subscribe">Subscribe</button>
                        </div>
                        @error('email')<p class="mt-2 text-xs font-medium text-red-300">{{ $message }}</p>@enderror
                    </form>
                @endif
            </div>
        </div>

        {{-- Columns --}}
        <nav class="grid grid-cols-2 gap-x-6 gap-y-10 py-14 sm:grid-cols-3 lg:grid-cols-6" aria-label="Footer">
            @foreach ($footerNav as $column)
                <div>
                    <p class="text-sm font-bold text-white">
                        @if ($column->url)<a href="{{ $column->href() }}" class="hover:text-signal-400">{{ $column->label }}</a>@else{{ $column->label }}@endif
                    </p>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($column->children as $link)
                            <li><a href="{{ $link->href() }}" class="text-sm hover:text-white">{{ $link->label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <x-signal class="border-t border-white/10 pt-10 pb-2" dark compact />

        {{-- Legal --}}
        <div class="flex flex-col gap-4 border-t border-white/10 py-8 text-xs sm:flex-row sm:items-center sm:justify-between mt-8">
            <p>© {{ now()->year }} {{ setting('legal_name', 'Advertally') }}. All rights reserved.</p>
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-2">
                @foreach ($legalNav as $link)
                    <li><a href="{{ $link->href() }}" class="hover:text-white">{{ $link->label }}</a></li>
                @endforeach
                @foreach (['linkedin_url' => 'linkedin', 'youtube_url' => 'youtube', 'x_url' => 'x-social', 'instagram_url' => 'instagram'] as $key => $icon)
                    @if ($url = setting($key))
                        <li><a href="{{ $url }}" class="hover:text-white" rel="me noopener" target="_blank" aria-label="Advertally on {{ str($icon)->before('-')->title() }}"><x-glyph :name="$icon" class="size-4" /></a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</footer>
