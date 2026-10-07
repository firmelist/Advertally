<?php

namespace App\Support;

use App\Models\Service;
use App\Models\ServiceCategory;

/**
 * Picks the hero animation for every engine and service page: a small, relevant "scene" that acts out what
 * the service does (resources/views/components/scenes/*). Illustrative only — no metrics, clients or claims.
 * Returns null when a page should use the orbit visual instead.
 */
class Scenes
{
    private const BRAND = 'yourcompany.com';

    public static function forCategory(ServiceCategory $category): ?array
    {
        return match ($category->slug) {
            'ai-search' => self::make('radar', 'AI search radar', ['brand' => 'Your company']),
            'demand' => self::make('revenue-flow', 'Demand engine', []),
            'authority' => self::make('trust', 'Authority signals', []),
            'conversion' => self::make('journey', 'Visitor to customer', []),
            'automation' => self::make('gears', 'Automation engine', ['tasks' => ['Lead enriched', 'Owner assigned', 'Follow-up sent', 'CRM updated', 'Report drafted', 'Meeting booked']]),
            'intelligence' => self::make('converge', 'One source of truth', []),
            'technology-talent' => self::make('match', 'Talent matching', [
                'brief' => 'Senior Laravel developer', 'needs' => ['Laravel', 'APIs', 'IST overlap'],
                'profiles' => [['Aarav', 'Frontend · React', false], ['Meera', 'Full stack · Laravel', false], ['Kabir', 'Senior Laravel · APIs', true], ['Isha', 'QA engineer', false]],
            ]),
            default => null, // growth-technology keeps the orbit
        };
    }

