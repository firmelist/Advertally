<?php

namespace Database\Seeders;

use App\Models\AiResearch;
use App\Models\Author;
use App\Models\Industry;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

/**
 * Seeds the Growth OS engines, verticals, services, industries, insights and AI Search Lab research
 * from database/data/*.php. Safe to re-run (updateOrCreate by slug).
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $engines = [
            ...array_map(fn ($e) => [...$e, 'group' => 'solution'], require database_path('data/solutions.php')),
            ...array_map(fn ($e) => [...$e, 'group' => 'solution'], require database_path('data/solutions_more.php')),
            ...require database_path('data/verticals.php'),
        ];

        foreach ($engines as $order => $engine) {
            $category = ServiceCategory::query()->updateOrCreate(['slug' => $engine['slug']], [
                ...Arr::only($engine, ['group', 'name', 'number', 'tagline', 'headline', 'subheadline', 'summary', 'intro', 'icon',
                    'flow_title', 'flow', 'metrics_title', 'metrics', 'capabilities', 'highlights', 'process', 'faqs', 'principle', 'cta_label', 'cta_url']),
                'status' => 'published',
                'sort_order' => $order,
            ]);

            foreach ($engine['services'] ?? [] as $i => $s) {
                Service::query()->updateOrCreate(['slug' => $s['slug']], [
                    'service_category_id' => $category->id,
                    'title' => $s['title'],
                    'icon' => $s['icon'] ?? null,
                    'hero_title' => $s['hero_title'] ?? null,
                    'short_description' => $s['short'],
                    'long_description' => $s['body'] ?? null,
                    'benefits' => $s['benefits'] ?? [],
                    'deliverables' => $s['deliverables'] ?? [],
                    'faqs' => $s['faqs'] ?? [],
                    'is_featured' => $i < 2,
                    'status' => 'published',
                    'sort_order' => $i,
                ]);
            }
        }

        // Related services: siblings in the same engine plus a curated cross-engine link set.
        $crossLinks = [
            'seo' => ['geo', 'content-marketing', 'marketing-analytics'],
            'geo' => ['ai-visibility', 'entity-seo', 'digital-pr'],
            'aeo' => ['geo', 'content-marketing', 'ai-assistants'],
            'ai-visibility' => ['geo', 'entity-seo', 'thought-leadership'],
            'google-ads' => ['landing-pages', 'attribution', 'lead-automation'],
            'linkedin-ads' => ['abm', 'thought-leadership', 'founder-branding'],
            'lead-generation' => ['landing-pages', 'lead-automation', 'crm-automation'],
            'abm' => ['linkedin-ads', 'content-marketing', 'revenue-intelligence'],
            'content-marketing' => ['seo', 'aeo', 'thought-leadership'],
            'cro' => ['landing-pages', 'marketing-analytics', 'funnels'],
            'landing-pages' => ['cro', 'google-ads', 'websites'],
            'ai-assistants' => ['ai-agents', 'ai-integration', 'lead-automation'],
            'ai-agents' => ['ai-integration', 'crm-automation', 'lead-automation'],
            'crm-automation' => ['crm-integration', 'lead-automation', 'revenue-intelligence'],
            'attribution' => ['marketing-analytics', 'dashboards', 'crm-integration'],
            'websites' => ['cro', 'seo', 'landing-pages'],
            'ai-integration' => ['ai-agents', 'ai-assistants', 'api-integration'],
            'dashboards' => ['revenue-intelligence', 'attribution', 'marketing-analytics'],
        ];
        $ids = Service::query()->pluck('id', 'slug');
        foreach ($crossLinks as $slug => $related) {
            if (isset($ids[$slug])) {
                Service::query()->find($ids[$slug])->related()->sync($ids->only($related)->values());
            }
        }

        foreach (require database_path('data/industries.php') as $i => $data) {
            $industry = Industry::query()->updateOrCreate(['slug' => $data['slug']], [
                ...Arr::only($data, ['name', 'icon', 'headline', 'summary', 'how_customers_search', 'ai_discovery', 'challenges', 'opportunities', 'growth_system', 'faqs']),
                'status' => 'published',
                'sort_order' => $i,
            ]);
            $industry->services()->sync($ids->only($data['services'])->values());
        }

        $insights = require database_path('data/insights.php');

        foreach ($insights['categories'] as $i => $c) {
            PostCategory::query()->updateOrCreate(['slug' => $c['slug']], [...$c, 'sort_order' => $i]);
        }
        foreach ($insights['authors'] as $a) {
            Author::query()->updateOrCreate(['slug' => $a['slug']], [...$a, 'is_active' => true]);
        }

        $industryIds = Industry::query()->pluck('id', 'slug');
        foreach ($insights['posts'] as $p) {
            $post = Post::query()->updateOrCreate(['slug' => $p['slug']], [
                'title' => $p['title'],
                'excerpt' => $p['excerpt'],
                'content' => $p['content'],
                'type' => $p['type'],
                'tags' => $p['tags'],
                'is_featured' => $p['featured'],
                'post_category_id' => PostCategory::query()->where('slug', $p['category'])->value('id'),
                'author_id' => Author::query()->where('slug', $p['author'])->value('id'),
                'status' => 'published',
                'published_at' => now()->subDays($p['days_ago'])->setTime(9, 0),
            ]);
            $post->services()->sync($ids->only($p['services'])->values());
            $post->industries()->sync($industryIds->only($p['industries'])->values());
        }

        foreach ($insights['research'] as $r) {
            $research = AiResearch::query()->updateOrCreate(['slug' => $r['slug']], [
                ...Arr::only($r, ['title', 'category', 'summary', 'key_findings', 'body', 'methodology', 'sources']),
                'data' => $r['data'] ?? null,
                'charts' => $r['charts'] ?? null,
                'is_featured' => $r['featured'],
                'author_id' => Author::query()->where('slug', $r['author'])->value('id'),
                'status' => 'published',
                'published_at' => now()->subDays($r['days_ago'])->setTime(9, 0),
            ]);
            $research->services()->sync($ids->only($r['services'])->values());
        }
    }
}
