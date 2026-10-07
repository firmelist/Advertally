<?php

namespace App\Support;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

/**
 * Content for the animated "orbit" hero visual: a centre tile, an orbit ring and four connected cards,
 * each with a small animated widget. Decorative only — no metrics or claims.
 *
 * Widget types: search {query} · chips {items} · check {items} · bars · flow {items} · avatars {label} · code {lines}
 */
class Orbit
{
    /** Four nodes per engine / vertical: [service slug, title, subtitle, icon, widget]. */
    private const ENGINES = [
        'ai-search' => [
            ['seo', 'SEO', 'Rank for intent', 'search', ['type' => 'search', 'query' => 'cloud partner for fintech']],
            ['geo', 'GEO', 'Cited in AI answers', 'sparkles', ['type' => 'chips', 'items' => ['ChatGPT', 'Gemini', 'Perplexity']]],
            ['aeo', 'AEO', 'Answer-ready content', 'message', ['type' => 'check', 'items' => ['FAQ schema', 'Direct answers']]],
            ['ai-visibility', 'AI Visibility', 'Monitored over time', 'eye', ['type' => 'bars']],
        ],
        'demand' => [
            ['google-ads', 'Google Ads', 'Capture intent', 'search', ['type' => 'search', 'query' => 'b2b growth partner']],
            ['linkedin-ads', 'LinkedIn Ads', 'Reach the committee', 'linkedin', ['type' => 'avatars', 'label' => '+ buying committee']],
            ['abm', 'ABM', 'Target accounts', 'radar', ['type' => 'chips', 'items' => ['Tier 1', 'Tier 2', 'Tier 3']]],
            ['lead-generation', 'Lead Generation', 'Qualified pipeline', 'target', ['type' => 'flow', 'items' => ['Lead', 'MQL', 'SQL']]],
        ],
        'authority' => [
            ['content-marketing', 'Content', 'Expert-led, not generic', 'file-text', ['type' => 'check', 'items' => ['Expert interview', 'Published']]],
            ['thought-leadership', 'Thought Leadership', 'Own a point of view', 'lightbulb', ['type' => 'chips', 'items' => ['Framework', 'Report', 'Talk']]],
            ['founder-branding', 'Founder Branding', 'Trusted voices', 'user-search', ['type' => 'avatars', 'label' => '+ leadership voice']],
            ['digital-pr', 'Digital PR', 'Earned mentions', 'megaphone', ['type' => 'bars']],
        ],
        'conversion' => [
            ['landing-pages', 'Landing Pages', 'One clear decision', 'layout', ['type' => 'check', 'items' => ['Message match', 'Proof above fold']]],
            ['cro', 'CRO', 'Tested, not guessed', 'gauge', ['type' => 'bars']],
            ['funnels', 'Funnels', 'A next step for everyone', 'merge', ['type' => 'flow', 'items' => ['Visit', 'Lead', 'Call']]],
            ['ai-assistants', 'AI Assistants', 'Helpful, 24/7', 'bot', ['type' => 'code', 'lines' => ['assistant.answer(q)', 'handed to sales']]],
        ],
        'automation' => [
            ['ai-agents', 'AI Agents', 'With human review', 'bot', ['type' => 'code', 'lines' => ['agent.qualify(lead)', 'approved by team']]],
            ['crm-automation', 'CRM', 'Clean pipeline data', 'database', ['type' => 'flow', 'items' => ['Lead', 'AI', 'CRM']]],
            ['whatsapp-automation', 'WhatsApp', 'Instant follow-up', 'message', ['type' => 'check', 'items' => ['Template approved', 'Sent instantly']]],
            ['lead-automation', 'Lead Automation', 'Routed in minutes', 'zap', ['type' => 'avatars', 'label' => '+ owner assigned']],
        ],
        'intelligence' => [
            ['marketing-analytics', 'Analytics', 'GA4 + Tag Manager', 'chart', ['type' => 'check', 'items' => ['Events tracked', 'Consent respected']]],
            ['attribution', 'Attribution', 'First & last touch', 'merge', ['type' => 'flow', 'items' => ['Search', 'AI', 'Deal']]],
            ['revenue-intelligence', 'Revenue Intelligence', 'Pipeline health', 'trending-up', ['type' => 'chips', 'items' => ['Leads', 'Pipeline', 'Revenue']]],
            ['dashboards', 'Dashboards', 'One source of truth', 'bar-chart', ['type' => 'bars']],
        ],
        'growth-technology' => [
            ['websites', 'Websites', 'Fast & search-ready', 'layout', ['type' => 'code', 'lines' => ['fn deploy(site)', 'tests passing']]],
            ['crm-integration', 'CRM Integration', 'Data in sync', 'database', ['type' => 'flow', 'items' => ['Form', 'API', 'CRM']]],
            ['ai-integration', 'AI Integration', 'Provider-agnostic', 'sparkles', ['type' => 'chips', 'items' => ['OpenAI', 'Claude', 'Gemini']]],
            ['dashboards', 'Dashboards', 'Traffic to revenue', 'bar-chart', ['type' => 'bars']],
        ],
        'technology-talent' => [
            ['hire-developers', 'Developers', 'Laravel · React · Next.js', 'code', ['type' => 'code', 'lines' => ['git push origin main', 'PR merged']]],
            ['hire-designers', 'Designers', 'Conversion-aware UI', 'layout', ['type' => 'chips', 'items' => ['Figma', 'UX', 'Brand']]],
            ['hire-seo-specialists', 'SEO Specialists', 'AI search methodology', 'search', ['type' => 'search', 'query' => 'technical seo audit']],
            ['dedicated-teams', 'Dedicated Teams', 'Scale with notice', 'users', ['type' => 'avatars', 'label' => '+ team ready']],
        ],
    ];

