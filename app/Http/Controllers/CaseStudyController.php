<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Services\Seo;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(): View
    {
        $this->seo->page('Growth Stories: Case Studies',
            'How Advertally diagnoses growth problems and builds connected systems across search, AI, demand, conversion and automation.')
            ->breadcrumbs(['Case Studies' => null]);

        return view('case-studies.index', [
            'caseStudies' => CaseStudy::query()->published()->with('industry', 'metrics')
                ->orderByDesc('is_featured')->latest('published_at')->paginate(9),
        ]);
    }

    public function show(string $slug): View
    {
        $caseStudy = CaseStudy::query()->published()->where('slug', $slug)
            ->with(['seo', 'industry', 'metrics', 'testimonial', 'services' => fn ($q) => $q->published()->with('category')])
            ->firstOrFail();

        $this->seo->page($caseStudy->title, $caseStudy->summary, $caseStudy)
            ->image($caseStudy->featured_image)
            ->type('article')
            ->breadcrumbs(['Case Studies' => route('case-studies.index'), $caseStudy->title => null])
            ->schema([
                '@type' => 'Article',
                'headline' => $caseStudy->title,
                'description' => (string) $caseStudy->summary,
                'datePublished' => $caseStudy->published_at?->toIso8601String(),
                'dateModified' => $caseStudy->updated_at?->toIso8601String(),
                'publisher' => ['@id' => $this->seo->organizationId()],
                'author' => ['@id' => $this->seo->organizationId()],
                'mainEntityOfPage' => $caseStudy->url(),
            ]);

        return view('case-studies.show', [
            'caseStudy' => $caseStudy,
            'more' => CaseStudy::query()->published()->whereKeyNot($caseStudy->id)->latest('published_at')->take(2)->get(),
        ]);
    }
}
