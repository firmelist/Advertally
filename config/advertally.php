<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand system
    |--------------------------------------------------------------------------
    | Tokens live in resources/css/app.css (@theme). Repeated here for e-mails and the admin panel.
    | Ratio guide: 60% warm white/white · 20% navy · 10% blue · 5% violet · 3% cyan · 2% green.
    */
    'brand' => [
        'blue' => '#2563EB',
        'navy' => '#0B1F3A',
        'violet' => '#7C3AED',
        'cyan' => '#06B6D4',
        'green' => '#16A34A',
    ],

    'lead_alert_emails' => array_filter(array_map('trim', explode(',', (string) env('LEAD_ALERT_EMAILS', '')))),

    'slack_lead_webhook' => env('SLACK_LEAD_WEBHOOK'),

    // Every new lead is POSTed here as JSON (Zoho / HubSpot / Make / n8n).
    'lead_webhook_url' => env('LEAD_WEBHOOK_URL'),

    'booking_url' => env('BOOKING_URL'),

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@advertally.com'),
        'password' => env('ADMIN_PASSWORD', 'ChangeMe@123'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics & tags — IDs come from .env only; blank = not loaded.
    | Scripts load after the visitor accepts analytics cookies.
    |--------------------------------------------------------------------------
    */
    'analytics' => [
        'gtm_id' => env('GTM_ID'),
        'ga4_id' => env('GA4_MEASUREMENT_ID'),
        'meta_pixel_id' => env('META_PIXEL_ID'),
        'linkedin_partner_id' => env('LINKEDIN_PARTNER_ID'),
        'clarity_id' => env('CLARITY_PROJECT_ID'),
        'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
        'bing_site_verification' => env('BING_SITE_VERIFICATION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI providers — server-side only, keys never reach the browser.
    | AI_PROVIDER = none | openai | anthropic | gemini
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'default' => env('AI_PROVIDER', 'none'),
        'timeout' => (int) env('AI_TIMEOUT', 30),
        'providers' => [
            'openai' => ['key' => env('OPENAI_API_KEY'), 'model' => env('OPENAI_MODEL', 'gpt-4.1-mini')],
            'anthropic' => ['key' => env('ANTHROPIC_API_KEY'), 'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-5')],
            'gemini' => ['key' => env('GEMINI_API_KEY'), 'model' => env('GEMINI_MODEL', 'gemini-2.5-flash')],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Form options (keys are stored, labels are shown)
    |--------------------------------------------------------------------------
    */
    'industries' => [
        'technology' => 'Technology',
        'saas' => 'SaaS',
        'it-services' => 'IT Services',
        'consulting' => 'Consulting',
        'professional-services' => 'Professional Services',
        'financial-services' => 'Financial Services',
        'recruitment' => 'Recruitment',
        'b2b-services' => 'Specialised B2B Services',
        'healthcare' => 'Healthcare',
        'education' => 'Education',
        'manufacturing' => 'Manufacturing',
        'other' => 'Other',
    ],

    'service_interests' => [
        'ai-search' => 'AI Search & SEO',
        'demand' => 'Demand generation & paid media',
        'authority' => 'Authority & content',
        'conversion' => 'Websites & conversion',
        'automation' => 'AI agents & automation',
        'intelligence' => 'Analytics & attribution',
        'growth-technology' => 'Growth technology (CRM, AI, API integration)',
        'talent' => 'Technology & talent (dedicated specialists)',
        'not-sure' => 'Not sure yet — need a diagnosis',
    ],

    'challenges' => [
        'not-visible' => 'We are not visible enough in search or AI answers',
        'low-quality-leads' => 'Leads are low quality or inconsistent',
        'high-cac' => 'Customer acquisition cost is too high',
        'low-conversion' => 'Traffic is not converting',
        'no-attribution' => 'We cannot connect marketing to revenue',
        'manual-work' => 'Too much manual marketing and sales work',
        'scaling' => 'We need to scale into new markets or segments',
        'other' => 'Something else',
    ],

    'objectives' => [
        'pipeline' => 'Grow qualified pipeline',
        'ai-visibility' => 'Become visible in AI search',
        'authority' => 'Build category authority',
        'efficiency' => 'Lower acquisition cost',
        'launch' => 'Launch a new product or market',
        'measurement' => 'Measure and attribute revenue',
    ],

    'budgets' => [
        'under-1l' => 'Under ₹1 Lakh / month',
        '1l-3l' => '₹1 – 3 Lakh / month',
        '3l-10l' => '₹3 – 10 Lakh / month',
        '10l-plus' => '₹10 Lakh+ / month',
        'project' => 'Project-based budget',
        'undecided' => 'Not decided yet',
    ],

    'talent_roles' => [
        'developers' => 'Developers (Laravel, PHP, React, Next.js)',
        'designers' => 'UI/UX & brand designers',
        'seo' => 'SEO specialists',
        'marketers' => 'Digital marketers',
        'team' => 'A dedicated team',
    ],
];
