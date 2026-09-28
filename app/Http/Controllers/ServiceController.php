<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    /** Map ladder pillars to pricing-plan categories shown on the page. */
    private const PLAN_CATEGORY = [
        'grow' => 'marketing',
        'build' => 'websites',
        'scale' => 'hire',
        'automate' => 'crm',
        'transform' => null,
    ];

    public function hub(string $hub): View
    {
        $service = Service::hubs()->active()->where('slug', $hub)
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->firstOrFail();

        return view('services.hub', [
            'service' => $service,
            'plans' => $this->plansFor($service),
            'caseStudy' => $this->caseStudyFor($service),
            'testimonial' => Testimonial::active()->inRandomOrder()->first(),
            'faqs' => $service->pillar === 'scale' ? Faq::forPage('hire')->get() : $this->childFaqs($service),
            'next' => $service->nextHub(),
        ]);
    }

    public function show(string $hub, string $slug): View
    {
        $parent = Service::hubs()->active()->where('slug', $hub)->firstOrFail();
        $service = $parent->children()->active()->where('slug', $slug)->with('faqs')->firstOrFail();
        $service->setRelation('parent', $parent);

        return view('services.show', [
            'service' => $service,
            'parent' => $parent,
            'siblings' => $parent->children()->active()->where('id', '!=', $service->id)->orderBy('sort_order')->get(),
            'caseStudy' => $this->caseStudyFor($parent),
            'testimonial' => Testimonial::active()->inRandomOrder()->first(),
            'next' => $parent->nextHub(),
        ]);
    }

    private function plansFor(Service $service)
    {
        $category = self::PLAN_CATEGORY[$service->pillar] ?? null;

        return $category ? PricingPlan::active()->where('category', $category)->take(3)->get() : collect();
    }

    /** Keywords used to pick a relevant case study for each pillar. */
    private const PILLAR_KEYWORDS = [
        'grow' => ['seo', 'ads', 'marketing', 'google', 'social', 'whatsapp', 'lead'],
        'build' => ['website', 'e-commerce', 'redesign', 'landing'],
        'scale' => ['hire', 'dedicated', 'resource'],
        'automate' => ['crm', 'automation', 'chatbot'],
        'transform' => ['software', 'erp', 'app', 'integration'],
    ];

    private function caseStudyFor(Service $service): ?CaseStudy
    {
        $keywords = self::PILLAR_KEYWORDS[$service->pillar] ?? [];
        $all = CaseStudy::published()->get();

        return $all->first(fn (CaseStudy $cs) => collect($cs->services)->contains(
            fn ($s) => collect($keywords)->contains(fn ($k) => str_contains(strtolower($s), $k))
        )) ?? $all->first();
    }

    private function childFaqs(Service $service)
    {
        return Faq::query()->whereIn('service_id', $service->children->pluck('id'))
            ->where('is_active', true)
            ->get()
            ->unique('question')
            ->take(5)
            ->values();
    }
}
