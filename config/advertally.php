<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    | Colours are defined as design tokens in resources/css/app.css (@theme).
    | They are repeated here for emails, PDFs and the Filament admin panel.
    |
    |  Trust Blue   #2952CC  – primary: trust, technology, reliability
    |  Deep Navy    #0B1B4D  – headings, dark sections, text on orange
    |  Growth Orange#F26B1D  – CTAs only: action, growth, conversion
    |  Tech Teal    #0F766E  – automation / success accents
    */
    'brand' => [
        'primary' => '#2952CC',
        'navy' => '#0B1B4D',
        'accent' => '#F26B1D',
        'teal' => '#0F766E',
    ],

    'lead_alert_emails' => array_filter(array_map('trim', explode(',', (string) env('LEAD_ALERT_EMAILS', '')))),

    'booking_url' => env('BOOKING_URL', 'https://calendly.com/advertally/30min'),

    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'alert_to' => env('WHATSAPP_ALERT_TO'),
        'template_lead_alert' => env('WHATSAPP_TEMPLATE_LEAD_ALERT', 'new_lead_alert'),
        'template_thank_you' => env('WHATSAPP_TEMPLATE_THANK_YOU', 'lead_thank_you'),
        'api_version' => 'v21.0',
    ],

    'slack_lead_webhook' => env('SLACK_LEAD_WEBHOOK'),

    'lead_webhook_url' => env('LEAD_WEBHOOK_URL'),

    'pagespeed_api_key' => env('PAGESPEED_API_KEY'),

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
    | The SME growth ladder — the backbone of navigation and upsell logic.
    |--------------------------------------------------------------------------
    | key => [step label, hub slug, headline]
    */
    'ladder' => [
        'grow' => ['step' => 1, 'label' => 'Grow', 'slug' => 'digital-marketing', 'title' => 'Digital Marketing', 'icon' => 'trending-up', 'line' => 'Get found, get leads.'],
        'build' => ['step' => 2, 'label' => 'Build', 'slug' => 'web-development', 'title' => 'Websites & E-commerce', 'icon' => 'monitor', 'line' => 'Turn visitors into customers.'],
        'scale' => ['step' => 3, 'label' => 'Scale', 'slug' => 'hire', 'title' => 'Hire Dedicated Resources', 'icon' => 'users', 'line' => 'Add skilled people without hiring overhead.'],
        'automate' => ['step' => 4, 'label' => 'Automate', 'slug' => 'solutions', 'title' => 'CRM & Automation', 'icon' => 'workflow', 'line' => 'Stop losing leads in Excel & WhatsApp.'],
        'transform' => ['step' => 5, 'label' => 'Transform', 'slug' => 'custom-software', 'title' => 'Custom Software', 'icon' => 'code', 'line' => 'Software built around how you work.'],
    ],

    'business_sizes' => [
        'micro' => 'Micro (turnover up to ₹10 Cr)',
        'small' => 'Small (₹10–100 Cr)',
        'medium' => 'Medium (₹100–500 Cr)',
        'large' => 'Large (₹500 Cr+)',
    ],

    'budgets' => [
        'under_10k' => 'Under ₹10,000 / month',
        '10k_25k' => '₹10,000 – 25,000 / month',
        '25k_60k' => '₹25,000 – 60,000 / month',
        '60k_2l' => '₹60,000 – 2 Lakh / month',
        'above_2l' => 'Above ₹2 Lakh / month',
        'project' => 'One-time project',
    ],

    'industries' => [
        'healthcare' => 'Healthcare & Clinics',
        'education' => 'Education & Coaching',
        'real-estate' => 'Real Estate',
        'manufacturing' => 'Manufacturing & Exporters',
        'd2c' => 'D2C & E-commerce',
        'hospitality' => 'Hospitality & Restaurants',
        'professional' => 'Professional Services',
        'local-services' => 'Local Services',
        'other' => 'Other',
    ],

    'lead_statuses' => [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal' => 'Proposal Sent',
        'won' => 'Won',
        'lost' => 'Lost',
    ],
];
