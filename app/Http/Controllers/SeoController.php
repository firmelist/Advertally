<?php

namespace App\Http\Controllers;

use App\Models\AiResearch;
use App\Models\CaseStudy;
use App\Models\Internship;
use App\Models\Industry;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $xml = Cache::remember('seo.sitemap', 3600, function () {
            $urls = collect([
                ['loc' => route('home'), 'priority' => '1.0'],
                ['loc' => route('solutions.index'), 'priority' => '0.9'],
                ['loc' => route('growth-score'), 'priority' => '0.9'],
                ['loc' => route('ai-audit'), 'priority' => '0.9'],
                ['loc' => route('industries.index'), 'priority' => '0.7'],
                ['loc' => route('case-studies.index'), 'priority' => '0.7'],
                ['loc' => route('insights.index'), 'priority' => '0.7'],
                ['loc' => route('lab.index'), 'priority' => '0.8'],
                ['loc' => route('resources'), 'priority' => '0.5'],
                ['loc' => route('contact'), 'priority' => '0.6'],
            ]);

            Page::query()->published()->where('slug', '!=', 'home')->where('slug', '!=', 'contact')->get()
                ->each(fn ($p) => $urls->push(['loc' => $p->url(), 'lastmod' => $p->updated_at, 'priority' => $p->template === 'legal' ? '0.2' : '0.6']));
            ServiceCategory::query()->published()->get()
                ->each(fn ($c) => $urls->push(['loc' => $c->url(), 'lastmod' => $c->updated_at, 'priority' => '0.9']));
            Service::query()->published()->whereHas('category', fn ($q) => $q->published())->with('category')->get()
                ->each(fn ($s) => $urls->push(['loc' => $s->url(), 'lastmod' => $s->updated_at, 'priority' => '0.8']));
            Industry::query()->published()->get()
                ->each(fn ($i) => $urls->push(['loc' => $i->url(), 'lastmod' => $i->updated_at, 'priority' => '0.7']));
            CaseStudy::query()->published()->get()
                ->each(fn ($c) => $urls->push(['loc' => $c->url(), 'lastmod' => $c->updated_at, 'priority' => '0.6']));
            PostCategory::query()->whereHas('posts', fn ($q) => $q->published())->get()
                ->each(fn ($c) => $urls->push(['loc' => $c->url(), 'priority' => '0.4']));
            Post::query()->published()->get()
                ->each(fn ($p) => $urls->push(['loc' => $p->url(), 'lastmod' => $p->updated_at, 'priority' => '0.6']));
            $urls->push(['loc' => route('internships.index'), 'priority' => '0.5']);
            Internship::query()->published()->get()
                ->each(fn ($i) => $urls->push(['loc' => $i->url(), 'lastmod' => $i->updated_at, 'priority' => '0.5']));
            AiResearch::query()->published()->get()
                ->each(fn ($r) => $urls->push(['loc' => $r->url(), 'lastmod' => $r->updated_at, 'priority' => '0.7']));

            return view('seo.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = app()->environment('production')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /livewire', 'Disallow: /thank-you', 'Disallow: /growth-score/report/', 'Disallow: /ai-visibility-audit/report/', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /** llms.txt — a plain-language map of the site for AI assistants (https://llmstxt.org). */
    public function llms(): Response
    {
        $text = Cache::remember('seo.llms', 3600, function () {
            $engines = ServiceCategory::query()->published()->orderBy('group')->orderBy('sort_order')
                ->with(['services' => fn ($q) => $q->published()])->get();

            $out = ['# Advertally', '', '> '.setting('company_description',
                'Advertally is an AI-native growth and revenue partner. It helps ambitious B2B businesses become discoverable, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.'), ''];

            $out[] = 'Advertally connects six growth engines — AI Search, Demand, Authority, Conversion, Automation and Intelligence — into one measurable growth system, powered by Growth Technology. Technology & Talent is a separate vertical for extending teams with specialists.';
            $out[] = '';

            foreach ($engines as $engine) {
                $out[] = "## {$engine->name}".($engine->tagline ? " — {$engine->tagline}" : '');
                $out[] = "- [{$engine->name}]({$engine->url()}): ".str($engine->summary ?: $engine->subheadline)->squish();
                foreach ($engine->services as $service) {
                    $out[] = "- [{$service->title}]({$service->url()}): ".str($service->short_description)->squish();
                }
                $out[] = '';
            }

            $out[] = '## Industries';
            foreach (Industry::query()->published()->orderBy('sort_order')->get() as $industry) {
                $out[] = "- [{$industry->name}]({$industry->url()}): ".str($industry->summary)->squish();
            }

            $out[] = '';
            $out[] = '## Tools & research';
            $out[] = '- [Advertally Growth Score]('.route('growth-score').'): Self-assessment across AI Visibility, Search Visibility, Authority, Demand, Conversion and Intelligence.';
            $out[] = '- [AI Visibility Audit]('.route('ai-audit').'): Scores how ready a website is to be crawled, understood and cited by AI assistants.';
            $out[] = '- [AI Search Lab]('.route('lab.index').'): Research and frameworks on AI-driven discovery.';
            $out[] = '- [Insights]('.route('insights.index').'): Articles on AI search, B2B growth, conversion, automation and analytics.';
            $out[] = '';
            $out[] = '## Company';
            $out[] = '- [About]('.url('about').')';
            $out[] = '- [Approach]('.url('approach').')';
            $out[] = '- [Internships]('.route('internships.index').'): Hands-on internships in digital marketing, development and AI.';
            foreach (Internship::query()->published()->orderBy('sort_order')->get() as $internship) {
                $out[] = "  - [{$internship->title}]({$internship->url()}): ".str($internship->summary)->squish();
            }
            $out[] = '- [Contact]('.route('contact').')';

            return implode("\n", $out)."\n";
        });

        return response($text, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
