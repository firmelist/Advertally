@php
    $siteName = setting('company_name', 'Advertally');
    $pageTitle = trim($__env->yieldContent('title')) ?: 'Advertally — One Trusted Digital Partner for Growing Businesses';
    $pageDescription = trim($__env->yieldContent('description')) ?: 'Digital marketing, websites, dedicated teams, CRM and custom software for India\'s growing businesses. One team. One point of contact. Measurable results.';
    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/og-default.png');
    $tracking = array_filter([
        'gtm' => setting('gtm_id'),
        'ga4' => setting('ga4_id'),
        'pixel' => setting('meta_pixel_id'),
        'clarity' => setting('clarity_id'),
    ]);
@endphp
<!DOCTYPE html>
<html lang="en-IN" class="scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    @hasSection('noindex')<meta name="robots" content="noindex, nofollow">@endif
    <meta name="theme-color" content="#0B1B4D">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="en_IN">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Organization + LocalBusiness schema (site-wide) --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => ['Organization', 'ProfessionalService'],
            'name' => $siteName,
            'url' => url('/'),
            'logo' => asset('favicon.svg'),
            'description' => $pageDescription,
            'telephone' => setting('phone'),
            'email' => setting('email'),
            'address' => ['@type' => 'PostalAddress', 'streetAddress' => setting('address'), 'addressCountry' => 'IN'],
            'areaServed' => 'IN',
            'priceRange' => '₹₹',
            'sameAs' => array_values(array_filter([setting('facebook_url'), setting('instagram_url'), setting('linkedin_url'), setting('youtube_url'), setting('x_url')])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('schema')
    @stack('head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg">Skip to content</a>

    @include('partials.header')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.floating')

    {{-- Fire conversion event once after a successful form submission --}}
    @if (session('lead_tracked'))
        <script>document.addEventListener('DOMContentLoaded', () => window.track && window.track('generate_lead', { form: @json(session('lead_form', 'audit')) }));</script>
    @endif

    {{-- Analytics load only after cookie consent (DPDP Act friendly) --}}
    @if ($tracking)
        <script>
            (function () {
                const ids = @json($tracking);
                function load() {
                    if (window.__advTracking) return; window.__advTracking = true;
                    const s = (src) => { const e = document.createElement('script'); e.async = true; e.src = src; document.head.appendChild(e); };
                    if (ids.gtm) { window.dataLayer = window.dataLayer || []; window.dataLayer.push({'gtm.start': Date.now(), event: 'gtm.js'}); s('https://www.googletagmanager.com/gtm.js?id=' + ids.gtm); }
                    if (ids.ga4) { s('https://www.googletagmanager.com/gtag/js?id=' + ids.ga4); window.dataLayer = window.dataLayer || []; window.gtag = function(){ dataLayer.push(arguments); }; gtag('js', new Date()); gtag('config', ids.ga4); }
                    if (ids.pixel) { !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js'); fbq('init', ids.pixel); fbq('track', 'PageView'); }
                    if (ids.clarity) { (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window, document, "clarity", "script", ids.clarity); }
                }
                if (document.cookie.includes('adv_consent=all')) load();
                window.addEventListener('adv:consent', load);
            })();
        </script>
    @endif
    @stack('scripts')
</body>
</html>