    /** Short captions for the shared delivery process, keyed by step title. */
    private const STEP_CAPTIONS = [
        'Diagnose' => 'Market, buyers & data',
        'Plan' => 'A prioritised roadmap',
        'Build & activate' => 'One accountable team',
        'Measure & improve' => 'Pipeline & revenue',
        'Define the outcome' => 'The metric to move',
        'Design the system' => 'Architecture & UX',
        'Build iteratively' => 'Shipped in increments',
        'Launch & optimise' => 'Measured after release',
        'Understand the need' => 'Role, skills & timeline',
        'Match specialists' => 'Shortlisted profiles',
        'Onboard' => 'Productive in week one',
        'Review & scale' => 'Regular check-ins',
    ];

    private const STEP_ICONS = ['compass', 'layers', 'rocket', 'trending-up'];

    /** Engine / vertical page: its four signature services around the engine. */
    public static function forCategory(ServiceCategory $category): array
    {
        $nodes = self::ENGINES[$category->slug] ?? [];
        $services = Service::query()->published()->with('category')->whereIn('slug', array_column($nodes, 0))->get()->keyBy('slug');

        $nodes = collect($nodes)->map(function (array $n) use ($services) {
            [$slug, $title, $subtitle, $icon, $widget] = $n;
            $service = $services->get($slug);

            return [
                'title' => $title,
                'subtitle' => $subtitle,
                'icon' => $icon,
                'widget' => $widget,
                'url' => $service?->url(),
            ];
        })->all();

        return [
            'center' => ['icon' => $category->icon ?: 'sparkles', 'label' => $category->name],
            'nodes' => $nodes ?: self::fallbackNodes($category->process ?? [], $category->slug),
        ];
    }

    /** Service page: the service at the centre, its delivery process around it. */
    public static function forService(Service $service): array
    {
        return [
            'center' => ['icon' => $service->icon ?: ($service->category?->icon ?? 'sparkles'), 'label' => $service->title],
            'nodes' => self::fallbackNodes($service->processSteps(), (string) $service->category?->slug),
        ];
    }

    private static function fallbackNodes(array $steps, string $engine): array
    {
        $widgets = array_column(self::ENGINES[$engine] ?? self::ENGINES['growth-technology'], 4);

        return collect(array_slice($steps, 0, 4))->values()->map(fn (array $step, int $i) => [
            'title' => $step['title'] ?? '',
            'subtitle' => self::STEP_CAPTIONS[$step['title'] ?? ''] ?? Str::limit((string) ($step['text'] ?? ''), 28),
            'icon' => self::STEP_ICONS[$i] ?? 'sparkles',
            'widget' => $widgets[$i] ?? ['type' => 'bars'],
            'url' => null,
        ])->all();
    }
}
