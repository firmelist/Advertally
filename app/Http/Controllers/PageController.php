<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\ClientLogo;
use App\Models\Faq;
use App\Models\PlanBuilderItem;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $data = [
            'hubs' => Service::hubs()->active()->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])->orderBy('sort_order')->get(),
            'caseStudies' => CaseStudy::published()->where('is_featured', true)->take(3)->get(),
            'testimonials' => Testimonial::active()->take(6)->get(),
            'plans' => PricingPlan::active()->where('category', 'marketing')->take(3)->get(),
            'faqs' => Faq::forPage('home')->get(),
            'logos' => ClientLogo::where('is_active', true)->orderBy('sort_order')->get(),
        ];

        return view('pages.home', $data);
    }

    public function about(): View
    {
        return view('pages.about', [
            'testimonials' => Testimonial::active()->take(3)->get(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', ['faqs' => Faq::forPage('contact')->get()]);
    }

    public function consultation(): View
    {
        return view('pages.consultation');
    }

    public function pricing(): View
    {
        return view('pages.pricing', [
            'plans' => PricingPlan::active()->get()->groupBy('category'),
            'builderItems' => PlanBuilderItem::where('is_active', true)->orderBy('sort_order')->get()
                ->map(fn ($i) => ['key' => 'i'.$i->id, 'label' => $i->label, 'group' => $i->group, 'price' => $i->price, 'unit' => $i->unit])
                ->values(),
            'faqs' => Faq::forPage('pricing')->get(),
        ]);
    }

    public function thankYou(): View
    {
        return view('pages.thank-you', ['lead' => session('lead_name')]);
    }

    public function legal(string $page): View
    {
        abort_unless(in_array($page, ['privacy', 'terms', 'refund'], true), 404);

        return view("pages.legal.{$page}");
    }
}
