<title>{{ $seo->fullTitle() }}</title>
<meta name="description" content="{{ $seo->metaDescription() }}">
<meta name="robots" content="{{ $seo->robots }}">
<link rel="canonical" href="{{ $seo->canonicalUrl() }}">
<link rel="alternate" type="text/plain" title="LLM site guide" href="{{ route('llms') }}">

<meta property="og:site_name" content="{{ setting('company_name', 'Advertally') }}">
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:title" content="{{ $seo->fullTitle() }}">
<meta property="og:description" content="{{ $seo->metaDescription() }}">
<meta property="og:url" content="{{ $seo->canonicalUrl() }}">
<meta property="og:image" content="{{ $seo->imageUrl() }}">
<meta property="og:locale" content="en_IN">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo->fullTitle() }}">
<meta name="twitter:description" content="{{ $seo->metaDescription() }}">
<meta name="twitter:image" content="{{ $seo->imageUrl() }}">
@if ($handle = setting('x_handle'))
    <meta name="twitter:site" content="{{ $handle }}">
@endif

@if ($v = config('advertally.analytics.google_site_verification'))
    <meta name="google-site-verification" content="{{ $v }}">
@endif
@if ($v = config('advertally.analytics.bing_site_verification'))
    <meta name="msvalidate.01" content="{{ $v }}">
@endif

<script type="application/ld+json">{!! json_encode($seo->graph(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
