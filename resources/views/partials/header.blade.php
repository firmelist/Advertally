<header x-data="megaMenu"
    x-init="scrolled = window.scrollY > 8"
    @scroll.window.passive="scrolled = window.scrollY > 8"
    @keydown.escape.window="close(); drawer = false"
    class="sticky top-0 z-40 border-b transition-colors duration-300"
    :class="scrolled || open || drawer ? 'border-line bg-white/95 backdrop-blur-md' : 'border-transparent bg-white/70 backdrop-blur'">
    <div class="container-x flex h-[68px] items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="Advertally home"><x-logo /></a>

        {{-- Desktop navigation --}}
        <nav class="hidden h-full items-center lg:flex" aria-label="Main">
            <ul class="flex h-full items-center gap-0.5">
                @foreach ($headerNav as $item)
                    @php $isMega = $item->children->contains(fn ($c) => $c->children->isNotEmpty()); @endphp
                    <li class="flex h-full items-center" @if ($item->children->isNotEmpty()) @mouseenter="show({{ $item->id }})" @mouseleave="hide()" @endif>
                        @if ($item->children->isEmpty())
                            <a href="{{ $item->href() }}" class="rounded-lg px-3 py-2 text-[14px] font-semibold text-ink/80 transition hover:bg-navy-50 hover:text-ink">{{ $item->label }}</a>
                        @else
                            <button type="button" class="flex items-center gap-1 rounded-lg px-3 py-2 text-[14px] font-semibold transition hover:bg-navy-50"
                                :class="open === {{ $item->id }} ? 'text-brand-700' : 'text-ink/80 hover:text-ink'"
                                @click="toggle({{ $item->id }})" :aria-expanded="(open === {{ $item->id }}).toString()" aria-controls="menu-{{ $item->id }}">
                                {{ $item->label }}
                                <x-glyph name="chevron-down" class="size-4 opacity-60 transition" ::class="open === {{ $item->id }} && 'rotate-180'" />
                            </button>

                            {{-- Panel --}}
                            <div id="menu-{{ $item->id }}" x-show="open === {{ $item->id }}" x-cloak
                                x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition duration-100" x-transition:leave-end="opacity-0"
                                @click.outside="close()"
                                class="absolute inset-x-0 top-full border-b border-line bg-white shadow-[0_24px_48px_-24px_rgb(11_31_58/0.25)]">
                                <div class="container-x py-8">
                                    @if ($isMega)
                                        <div class="grid grid-cols-[1fr_17rem] gap-10">
                                            <div class="grid grid-cols-3 gap-x-8 gap-y-8">
                                                @foreach ($item->children as $group)
                                                    <div>
                                                        <a href="{{ $group->href() }}" class="group flex items-start gap-3">
                                                            <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white">
                                                                <x-glyph :name="$group->icon ?: 'sparkles'" class="size-[18px]" />
                                                            </span>
                                                            <span>
                                                                <span class="block text-sm font-bold text-ink group-hover:text-brand-700">{{ $group->label }}</span>
                                                                @if ($group->description)<span class="block text-xs text-muted">{{ $group->description }}</span>@endif
                                                            </span>
                                                        </a>
                                                        <ul class="mt-3 space-y-1.5 border-l border-line pl-[3.25rem] -ml-0">
                                                            @foreach ($group->children as $link)
                                                                <li><a href="{{ $link->href() }}" class="text-[13px] text-muted hover:text-brand-700">{{ $link->label }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a href="{{ route('growth-score') }}" class="bg-navy-field group relative flex flex-col justify-between overflow-hidden rounded-2xl p-6 text-white" data-track="nav_growth_score">
                                                <div class="bg-dots-dark absolute inset-0" aria-hidden="true"></div>
                                                <div class="relative">
                                                    <p class="eyebrow-dark">Growth OS</p>
                                                    <p class="mt-3 text-lg leading-snug font-bold">One growth system. Six engines.</p>
                                                    <p class="mt-2 text-sm text-navy-200">See how your business scores across all six in about four minutes.</p>
                                                </div>
                                                <span class="relative mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-signal-400">Get your Growth Score <x-glyph name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" /></span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-[1fr_17rem] gap-10">
                                            <ul class="grid grid-cols-2 gap-2 xl:grid-cols-3">
                                                @foreach ($item->children as $link)
                                                    <li>
                                                        <a href="{{ $link->href() }}" class="group flex items-start gap-3 rounded-xl p-3 transition hover:bg-canvas">
                                                            <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-navy-50 text-navy-700 transition group-hover:bg-brand-50 group-hover:text-brand-700">
                                                                <x-glyph :name="$link->icon ?: 'arrow-right'" class="size-[18px]" />
                                                            </span>
                                                            <span>
                                                                <span class="block text-sm font-semibold text-ink group-hover:text-brand-700">{{ $link->label }}</span>
                                                                @if ($link->description)<span class="mt-0.5 block text-xs leading-relaxed text-muted">{{ $link->description }}</span>@endif
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="rounded-2xl border border-line bg-canvas p-6">
                                                <p class="text-sm font-bold text-ink">{{ $item->label }}</p>
                                                <p class="mt-2 text-sm leading-relaxed text-muted">{{ $item->description }}</p>
                                                @if ($item->url)
                                                    <a href="{{ $item->href() }}" class="link-arrow mt-4">Overview <x-glyph name="arrow-right" class="size-4" /></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('contact') }}" class="hidden rounded-lg px-3 py-2 text-[14px] font-semibold text-ink/80 hover:text-ink xl:inline-flex" data-track="nav_expert">Talk to an Expert</a>
            <a href="{{ route('growth-score') }}" class="btn-primary !px-3.5 !py-2.5 sm:!px-4" data-track="nav_growth_score">
                <span class="sm:hidden">Growth Score</span><span class="hidden sm:inline">Get Your Growth Score</span>
                <x-glyph name="arrow-right" class="hidden size-4 sm:block" />
            </a>
            <button type="button" class="-mr-2 grid size-11 place-items-center rounded-lg text-ink lg:hidden" @click="drawer = true" aria-label="Open menu" :aria-expanded="drawer.toString()" aria-controls="mobile-drawer">
                <x-glyph name="menu" class="size-6" />
            </button>
        </div>
    </div>

    {{-- Mobile drawer: teleported to <body> so the header's backdrop blur cannot shrink it --}}
    <template x-teleport="body">
    <div id="mobile-drawer" x-show="drawer" x-cloak class="fixed inset-0 z-[60] flex flex-col bg-white lg:hidden" role="dialog" aria-modal="true" aria-label="Menu"
        x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:leave="transition duration-150" x-transition:leave-end="opacity-0"
        x-trap.noscroll="drawer">
        <div class="container-x flex h-[68px] shrink-0 items-center justify-between border-b border-line">
            <a href="{{ route('home') }}" aria-label="Advertally home"><x-logo /></a>
            <button type="button" class="-mr-2 grid size-11 place-items-center rounded-lg text-ink" @click="drawer = false" aria-label="Close menu"><x-glyph name="x" class="size-6" /></button>
        </div>
        <nav class="flex-1 overflow-y-auto" aria-label="Mobile">
            <ul class="container-x divide-y divide-line" x-data="{ section: null }">
                @foreach ($headerNav as $item)
                    <li>
                        @if ($item->children->isEmpty())
                            <a href="{{ $item->href() }}" class="flex items-center justify-between py-4 text-base font-semibold text-ink">{{ $item->label }}<x-glyph name="arrow-right" class="size-4 text-muted" /></a>
                        @else
                            <button type="button" class="flex w-full items-center justify-between py-4 text-left text-base font-semibold text-ink"
                                @click="section = section === {{ $item->id }} ? null : {{ $item->id }}" :aria-expanded="(section === {{ $item->id }}).toString()">
                                {{ $item->label }}
                                <x-glyph name="chevron-down" class="size-5 text-muted transition" ::class="section === {{ $item->id }} && 'rotate-180'" />
                            </button>
                            <div x-show="section === {{ $item->id }}" x-collapse x-cloak>
                                <ul class="space-y-1 pb-4">
                                    @if ($item->url)
                                        <li><a href="{{ $item->href() }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-brand-700">{{ $item->label }} overview</a></li>
                                    @endif
                                    @foreach ($item->children as $child)
                                        <li>
                                            <a href="{{ $child->href() }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[15px] font-medium text-ink hover:bg-canvas">
                                                <x-glyph :name="$child->icon ?: 'arrow-right'" class="size-4 text-brand-600" /> {{ $child->label }}
                                            </a>
                                            @if ($child->children->isNotEmpty())
                                                <ul class="mb-2 ml-10 flex flex-wrap gap-x-4 gap-y-1">
                                                    @foreach ($child->children as $link)
                                                        <li><a href="{{ $link->href() }}" class="text-[13px] text-muted">{{ $link->label }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="container-x shrink-0 space-y-2 border-t border-line py-4">
            <a href="{{ route('growth-score') }}" class="btn-primary btn-lg w-full" data-track="drawer_growth_score">Get Your Growth Score <x-glyph name="arrow-right" class="size-4" /></a>
            <a href="{{ route('contact') }}" class="btn-secondary w-full">Talk to an Expert</a>
        </div>
    </div>
    </template>
</header>
