<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = collect([
                ['loc' => route('home'), 'priority' => '1.0'],
                ['loc' => route('pricing'), 'priority' => '0.9'],
                ['loc' => route('audit.create'), 'priority' => '0.8'],
                ['loc' => route('about'), 'priority' => '0.6'],
                ['loc' => route('contact'), 'priority' => '0.6'],
                ['loc' => route('case-studies.index'), 'priority' => '0.7'],
            ]);

            Service::active()->with('parent')->get()->each(function (Service $s) use ($urls) {
                if ($s->parent_id && ! $s->parent?->is_active) {
                    return;
                }
                $urls->push(['loc' => $s->url, 'priority' => $s->isHub() ? '0.9' : '0.8', 'lastmod' => $s->updated_at?->toAtomString()]);
            });

            CaseStudy::published()->get()->each(fn ($cs) => $urls->push([
                'loc' => route('case-studies.show', $cs), 'priority' => '0.6', 'lastmod' => $cs->updated_at?->toAtomString(),
            ]));

            return view('seo.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = app()->environment('production')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /free-website-audit/report/', 'Disallow: /thank-you', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }
}
