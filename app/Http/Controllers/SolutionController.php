<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\Seo;
use Illuminate\View\View;

/**
 * Growth OS engines, their services, and the two secondary verticals (Growth Technology, Technology & Talent).
 */
class SolutionController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(): View
    {
        $this->seo->page('Growth OS: One Growth System, Six Engines',
            'AI Search, Demand, Authority, Conversion, Automation and Intelligence — the six connected engines Advertally uses to turn visibility into revenue.')
            ->breadcrumbs(['Solutions' => null]);

        return view('solutions.index', [
            'engines' => ServiceCategory::query()->published()->solutions()->with(['services' => fn ($q) => $q->published()])->get(),
        ]);
    }

    public function show(string $slug): View
    {
        return $this->category('solution', $slug);
    }

    public function growthTechnology(): View
    {
        return $this->category('technology', 'growth-technology');
    }

    public function talent(): View
    {
        return $this->category('talent', 'technology-talent');
    }

    public function service(string $slug): View
    {
        return $this->serviceIn('solution', $slug);
    }

    public function growthTechnologyService(string $slug): View
    {
        return $this->serviceIn('technology', $slug);
    }

    public function talentService(string $slug): View
    {
        return $this->serviceIn('talent', $slug);
    }

    private function category(string $group, string $slug): View
    {
        $category = ServiceCategory::query()->published()->where('group', $group)->where('slug', $slug)
            ->with(['seo', 'services' => fn ($q) => $q->published()])
            ->firstOrFail();

        $trail = $group === 'solution'
            ? ['Solutions' => route('solutions.index'), $category->name => null]
            : [$category->name => null];

        $this->seo->page($category->headline ?: $category->name, $category->subheadline ?: $category->summary, $category)
            ->breadcrumbs($trail)
            ->service($category->name, (string) ($category->summary ?: $category->subheadline), $category->url())
            ->faq($category->faqs ?? []);

        $serviceIds = $category->services->pluck('id');

        return view('solutions.show', [
            'category' => $category,
            'engines' => $group === 'solution' ? ServiceCategory::query()->published()->solutions()->get() : collect(),
            'caseStudies' => CaseStudy::query()->published()->whereHas('services', fn ($q) => $q->whereIn('services.id', $serviceIds))->take(2)->get(),
            'posts' => Post::query()->published()->whereHas('services', fn ($q) => $q->whereIn('services.id', $serviceIds))->latest('published_at')->take(3)->get(),
        ]);
    }

    private function serviceIn(string $group, string $slug): View
    {
        $service = Service::query()->published()->where('slug', $slug)
            ->whereHas('category', fn ($q) => $q->where('group', $group)->published())
            ->with([
                'seo', 'category',
                'related' => fn ($q) => $q->published()->with('category'),
                'industries' => fn ($q) => $q->published(),
                'caseStudies' => fn ($q) => $q->published(),
                'posts' => fn ($q) => $q->published()->latest('published_at'),
                'research' => fn ($q) => $q->published()->latest('published_at'),
            ])
            ->firstOrFail();

        $category = $service->category;
        $trail = match ($group) {
            'solution' => ['Solutions' => route('solutions.index'), $category->name => $category->url(), $service->title => null],
            default => [$category->name => $category->url(), $service->title => null],
        };

        $this->seo->page($service->hero_title ?: $service->title, $service->short_description, $service)
            ->breadcrumbs($trail)
            ->service($service->title, (string) $service->short_description, $service->url(), $category->name)
            ->faq($service->faqs ?? []);

        $siblings = $category->services()->published()->whereKeyNot($service->id)->get();

        return view('solutions.service', compact('service', 'category', 'siblings'));
    }
}
