<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\NavigationItem;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Header mega menu + footer columns + legal links. Rebuilt from content on each run; edit freely in the admin afterwards.
 */
class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        NavigationItem::query()->delete();

        $add = function (string $menu, string $label, ?string $url = null, ?NavigationItem $parent = null, array $extra = []) {
            static $order = 0;

            return NavigationItem::query()->create([
                'menu' => $menu, 'label' => $label, 'url' => $url, 'parent_id' => $parent?->id, 'sort_order' => $order++, ...$extra,
            ]);
        };

        // Map engine nav labels to the services shown under each group (Websites lives in Growth Technology).
        $engineLinks = [
            'ai-search' => [['SEO', '/services/seo'], ['GEO', '/services/geo'], ['AEO', '/services/aeo'], ['AI Visibility', '/services/ai-visibility'], ['Local Search', '/services/local-search'], ['Enterprise Search', '/services/enterprise-seo']],
            'demand' => [['Google Ads', '/services/google-ads'], ['LinkedIn Ads', '/services/linkedin-ads'], ['Meta Ads', '/services/meta-ads'], ['YouTube', '/services/youtube-ads'], ['Lead Generation', '/services/lead-generation'], ['ABM', '/services/abm']],
            'authority' => [['Content', '/services/content-marketing'], ['Digital PR', '/services/digital-pr'], ['Thought Leadership', '/services/thought-leadership'], ['Founder Branding', '/services/founder-branding'], ['Social Media', '/services/social-media']],
            'conversion' => [['Websites', '/growth-technology/websites'], ['Landing Pages', '/services/landing-pages'], ['CRO', '/services/cro'], ['Funnels', '/services/funnels'], ['AI Assistants', '/services/ai-assistants']],
            'automation' => [['AI Agents', '/services/ai-agents'], ['CRM', '/services/crm-automation'], ['WhatsApp', '/services/whatsapp-automation'], ['Marketing Automation', '/services/marketing-automation'], ['Lead Automation', '/services/lead-automation']],
            'intelligence' => [['Analytics', '/services/marketing-analytics'], ['Attribution', '/services/attribution'], ['Dashboards', '/growth-technology/dashboards'], ['Revenue Intelligence', '/services/revenue-intelligence']],
        ];

        // ---------- HEADER ----------
        $solutions = $add('header', 'Solutions', '/solutions', null, ['description' => 'The Advertally Growth OS: six connected engines.']);
        foreach (ServiceCategory::query()->solutions()->get() as $engine) {
            $group = $add('header', $engine->name, "/solutions/{$engine->slug}", $solutions, ['description' => $engine->tagline, 'icon' => $engine->icon]);
            foreach ($engineLinks[$engine->slug] ?? [] as [$label, $url]) {
                $add('header', $label, $url, $group);
            }
        }

        $tech = ServiceCategory::query()->where('slug', 'growth-technology')->with('services')->first();
        $techNav = $add('header', 'Growth Technology', '/growth-technology', null, ['description' => 'We build the technology required to turn marketing into measurable growth.']);
        foreach ($tech?->services ?? [] as $service) {
            $add('header', $service->title, "/growth-technology/{$service->slug}", $techNav, ['description' => $service->short_description, 'icon' => $service->icon]);
        }

        $industriesNav = $add('header', 'Industries', '/industries', null, ['description' => 'Growth systems for markets where trust decides the deal.']);
        foreach (Industry::query()->orderBy('sort_order')->get() as $industry) {
            $add('header', $industry->name, "/industries/{$industry->slug}", $industriesNav, ['icon' => $industry->icon]);
        }

        $resources = $add('header', 'Resources', '/resources', null, ['description' => 'Research, insights and free diagnostics for the AI era.']);
        foreach ([
            ['Insights', '/insights', 'Thinking on AI search, B2B growth and revenue.', 'lightbulb'],
            ['AI Search Lab', '/ai-search-lab', 'Research on how AI discovers businesses.', 'flask'],
            ['Case Studies', '/case-studies', 'Growth stories from diagnosis to results.', 'book'],
            ['Reports', '/insights?type=report', 'Reports and frameworks.', 'file-text'],
            ['Growth Score', '/growth-score', 'Benchmark your growth system in 4 minutes.', 'gauge'],
            ['AI Visibility Audit', '/ai-visibility-audit', 'Can AI find, understand and recommend you?', 'sparkles'],
        ] as [$label, $url, $desc, $icon]) {
            $add('header', $label, $url, $resources, ['description' => $desc, 'icon' => $icon]);
        }

        $company = $add('header', 'Company', null, null, ['description' => 'An AI-native growth and revenue partner.']);
        foreach ([
            ['About', '/about', 'Marketing changed. So did we.', 'building'],
            ['Approach', '/approach', 'How Advertally works.', 'compass'],
            ['Technology & Talent', '/technology-talent', 'Extend your team with specialists.', 'users'],
            ['Careers', '/careers', 'Build the next generation of growth.', 'briefcase'],
            ['Internships', '/internships', 'Learn by working on real growth projects.', 'book'],
            ['Contact', '/contact', "Let's build your next growth engine.", 'mail'],
        ] as [$label, $url, $desc, $icon]) {
            $add('header', $label, $url, $company, ['description' => $desc, 'icon' => $icon]);
        }

        // ---------- FOOTER ----------
        $col = $add('footer', 'Solutions', '/solutions');
        foreach (ServiceCategory::query()->solutions()->get() as $engine) {
            $add('footer', $engine->name, "/solutions/{$engine->slug}", $col);
        }

        $col = $add('footer', 'Growth Technology', '/growth-technology');
        foreach ($tech?->services->take(6) ?? [] as $service) {
            $add('footer', $service->title, "/growth-technology/{$service->slug}", $col);
        }

        $col = $add('footer', 'Industries', '/industries');
        foreach (Industry::query()->orderBy('sort_order')->get() as $industry) {
            $add('footer', $industry->name, "/industries/{$industry->slug}", $col);
        }

        $col = $add('footer', 'Resources', '/resources');
        foreach ([['Insights', '/insights'], ['AI Search Lab', '/ai-search-lab'], ['Case Studies', '/case-studies'], ['Growth Score', '/growth-score'], ['AI Visibility Audit', '/ai-visibility-audit']] as [$label, $url]) {
            $add('footer', $label, $url, $col);
        }

        $col = $add('footer', 'Company');
        foreach ([['About', '/about'], ['Approach', '/approach'], ['Careers', '/careers'], ['Internships', '/internships'], ['Contact', '/contact']] as [$label, $url]) {
            $add('footer', $label, $url, $col);
        }

        $col = $add('footer', 'Technology & Talent', '/technology-talent');
        foreach ([['Hire Developers', '/technology-talent/hire-developers'], ['Hire Designers', '/technology-talent/hire-designers'], ['Hire SEO Specialists', '/technology-talent/hire-seo-specialists'], ['Hire Digital Marketers', '/technology-talent/hire-digital-marketers'], ['Dedicated Teams', '/technology-talent/dedicated-teams']] as [$label, $url]) {
            $add('footer', $label, $url, $col);
        }

        // ---------- LEGAL ----------
        foreach ([['Privacy', '/privacy-policy'], ['Terms', '/terms'], ['Cookie Policy', '/cookie-policy'], ['Sitemap', '/sitemap.xml']] as [$label, $url]) {
            $add('legal', $label, $url);
        }

        NavigationItem::flushCache();
    }
}
