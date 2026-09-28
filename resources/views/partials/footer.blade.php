<footer class="bg-brand-950 pb-24 text-white/70 lg:pb-0">
    {{-- Newsletter / final nudge --}}
    <div class="border-b border-white/10">
        <div class="container-x flex flex-col gap-6 py-10 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="font-display text-xl font-bold text-white">Get one practical growth idea every week.</p>
                <p class="mt-1 text-sm">Marketing, website and automation tips for Indian business owners. No spam.</p>
            </div>
            <form method="POST" action="{{ route('leads.store') }}" class="flex w-full max-w-md gap-2">
                @csrf
                <input type="hidden" name="form_type" value="contact">
                <input type="hidden" name="form_id" value="newsletter">
                <input type="hidden" name="name" value="Newsletter subscriber">
                <input type="hidden" name="consent" value="1">
                <input type="hidden" name="message" value="Newsletter signup">
                <input type="hidden" name="source_page" value="{{ request()->path() }}">
                <label class="sr-only" for="nl-phone">WhatsApp number</label>
                <input id="nl-phone" name="phone" type="tel" inputmode="numeric" required placeholder="Your WhatsApp number"
                       class="field flex-1 border-white/15 bg-white/10 text-white placeholder:text-white/50">
                <button class="btn-cta shrink-0">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="container-x grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-logo dark class="h-8 w-auto" />
            <p class="mt-4 max-w-sm text-sm leading-relaxed">
                {{ setting('tagline') }}. Digital marketing, websites, dedicated teams, CRM and custom software — one team, one point of contact.
            </p>
            <ul class="mt-6 space-y-3 text-sm">
                <li class="flex gap-3"><x-lucide name="map-pin" class="mt-0.5 size-4 shrink-0 text-accent-400" /> {{ setting('address') }}</li>
                <li class="flex gap-3"><x-lucide name="call" class="mt-0.5 size-4 shrink-0 text-accent-400" /> <a href="{{ tel_link() }}" class="hover:text-white">{{ setting('phone') }}</a></li>
                <li class="flex gap-3"><x-lucide name="mail" class="mt-0.5 size-4 shrink-0 text-accent-400" /> <a href="mailto:{{ setting('email') }}" class="hover:text-white">{{ setting('email') }}</a></li>
                <li class="flex gap-3"><x-lucide name="clock" class="mt-0.5 size-4 shrink-0 text-accent-400" /> {{ setting('business_hours') }}</li>
            </ul>
            <div class="mt-6 flex gap-2">
                @foreach (['facebook_url' => 'facebook', 'instagram_url' => 'instagram', 'linkedin_url' => 'linkedin', 'youtube_url' => 'youtube', 'x_url' => 'x-social'] as $key => $icon)
                    @if (setting($key))
                        <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($icon) }}"
                           class="grid size-9 place-items-center rounded-lg bg-white/5 text-white/70 hover:bg-white/15 hover:text-white">
                            <x-lucide :name="$icon" class="size-4" />
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        @foreach ($menuHubs->chunk(3) as $chunk)
            <div class="space-y-8 lg:col-span-2">
                @foreach ($chunk as $hub)
                    <div>
                        <a href="{{ $hub->url }}" class="text-sm font-semibold text-white hover:text-accent-300">{{ $hub->title }}</a>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach ($hub->children->take(5) as $child)
                                <li><a href="{{ route('services.show', [$hub->slug, $child->slug]) }}" class="hover:text-white">{{ $child->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="lg:col-span-2">
            <p class="text-sm font-semibold text-white">Company</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white">About us</a></li>
                <li><a href="{{ route('case-studies.index') }}" class="hover:text-white">Case studies</a></li>
                <li><a href="{{ route('pricing') }}" class="hover:text-white">Pricing</a></li>
                <li><a href="{{ route('audit.create') }}" class="hover:text-white">Free website audit</a></li>
                <li><a href="{{ route('consultation') }}" class="hover:text-white">Book a consultation</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
            </ul>
            <p class="mt-8 text-sm font-semibold text-white">We serve</p>
            <p class="mt-3 text-sm leading-relaxed">Delhi NCR · Gurugram · Noida · Mumbai · Pune · Bengaluru · Hyderabad · Ahmedabad · Jaipur · Pan-India</p>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-x flex flex-col gap-3 py-6 text-xs sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ date('Y') }} {{ setting('company_name', 'Advertally') }}. All rights reserved.
                @if (setting('gstin')) · GSTIN {{ setting('gstin') }} @endif
                @if (setting('udyam_number')) · Udyam {{ setting('udyam_number') }} @endif
            </p>
            <nav class="flex gap-4" aria-label="Legal">
                <a href="{{ route('privacy') }}" class="hover:text-white">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-white">Terms</a>
                <a href="{{ route('refund') }}" class="hover:text-white">Refund policy</a>
                <a href="{{ route('sitemap') }}" class="hover:text-white">Sitemap</a>
            </nav>
        </div>
    </div>
</footer>
