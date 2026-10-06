<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Two ILLUSTRATIVE case studies (is_sample = true) that show the growth-story format.
 * They are labelled "Sample case study" everywhere on the site. Replace or unpublish once real stories are approved.
 */
class SampleCaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::query()->pluck('id', 'slug');

        $stories = [
            [
                'slug' => 'sample-b2b-saas-ai-search-pipeline',
                'industry' => 'saas', 'client_name' => 'Sample — B2B SaaS company',
                'title' => 'How a B2B SaaS company could connect AI search visibility to pipeline',
                'summary' => 'An illustrative growth story showing how Advertally diagnoses a SaaS business with strong product reviews but weak visibility in comparison searches and AI answers.',
                'challenge' => '<p>A growth-stage SaaS business relies on paid search for most new trials. Acquisition costs are rising, and the company rarely appears when buyers ask AI assistants for tools in its category.</p>',
                'diagnosis' => '<p>The Growth Score shows strong Conversion but weak AI Visibility and Intelligence. Comparison and alternatives pages do not exist, entity data is inconsistent across review sites, and trial sources are not captured in the CRM.</p>',
                'strategy' => '<p>Rebalance from paid capture to owned visibility: build comparison and use-case content, clarify the entity across profiles, and close the loop between product sign-ups and CRM so paid media can optimise for activated accounts.</p>',
                'execution' => '<p>Twelve comparison and use-case pages, schema and profile alignment, offline conversion import for Google Ads, and lifecycle emails triggered by activation milestones.</p>',
                'technology' => '<p>CRM integration for trial and activation events, GA4 and Tag Manager rebuild, and a growth dashboard from traffic to paid conversion.</p>',
                'results' => '<p>This section would report verified results. The chart below uses sample data to show the format.</p>',
                'business_impact' => '<p>In a real engagement this section explains the commercial impact in leadership terms — pipeline, CAC and payback — with client approval.</p>',
                'chart' => ['title' => 'Qualified pipeline index (sample data)', 'unit' => '', 'labels' => ['Q1', 'Q2', 'Q3', 'Q4'], 'values' => [100, 118, 141, 167]],
                'metrics' => [['label' => 'Qualified pipeline (sample)', 'value' => '+67%'], ['label' => 'AI Visibility Score (sample)', 'value' => '38 → 71']],
                'services' => ['geo', 'seo', 'google-ads', 'crm-integration', 'dashboards'],
            ],
            [
                'slug' => 'sample-consulting-authority-system',
                'industry' => 'consulting', 'client_name' => 'Sample — Specialist consulting firm',
                'title' => 'How a consulting firm could turn partner expertise into a growth system',
                'summary' => 'An illustrative growth story showing how Advertally helps a referral-dependent consultancy build visible authority and a predictable flow of qualified conversations.',
                'challenge' => '<p>A specialist consultancy wins most work through two partners\' networks. Growth has plateaued and new sectors are hard to enter.</p>',
                'diagnosis' => '<p>Strong Conversion on referrals, but low Authority and Search Visibility: expertise lives in proposals rather than published thinking, and sector pages are thin.</p>',
                'strategy' => '<p>Make partner expertise visible through a point-of-view programme, build sector pages structured for search and AI, and run ABM toward a defined list of target organisations.</p>',
                'execution' => '<p>Monthly partner interviews converted into articles and LinkedIn posts, five sector pages, digital PR around a signature framework, and a LinkedIn ABM programme.</p>',
                'technology' => '<p>CRM pipeline redesign with source attribution, and automated routing for inbound enquiries.</p>',
                'results' => '<p>This section would report verified results. The chart below uses sample data to show the format.</p>',
                'business_impact' => '<p>In a real engagement this section explains the commercial impact — new sectors entered, opportunity value and win rates — with client approval.</p>',
                'chart' => ['title' => 'Inbound qualified opportunities per quarter (sample data)', 'unit' => '', 'labels' => ['Q1', 'Q2', 'Q3', 'Q4'], 'values' => [4, 7, 9, 13]],
                'metrics' => [['label' => 'Inbound opportunities (sample)', 'value' => '3.2×'], ['label' => 'Authority score (sample)', 'value' => '41 → 74']],
                'services' => ['thought-leadership', 'founder-branding', 'abm', 'crm-automation'],
            ],
        ];

        foreach ($stories as $i => $s) {
            $caseStudy = CaseStudy::query()->updateOrCreate(['slug' => $s['slug']], [
                ...collect($s)->only(['client_name', 'title', 'summary', 'challenge', 'diagnosis', 'strategy', 'execution', 'technology', 'results', 'business_impact', 'chart'])->all(),
                'industry_id' => Industry::query()->where('slug', $s['industry'])->value('id'),
                'is_sample' => true,
                'is_featured' => $i === 0,
                'status' => 'published',
                'published_at' => now()->subDays(10 + $i),
            ]);

            $caseStudy->metrics()->delete();
            foreach ($s['metrics'] as $m => $metric) {
                $caseStudy->metrics()->create([...$metric, 'sort_order' => $m]);
            }
            $caseStudy->services()->sync($services->only($s['services'])->values());
        }
    }
}
