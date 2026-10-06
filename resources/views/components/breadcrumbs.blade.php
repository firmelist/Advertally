@props(['dark' => false])
@php $crumbs = app(\App\Services\Seo::class)->breadcrumbs; @endphp
@if (count($crumbs) > 1)
    <nav aria-label="Breadcrumb" {{ $attributes }}>
        <ol class="flex flex-wrap items-center gap-1.5 text-xs font-medium {{ $dark ? 'text-navy-300' : 'text-muted' }}">
            @foreach ($crumbs as $crumb)
                <li class="flex items-center gap-1.5">
                    @if (! $loop->last)
                        <a href="{{ $crumb['url'] }}" class="{{ $dark ? 'hover:text-white' : 'hover:text-brand-700' }}">{{ $crumb['name'] }}</a>
                        <x-glyph name="chevron-right" class="size-3 opacity-60" />
                    @else
                        <span aria-current="page" class="{{ $dark ? 'text-navy-100' : 'text-ink' }} line-clamp-1">{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
