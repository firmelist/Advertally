<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Services\Seo;
use Illuminate\View\View;

class IndustryController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(): View
    {
        $this->seo->page('Industries: Growth Systems for B2B Markets',
            'How Advertally builds AI-ready growth systems for technology, SaaS, consulting, professional services, financial services, recruitment and specialised B2B companies.')
            ->breadcrumbs(['Industries' => null]);

        return view('industries.index', [
            'industries' => Industry::query()->published()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $industry = Industry::query()->published()->where('slug', $slug)
            ->with([
                'seo',
                'services' => fn ($q) => $q->published()->with('category'),
                'caseStudies' => fn ($q) => $q->published(),
                'posts' => fn ($q) => $q->published()->latest('published_at'),
            ])
            ->firstOrFail();

        $this->seo->page($industry->headline ?: "Growth for {$industry->name}", $industry->summary, $industry)
            ->breadcrumbs(['Industries' => route('industries.index'), $industry->name => null])
            ->faq($industry->faqs ?? []);

        return view('industries.show', [
            'industry' => $industry,
            'others' => Industry::query()->published()->whereKeyNot($industry->id)->orderBy('sort_order')->get(),
        ]);
    }
}
