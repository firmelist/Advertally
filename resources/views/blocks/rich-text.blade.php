<section class="section bg-white">
    <div class="container-narrow">
        @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
        @if (! empty($data['heading']))<h2 class="h-section mt-3">{{ $data['heading'] }}</h2>@endif
        <div class="prose-adv mt-8">{!! str($data['body'] ?? '')->sanitizeHtml() !!}</div>
    </div>
</section>