    public static function forService(Service $service): array
    {
        $title = $service->title;

        return match ($service->slug) {
            // ---------- AI Search ----------
            'seo' => self::make('serp', 'Search results', ['query' => 'b2b cloud consulting partner', 'domain' => self::BRAND, 'path' => 'cloud-consulting', 'title' => 'Cloud Consulting for Fintech | Your Company']),
            'geo' => self::make('ai-chat', 'AI answer', ['prompt' => 'Who are the best cloud consulting partners for fintech?', 'brand' => 'Your Company', 'domain' => self::BRAND, 'reason' => 'for regulated cloud migrations, citing their published playbooks and client reviews.']),
            'aeo' => self::make('answer', 'Answer engine', ['question' => 'How long does a cloud migration take?', 'domain' => self::BRAND]),
            'ai-visibility' => self::make('monitor', 'AI visibility monitor', ['prompt' => 'best cloud partner for fintech']),
            'entity-seo' => self::make('graph', 'Entity graph', ['brand' => 'Your Company']),
            'local-search' => self::make('map', 'Local results', ['brand' => 'Your Company', 'category' => 'Consultancy']),
            'enterprise-seo' => self::make('crawl', 'Site crawl', []),

            // ---------- Demand ----------
            'google-ads' => self::make('ppc', 'Search ad to lead', ['query' => 'b2b growth agency india', 'headline' => 'Pipeline-Focused B2B Growth | Your Company', 'description' => 'Search, LinkedIn & ABM measured on qualified pipeline.', 'sitelinks' => ['Case studies', 'Growth Score', 'Contact']]),
            'linkedin-ads' => self::make('feed', 'LinkedIn feed', ['network' => 'LinkedIn', 'icon' => 'linkedin', 'author' => 'Your Company', 'tag' => 'Promoted · for CFOs', 'post' => 'Three questions every CFO should ask before approving a marketing budget →']),
            'meta-ads' => self::make('stories', 'Stories campaign', ['brand' => 'yourcompany', 'frames' => ['Still guessing which ads work?', 'See pipeline, not clicks.', 'Get your free Growth Score'], 'cta' => 'Learn more', 'audience' => 'Founders · 30–55 · India']),
            'youtube-ads' => self::make('video', 'Video campaign', ['title' => 'How we cut CAC — a 5-minute teardown', 'brand' => 'Your Company', 'chapters' => ['Problem', 'Diagnosis', 'Fix', 'Result']]),
            'lead-generation' => self::make('funnel', 'Lead qualification', ['stages' => ['Visitors', 'Leads', 'Qualified', 'Sales-ready']]),
            'abm' => self::make('abm', 'Target accounts', []),

            // ---------- Authority ----------
            'content-marketing' => self::make('editor', 'Content studio', ['type' => 'Guide', 'title' => 'The CFO’s guide to cloud costs', 'outline' => ['The real cost drivers', 'Where budgets leak', 'A 90-day plan', 'FAQs'], 'quote' => 'Most overspend comes from idle environments']),
            'digital-pr' => self::make('press', 'Coverage', ['brand' => 'Your Company', 'publications' => ['The Business Daily', 'TechLedger', 'Industry Weekly', 'Growth Review']]),
            'thought-leadership' => self::make('slides', 'Keynote', ['framework' => 'The 4C Growth Model', 'quadrants' => ['Clarity', 'Credibility', 'Conversion', 'Compounding'], 'point_of_view' => 'Growth is a system, not a set of channels']),
            'founder-branding' => self::make('profile', 'Founder profile', ['initials' => 'YF', 'name' => 'Your Founder', 'headline' => 'Founder & CEO · Your Company', 'post' => 'We stopped reporting clicks to our board. Here is what we report instead — and why it changed every budget conversation.']),
            'social-media' => self::make('feed', 'Social feed', ['network' => 'Social', 'icon' => 'message', 'author' => 'Your Company', 'tag' => 'Carousel · LinkedIn & Instagram', 'post' => '5 signs your website is losing leads (and the fix for each) 👇']),
            'online-reputation' => self::make('reviews', 'Reviews', ['brand' => 'Your Company', 'platforms' => ['Google', 'Clutch', 'G2'], 'reviews' => [['who' => 'Head of Marketing', 'text' => 'Clear thinking and genuinely accountable.'], ['who' => 'Founder, SaaS', 'text' => 'Finally a partner that talks about pipeline.']]]),

            // ---------- Conversion ----------
            'landing-pages' => self::make('builder', 'Landing page build', ['variant' => 'landing', 'url' => self::BRAND.'/demo', 'headline' => 'See your pipeline in one dashboard', 'cta' => 'Book a demo', 'checks' => ['Message match', 'Loads fast', 'Tracking live'], 'stack' => 'Your CMS']),
            'cro' => self::make('abtest', 'A/B test', ['headline_a' => 'Marketing services for every business', 'headline_b' => 'Turn your traffic into qualified pipeline', 'cta' => 'Get my growth plan']),
            'funnels' => self::make('branches', 'Funnel logic', ['entry' => 'New visitor', 'yes' => 'Book a consultation', 'no' => 'Guide + nurture emails']),
            'ai-assistants' => self::make('chatbot', 'Website assistant', ['bot' => 'Advisor', 'question' => 'Do you work with SaaS companies?', 'answer' => 'Yes — mostly B2B SaaS. Are you focused on new pipeline or retention right now?', 'options' => ['New pipeline', 'Retention', 'Both'], 'outcome' => 'Call booked with a strategist']),

            // ---------- Automation ----------
            'ai-agents' => self::make('agent', 'Agent run', ['agent' => 'lead-research-agent', 'steps' => [['go', 'New lead: Priya, Head of Growth'], ['ok', 'Company researched · 120 staff'], ['ok', 'Fit scored against ICP'], ['ok', 'Personal follow-up drafted'], ['wait', 'Waiting for human approval']]]),
            'crm-automation' => self::make('kanban', 'CRM pipeline', ['columns' => ['New', 'Qualified', 'Proposal', 'Won'], 'deal' => 'Acme Fintech', 'source' => 'LinkedIn Ads']),
            'whatsapp-automation' => self::make('whatsapp', 'WhatsApp follow-up', ['brand' => 'Your Company', 'incoming' => 'Hi, I filled the form for a growth audit', 'reply' => 'Thanks! A strategist will call you today. Pick a time that suits you:', 'buttons' => ['Today', 'Tomorrow', 'Call me now'], 'choice' => 'Tomorrow']),
            'marketing-automation' => self::make('sequence', 'Nurture journey', ['trigger' => 'Guide download', 'yes' => ['Case study', 'Invite to call'], 'no' => ['Reminder', 'Shorter tip', 'Re-engage later']]),
            'lead-automation' => self::make('router', 'Lead routing', ['owners' => [['North team', 'Enterprise · North'], ['SaaS desk', 'Industry: SaaS'], ['Partner team', 'Referral leads']]]),

            // ---------- Intelligence ----------
            'marketing-analytics' => self::make('events', 'Event tracking', ['events' => [['page_view', false], ['scroll_75', false], ['cta_click', false], ['form_start', false], ['generate_lead', true], ['book_call', true]]]),
            'attribution' => self::make('attribution', 'Buyer journey', ['touchpoints' => [['Search', 'search'], ['AI answer', 'sparkles'], ['LinkedIn', 'linkedin'], ['Webinar', 'youtube'], ['Demo', 'users']], 'weights' => [30, 20, 25, 15, 40]]),
            'revenue-intelligence' => self::make('forecast', 'Revenue view', []),

            // ---------- Growth Technology ----------
            'websites' => self::make('builder', 'Website build', ['variant' => 'site', 'url' => self::BRAND, 'headline' => 'Growth systems for B2B leaders', 'cta' => 'Get started', 'checks' => ['Fast', 'Search & AI ready', 'Accessible', 'Tracked'], 'stack' => 'Laravel + CMS']),
            'ecommerce' => self::make('shop', 'Store & order sync', ['products' => ['Starter kit', 'Pro bundle', 'Spare parts', 'Gift set']]),
            'crm-integration' => self::make('sync', 'Two-way sync', ['left' => 'Website & ads', 'left_items' => ['Forms', 'Chat', 'Ad leads'], 'right' => 'Your CRM', 'fields' => ['Contact', 'Source', 'Campaign', 'Stage']]),
            'api-integration' => self::make('api', 'API integration', ['endpoint' => '/v1/leads', 'event' => 'lead.created', 'from' => 'Website', 'to' => 'ERP · billing']),
            'ai-integration' => self::make('models', 'AI integration', ['feature' => 'Summarise every sales call']),
            'custom-growth-tools' => self::make('calculator', 'Interactive tool', ['title' => 'Cloud savings calculator', 'inputs' => ['Monthly cloud spend', 'Number of environments', 'Team size'], 'result' => 'Estimated savings range']),
            'dashboards' => self::make('dashboard', 'Growth dashboard', ['kpis' => ['Traffic', 'Leads', 'Pipeline', 'Revenue']]),

            // ---------- Technology & Talent ----------
            'hire-developers' => self::make('code', 'Your repository', ['tabs' => ['LeadController.php', 'routes.php'], 'stack' => 'Laravel · React', 'lines' => [
                [0, '<span class="text-ai-200">public function</span> <span class="text-signal-200">store</span>(Request $r)'],
                [0, '{'],
                [1, '$lead = <span class="text-signal-200">Lead</span>::create($r-><span class="text-signal-200">validated</span>());'],
                [1, '<span class="text-signal-200">NotifySales</span>::dispatch($lead);'],
                [1, '<span class="text-ai-200">return</span> <span class="text-growth-100">redirect</span>()-><span class="text-signal-200">route</span>(<span class="text-growth-100">\'thanks\'</span>);'],
                [0, '}'],
            ]]),
            'hire-designers' => self::make('canvas', 'Design canvas', ['cta' => 'Book a demo', 'designer' => 'Designer']),
            'hire-seo-specialists' => self::make('audit', 'Technical audit', ['checks' => ['Crawl errors', 'Core Web Vitals', 'Duplicate titles', 'Schema markup', 'Internal links', 'AI crawler access']]),
            'hire-digital-marketers' => self::make('calendar', 'Campaign calendar', ['team' => 'Paid media · content · analytics specialists']),
            'dedicated-teams' => self::make('team', 'Team assembly', ['ready' => 'Team ready · one lead, clear reporting', 'roles' => [['Team Lead', 'Delivery & reporting'], ['Laravel Dev', 'Backend & APIs'], ['React Dev', 'Frontend'], ['UI Designer', 'Design system'], ['SEO Specialist', 'Technical & AI'], ['QA Engineer', 'Testing']]]),

            default => self::make('dashboard', $title, ['kpis' => ['Traffic', 'Leads', 'Pipeline', 'Revenue']]),
        };
    }

    private static function make(string $name, string $label, array $data): array
    {
        return compact('name', 'label', 'data');
    }
}
