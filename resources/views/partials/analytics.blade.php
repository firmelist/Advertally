@php $a = config('advertally.analytics'); @endphp
@if (app()->environment('production') && array_filter(\Illuminate\Support\Arr::only($a, ['gtm_id', 'ga4_id', 'meta_pixel_id', 'linkedin_partner_id', 'clarity_id'])))
{{-- Tags load only after the visitor accepts analytics cookies (see cookie consent). IDs come from .env. --}}
<script>
    window.addEventListener('adv:consent', function () {
        if (window.__advTagsLoaded) return; window.__advTagsLoaded = true;
        function load(src) { var s = document.createElement('script'); s.async = true; s.src = src; document.head.appendChild(s); }
        @if ($a['gtm_id'])
            (function(w,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});load('https://www.googletagmanager.com/gtm.js?id='+i);})(window,'dataLayer',@js($a['gtm_id']));
        @endif
        @if ($a['ga4_id'] && ! $a['gtm_id'])
            load('https://www.googletagmanager.com/gtag/js?id=' + @js($a['ga4_id']));
            window.dataLayer = window.dataLayer || []; window.gtag = function(){ dataLayer.push(arguments); };
            gtag('js', new Date()); gtag('config', @js($a['ga4_id']));
        @endif
        @if ($a['meta_pixel_id'])
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];load('https://connect.facebook.net/en_US/fbevents.js');}(window,document);
            fbq('init', @js($a['meta_pixel_id'])); fbq('track', 'PageView');
        @endif
        @if ($a['linkedin_partner_id'])
            window._linkedin_partner_id = @js($a['linkedin_partner_id']); window._linkedin_data_partner_ids = [window._linkedin_partner_id];
            load('https://snap.licdn.com/li.lms-analytics/insight.min.js');
        @endif
        @if ($a['clarity_id'])
            (function(c,l,a,r,i){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};load('https://www.clarity.ms/tag/'+i);})(window,document,'clarity','script',@js($a['clarity_id']));
        @endif
    });
</script>
@endif
