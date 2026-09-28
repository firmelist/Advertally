<header x-data="{ mobile: false, mega: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 8"
        @scroll.window="scrolled = window.scrollY > 8"
        @keydown.escape.window="mega = false; mobile = false"
        :class="scrolled ? 'shadow-[0_1px_0_0_var(--color-line),0_8px_24px_-16px_rgb(11_27_77/0.25)]' : ''"
        class="sticky top-0 z-50 bg-white/90 backdrop-blur-md transition-shadow">

    {{-- Top strip --}}
    <div class="hidden bg-brand-950 text-white/80 lg:block">
        <div class="container-x flex h-9 items-center justify-between text-xs">
            <p class="flex items-center gap-2">
                <x-lucide name="award" class="size-3.5 text-accent-400" />
                Trusted by {{ setting('clients_count', 350) }}+ growing businesses across India · GST invoices · No lock-in contracts
            </p>
            <div class="flex items-center gap-5">
                <a href="mailto:{{ setting('email') }}" class="hover:text-white">{{ setting('email') }}</a>
                <span class="text-white/30">|</span>
                <span>{{ setting('business_hours') }}</span>
            </div>
        </div>
    </div>

    <div class="container-x flex h-16 items-center justify-between gap-6 lg:h-[72px]">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="Advertally home">
            <x-logo class="h-7 w-auto lg:h-8" />
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            <div class="relative" @mouseenter="mega = true" @mouseleave="mega = false">
                <button type="button" @click="mega = !mega" :aria-expanded="mega"
                        class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-ink/80 hover:text-brand-700">
                    Services <x-lucide name="chevron-down" class="size-4 transition" ::class="mega && 'rotate-180'" />
                </button>

                {{-- Mega menu --}}
                <div x-cloak x-show="mega" x-transition.opacity.duration.150ms
                     class="fixed inset-x-0 top-[108px] z-40 border-t border-line bg-white shadow-[var(--shadow-lift)]">
                    <div class="container-x grid grid-cols-5 gap-6 py-8">
                        @foreach ($menuHubs as $hub)
                            <div>
                                <a href="{{ $hub->url }}" class="group mb-3 flex items-start gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700 group-hover:bg-brand-600 group-hover:text-white">
                                        <x-lucide :name="$hub->icon" class="size-[18px]" />
                                    </span>
                                    <span>
                                        <span class="block text-[11px] font-semibold tracking-wider text-accent-600 uppercase">{{ $loop->iteration }}. {{ $hub->ladder['label'] ?? '' }}</span>
                                        <span class="block text-sm font-semibold text-ink group-hover:text-brand-700">{{ $hub->title }}</span>
                                    </span>
                                </a>
                                <ul class="space-y-1 border-l border-line pl-4 ml-[18px]">
                                    @foreach ($hub->children as $child)
                                        <li><a href="{{ route('services.show', [$hub->slug, $child->slug]) }}" class="block py-1 text-sm text-muted hover:text-brand-700">{{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-line bg-canvas">
                        <div class="container-x flex items-center justify-between py-3 text-sm">
                            <p class="text-muted"><span class="font-semibold text-ink">Not sure where to start?</span> Get a free audit and a 90-day growth plan.</p>
                            <a href="{{ route('audit.create') }}" class="inline-flex items-center gap-1 font-semibold text-brand-700 hover:text-brand-800">Get free audit <x-lucide name="arrow-right" class="size-4" /></a>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ([
                ['Pricing', route('pricing')],
                ['Hire', route('services.hub', 'hire')],
                ['Case Studies', route('case-studies.index')],
                ['Free Audit', route('audit.create')],
                ['About', route('about')],
                ['Contact', route('contact')],
            ] as [$label, $href])
                <a href="{{ $href }}" @class([
                    'rounded-lg px-3 py-2 text-sm font-medium hover:text-brand-700',
                    'text-brand-700' => url()->current() === $href,
                    'text-ink/80' => url()->current() !== $href,
                ])>{{ $label }}</a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ tel_link() }}" class="flex items-center gap-2 text-sm font-semibold text-ink hover:text-brand-700">
                <x-lucide name="call" class="size-4 text-brand-600" /> {{ setting('phone') }}
            </a>
            <a href="{{ route('audit.create') }}" class="btn-cta !py-2.5 !text-sm">Get Free Audit</a>
        </div>

        {{-- Mobile toggle --}}
        <button type="button" class="-mr-2 grid size-11 place-items-center rounded-lg text-ink lg:hidden" @click="mobile = !mobile" :aria-expanded="mobile" aria-label="Open menu">
            <x-lucide name="menu" class="size-6" x-show="!mobile" />
            <x-lucide name="x" class="size-6" x-cloak x-show="mobile" />
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-cloak x-show="mobile" x-transition.opacity class="max-h-[calc(100dvh-64px)] overflow-y-auto border-t border-line bg-white lg:hidden">
        <nav class="container-x space-y-1 py-4" aria-label="Mobile">
            @foreach ($menuHubs as $hub)
                <div x-data="{ open: false }" class="rounded-xl border border-line">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left">
                        <span class="flex items-center gap-3">
                            <x-lucide :name="$hub->icon" class="size-5 text-brand-600" />
                            <span class="font-semibold">{{ $hub->title }}</span>
                        </span>
                        <x-lucide name="chevron-down" class="size-4 text-muted transition" ::class="open && 'rotate-180'" />
                    </button>
                    <ul x-show="open" x-collapse class="space-y-1 px-4 pb-3">
                        <li><a href="{{ $hub->url }}" class="block py-1.5 text-sm font-semibold text-brand-700">Overview</a></li>
                        @foreach ($hub->children as $child)
                            <li><a href="{{ route('services.show', [$hub->slug, $child->slug]) }}" class="block py-1.5 text-sm text-muted">{{ $child->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            <div class="grid grid-cols-2 gap-2 pt-3 text-sm font-medium">
                <a href="{{ route('pricing') }}" class="rounded-lg bg-canvas px-4 py-3">Pricing</a>
                <a href="{{ route('case-studies.index') }}" class="rounded-lg bg-canvas px-4 py-3">Case Studies</a>
                <a href="{{ route('about') }}" class="rounded-lg bg-canvas px-4 py-3">About</a>
                <a href="{{ route('contact') }}" class="rounded-lg bg-canvas px-4 py-3">Contact</a>
            </div>
            <a href="{{ route('audit.create') }}" class="btn-cta mt-3 w-full">Get Free Website Audit</a>
        </nav>
    </div>
</header>
