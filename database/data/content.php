<?php

/*
| Seed content for pricing, testimonials, FAQs, case studies and settings.
| All sample names/numbers are placeholders — replace them with real data in the admin panel.
*/

return [

    'settings' => [
        // group, key, label, value, type
        ['general', 'company_name', 'Company name', 'Advertally Technology', 'text'],
        ['general', 'tagline', 'Tagline', 'One Trusted Digital Partner for Growing Businesses', 'text'],
        ['general', 'phone', 'Phone', '+91 99999 99999', 'text'],
        ['general', 'whatsapp_number', 'WhatsApp number (with country code)', '919999999999', 'text'],
        ['general', 'email', 'Email', 'hello@advertally.com', 'email'],
        ['general', 'address', 'Office address', 'Sector 44, Gurugram, Haryana 122003', 'textarea'],
        ['general', 'business_hours', 'Business hours', 'Mon–Sat, 10:00 AM – 7:00 PM', 'text'],
        ['general', 'map_embed_url', 'Google Maps embed URL', 'https://www.google.com/maps?q=Sector+44+Gurugram&output=embed', 'url'],
        ['stats', 'years_experience', 'Years of experience', '12', 'number'],
        ['stats', 'clients_count', 'Clients served', '350', 'number'],
        ['stats', 'leads_generated', 'Leads generated for clients', '2.4 Lakh+', 'text'],
        ['stats', 'retention_rate', 'Client retention', '92%', 'text'],
        ['stats', 'google_rating', 'Google rating', '4.9', 'text'],
        ['social', 'facebook_url', 'Facebook', 'https://facebook.com/advertally', 'url'],
        ['social', 'instagram_url', 'Instagram', 'https://instagram.com/advertally_official', 'url'],
        ['social', 'linkedin_url', 'LinkedIn', 'https://linkedin.com/company/advertally-official', 'url'],
        ['social', 'youtube_url', 'YouTube', '', 'url'],
        ['social', 'x_url', 'X (Twitter)', 'https://x.com/advertally', 'url'],
        ['tracking', 'gtm_id', 'Google Tag Manager ID', '', 'text'],
        ['tracking', 'ga4_id', 'GA4 Measurement ID', '', 'text'],
        ['tracking', 'meta_pixel_id', 'Meta Pixel ID', '', 'text'],
        ['tracking', 'clarity_id', 'Microsoft Clarity ID', '', 'text'],
        ['legal', 'gstin', 'GSTIN', '', 'text'],
        ['legal', 'udyam_number', 'Udyam registration no.', '', 'text'],
    ],

    'pricing_plans' => [
        // Marketing
        ['category' => 'marketing', 'name' => 'Starter', 'tagline' => 'Get found locally', 'audience' => 'micro', 'price' => 9999, 'price_unit' => 'month', 'is_popular' => false, 'cta_label' => 'Start with Starter',
            'features' => ['Google Business Profile optimisation', '12 social media posts / month', 'Basic local SEO (5 keywords)', 'WhatsApp Business catalogue', 'Monthly performance report']],
        ['category' => 'marketing', 'name' => 'Growth', 'tagline' => 'Consistent leads every month', 'audience' => 'small', 'price' => 24999, 'price_unit' => 'month', 'is_popular' => true, 'cta_label' => 'Choose Growth',
            'features' => ['Everything in Starter', 'SEO for 20 keywords', 'Google OR Meta ads management*', '20 posts + 4 reels / month', 'Landing page optimisation', 'Call & form tracking', 'Monthly review call']],
        ['category' => 'marketing', 'name' => 'Scale', 'tagline' => 'Full-funnel growth engine', 'audience' => 'medium', 'price' => 59999, 'price_unit' => 'month', 'is_popular' => false, 'cta_label' => 'Talk to us',
            'features' => ['Everything in Growth', 'Full SEO (50+ keywords) & content', 'Google AND Meta ads management*', 'CRM & WhatsApp lead integration', 'Conversion rate optimisation', 'Dedicated account manager', 'Fortnightly strategy calls']],
        // Websites
        ['category' => 'websites', 'name' => 'Landing Page', 'tagline' => 'For ad campaigns', 'audience' => 'micro', 'price' => 9999, 'price_unit' => 'one-time', 'is_popular' => false, 'cta_label' => 'Get a landing page',
            'features' => ['1 high-converting page', 'Mobile-first design', 'Lead form + WhatsApp', 'Tracking setup', 'Delivery in 5 days']],
        ['category' => 'websites', 'name' => 'Business Website', 'tagline' => 'Your 24×7 salesperson', 'audience' => 'small', 'price' => 34999, 'price_unit' => 'one-time', 'is_popular' => true, 'cta_label' => 'Build my website',
            'features' => ['Up to 15 pages, custom design', 'Easy admin panel', 'On-page SEO & speed', 'WhatsApp, call & forms', 'Blog & case studies', '1 year hosting + SSL', '30 days free support']],
        ['category' => 'websites', 'name' => 'E-commerce Store', 'tagline' => 'Sell online across India', 'audience' => 'small', 'price' => 69999, 'price_unit' => 'one-time', 'is_popular' => false, 'cta_label' => 'Start selling',
            'features' => ['Custom store design', 'Up to 500 products', 'Razorpay / PhonePe payments', 'Shiprocket shipping', 'GST invoices', 'Abandoned cart recovery', 'Training for your team']],
        // Hire
        ['category' => 'hire', 'name' => 'Hourly', 'tagline' => 'Pay as you go', 'audience' => null, 'price' => 799, 'price_unit' => 'hour', 'is_popular' => false, 'cta_label' => 'Request profiles',
            'features' => ['Minimum 20 hours', 'Developers, designers, marketers', 'Weekly timesheets', 'No long-term commitment']],
        ['category' => 'hire', 'name' => 'Part-time', 'tagline' => '80 hours / month', 'audience' => null, 'price' => 45000, 'price_unit' => 'month', 'is_popular' => false, 'cta_label' => 'Request profiles',
            'features' => ['Dedicated 4 hours / day', 'Daily reports', '7-day risk-free trial', 'Free replacement']],
        ['category' => 'hire', 'name' => 'Full-time', 'tagline' => '160 hours / month', 'audience' => null, 'price' => 79999, 'price_unit' => 'month', 'is_popular' => true, 'cta_label' => 'Request profiles',
            'features' => ['100% dedicated resource', 'Works in your time zone & tools', 'Project coordinator included', '7-day risk-free trial', 'NDA & full IP ownership']],
        // CRM
        ['category' => 'crm', 'name' => 'CRM Launch', 'tagline' => 'Organise every lead', 'audience' => 'small', 'price' => 19999, 'price_unit' => 'one-time', 'is_popular' => false, 'cta_label' => 'Set up my CRM',
            'features' => ['Zoho / HubSpot / Advertally CRM', 'Pipeline & fields setup', 'Website & ads lead sync', 'Excel data import', 'Team training']],
        ['category' => 'crm', 'name' => 'Automation Pro', 'tagline' => 'Follow-ups on autopilot', 'audience' => 'small', 'price' => 39999, 'price_unit' => 'one-time', 'is_popular' => true, 'cta_label' => 'Automate my sales',
            'features' => ['Everything in CRM Launch', 'WhatsApp API automation', 'Email/SMS drip journeys', 'IndiaMART & JustDial sync', 'Sales dashboards', '30 days support']],
        ['category' => 'crm', 'name' => 'Managed Automation', 'tagline' => 'We run it for you', 'audience' => 'medium', 'price' => 14999, 'price_unit' => 'month', 'is_popular' => false, 'cta_label' => 'Talk to us',
            'features' => ['Ongoing workflow improvements', 'New automations every month', 'CRM admin support', 'Monthly performance review']],
        // Maintenance
        ['category' => 'maintenance', 'name' => 'Basic AMC', 'tagline' => 'Peace of mind', 'audience' => 'micro', 'price' => 2999, 'price_unit' => 'month', 'is_popular' => false, 'cta_label' => 'Protect my site',
            'features' => ['Weekly backups', 'Security & uptime monitoring', 'Core updates', '2 hours of changes / month']],
        ['category' => 'maintenance', 'name' => 'Business AMC', 'tagline' => 'Always up to date', 'audience' => 'small', 'price' => 6999, 'price_unit' => 'month', 'is_popular' => true, 'cta_label' => 'Choose Business AMC',
            'features' => ['Daily backups', 'Priority support (4-hour response)', 'Speed & SEO health checks', '6 hours of changes / month']],
    ],

    'plan_builder_items' => [
        ['group' => 'marketing', 'label' => 'SEO (20 keywords)', 'price' => 14999, 'unit' => 'month'],
        ['group' => 'marketing', 'label' => 'Google Ads management', 'price' => 12999, 'unit' => 'month'],
        ['group' => 'marketing', 'label' => 'Meta Ads management', 'price' => 12999, 'unit' => 'month'],
        ['group' => 'marketing', 'label' => 'Social media (20 posts)', 'price' => 9999, 'unit' => 'month'],
        ['group' => 'marketing', 'label' => 'Google Business Profile', 'price' => 4999, 'unit' => 'month'],
        ['group' => 'marketing', 'label' => 'WhatsApp marketing', 'price' => 7999, 'unit' => 'month'],
        ['group' => 'websites', 'label' => 'Business website', 'price' => 34999, 'unit' => 'one-time'],
        ['group' => 'websites', 'label' => 'E-commerce store', 'price' => 69999, 'unit' => 'one-time'],
        ['group' => 'websites', 'label' => 'Website AMC', 'price' => 2999, 'unit' => 'month'],
        ['group' => 'crm', 'label' => 'CRM setup', 'price' => 19999, 'unit' => 'one-time'],
        ['group' => 'crm', 'label' => 'WhatsApp chatbot', 'price' => 14999, 'unit' => 'one-time'],
        ['group' => 'crm', 'label' => 'Managed automation', 'price' => 14999, 'unit' => 'month'],
        ['group' => 'hire', 'label' => 'Part-time developer', 'price' => 45000, 'unit' => 'month'],
        ['group' => 'hire', 'label' => 'Full-time developer', 'price' => 79999, 'unit' => 'month'],
    ],

    'testimonials' => [
        ['name' => 'Dr. Ritu Malhotra', 'designation' => 'Founder', 'company' => 'SmileCare Dental Clinics', 'city' => 'Gurugram', 'industry' => 'healthcare', 'rating' => 5,
            'quote' => 'Within three months our Google Maps ranking went from page two to the top three. Patient enquiries on WhatsApp have nearly tripled, and the monthly report tells me exactly where each booking came from.'],
        ['name' => 'Amit Agarwal', 'designation' => 'Director', 'company' => 'Agarwal Polymers Pvt. Ltd.', 'city' => 'Ahmedabad', 'industry' => 'manufacturing', 'rating' => 5,
            'quote' => 'They rebuilt our export website, set up LinkedIn and IndiaMART campaigns and connected everything to a CRM. For the first time our sales team follows up every single lead.'],
        ['name' => 'Neha Kapoor', 'designation' => 'Co-founder', 'company' => 'Kaya Organics', 'city' => 'Delhi', 'industry' => 'd2c', 'rating' => 5,
            'quote' => 'From the Shopify store to Meta ads and WhatsApp abandoned-cart flows — one team handles it all. Our ROAS improved from 1.8x to 4.2x in five months.'],
        ['name' => 'Rajesh Iyer', 'designation' => 'Managing Partner', 'company' => 'BrightPath Academy', 'city' => 'Pune', 'industry' => 'education', 'rating' => 5,
            'quote' => 'Admissions season used to be chaos. Now every enquiry lands in the CRM, counsellors get reminders and parents get instant WhatsApp replies. We filled our batches two weeks early.'],
        ['name' => 'Sandeep Chauhan', 'designation' => 'CEO', 'company' => 'UrbanNest Realty', 'city' => 'Noida', 'industry' => 'real-estate', 'rating' => 5,
            'quote' => 'Their dedicated performance marketer works like part of our in-house team. Cost per site visit dropped by 38% and we finally have clean data on every campaign.'],
    ],

    'faqs' => [
        ['page' => 'home', 'question' => 'What kind of businesses do you work with?', 'answer' => 'We mainly work with small and medium businesses in India — manufacturers, exporters, clinics, coaching institutes, real estate firms, D2C brands and service businesses. We also offer packaged plans for smaller local businesses.'],
        ['page' => 'home', 'question' => 'Why choose one partner for marketing, websites and software?', 'answer' => 'When one team handles your marketing, website, CRM and automation, nothing falls through the cracks. Leads go straight from your ads to a fast website, into your CRM and get followed up on WhatsApp — with one point of contact and one monthly report.'],
        ['page' => 'home', 'question' => 'How soon will I see results?', 'answer' => 'Paid ads usually bring enquiries within 1–2 weeks. SEO and Google Maps rankings typically show clear movement in 60–90 days. We share targets in your growth plan before we start.'],
        ['page' => 'home', 'question' => 'Do you lock us into long contracts?', 'answer' => 'No. Marketing plans are month-to-month after an initial 3-month period, which is the minimum time needed to see meaningful results. Quarterly and yearly billing come with discounts.'],
        ['page' => 'home', 'question' => 'Will I own my website, ad accounts and data?', 'answer' => 'Yes, always. Your domain, website code, ad accounts, CRM and data belong to you. We work inside accounts in your name.'],
        ['page' => 'pricing', 'question' => 'Are prices inclusive of GST?', 'answer' => 'Prices shown are exclusive of 18% GST. You receive a proper GST invoice for every payment.'],
        ['page' => 'pricing', 'question' => 'Is ad spend included in marketing plans?', 'answer' => 'No. Plans cover our management fee. Your ad budget is paid directly to Google or Meta from your own account, so you keep full control and transparency.'],
        ['page' => 'pricing', 'question' => 'Can I switch plans later?', 'answer' => 'Yes. You can upgrade anytime and downgrade at the end of a billing cycle.'],
        ['page' => 'pricing', 'question' => 'What is the SME Bundle?', 'answer' => 'Choose marketing, a website and CRM/automation together and get an extra 10% off the monthly fees. It\'s the fastest way to build a complete lead-to-sale system.'],
        ['page' => 'hire', 'question' => 'How quickly can a resource start?', 'answer' => 'We share 2–3 matching profiles within 48 hours. After your interview, the resource can typically start within 2–5 working days.'],
        ['page' => 'hire', 'question' => 'What if I am not happy with the resource?', 'answer' => 'Every engagement starts with a 7-day risk-free trial. If it isn\'t the right fit, we replace the resource at no extra cost.'],
        ['page' => 'hire', 'question' => 'Who owns the code and work?', 'answer' => 'You do. We sign an NDA and all intellectual property created for you is transferred to you.'],
        ['page' => 'audit', 'question' => 'Is the website audit really free?', 'answer' => 'Yes. You get an instant score on screen and a detailed PDF report by email. There is no obligation to buy anything.'],
        ['page' => 'audit', 'question' => 'What does the audit check?', 'answer' => 'Security (SSL), mobile readiness, page speed, SEO basics such as titles, descriptions and headings, image alt text, sitemap and robots.txt, social sharing tags and lead-capture elements like WhatsApp and call buttons.'],
    ],

    'case_studies' => [
        ['title' => 'Dental clinic chain triples WhatsApp enquiries with local SEO', 'slug' => 'dental-clinic-local-seo', 'client' => 'SmileCare Dental Clinics', 'industry' => 'healthcare', 'city' => 'Gurugram', 'is_featured' => true,
            'services' => ['Local SEO', 'Google Business Profile', 'WhatsApp Marketing'],
            'summary' => 'Ranked 4 clinic locations in the Google Maps top-3 and turned searches into WhatsApp bookings.',
            'challenge' => 'Four clinic locations were invisible on Google Maps and relied on walk-ins and word of mouth.',
            'solution' => 'We optimised every Google Business Profile, built location landing pages, launched a review-generation system and added click-to-WhatsApp booking.',
            'results' => [['value' => '3.1x', 'label' => 'WhatsApp enquiries'], ['value' => 'Top 3', 'label' => 'Maps rank, all locations'], ['value' => '90 days', 'label' => 'To results']]],
        ['title' => 'Polymer manufacturer builds an export lead engine', 'slug' => 'manufacturer-export-leads', 'client' => 'Agarwal Polymers Pvt. Ltd.', 'industry' => 'manufacturing', 'city' => 'Ahmedabad', 'is_featured' => true,
            'services' => ['Website Redesign', 'B2B Lead Generation', 'CRM Setup'],
            'summary' => 'A new export website, LinkedIn outreach and a CRM that finally connected marketing to sales.',
            'challenge' => 'An outdated website and IndiaMART leads managed in Excel meant slow responses and lost export orders.',
            'solution' => 'We redesigned the website for international buyers, ran LinkedIn and IndiaMART campaigns and set up Zoho CRM with automatic follow-up reminders.',
            'results' => [['value' => '+212%', 'label' => 'Qualified leads'], ['value' => '11', 'label' => 'New export buyers'], ['value' => '100%', 'label' => 'Leads followed up']]],
        ['title' => 'D2C brand grows ROAS from 1.8x to 4.2x', 'slug' => 'd2c-brand-roas', 'client' => 'Kaya Organics', 'industry' => 'd2c', 'city' => 'Delhi', 'is_featured' => true,
            'services' => ['E-commerce', 'Meta Ads', 'Marketing Automation'],
            'summary' => 'Store speed fixes, creative testing and WhatsApp cart recovery more than doubled return on ad spend.',
            'challenge' => 'Rising Meta ad costs and a slow Shopify store were eating into margins.',
            'solution' => 'We rebuilt the theme for speed, introduced a structured creative-testing framework and added WhatsApp abandoned-cart and repeat-purchase flows.',
            'results' => [['value' => '4.2x', 'label' => 'ROAS (from 1.8x)'], ['value' => '-41%', 'label' => 'Cost per purchase'], ['value' => '+27%', 'label' => 'Repeat orders']]],
    ],

    'client_logos' => ['SmileCare', 'Agarwal Polymers', 'Kaya Organics', 'BrightPath', 'UrbanNest', 'Metro Diagnostics', 'Shree Textiles', 'FitZone'],
];
