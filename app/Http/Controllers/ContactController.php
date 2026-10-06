<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Page;
use App\Services\LeadService;
use App\Services\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function show(): View
    {
        $page = Page::query()->where('slug', 'contact')->with('seo')->first();

        $this->seo->page("Let's Build Your Next Growth Engine",
            'Request a growth strategy from Advertally. Tell us about your business, current challenge and objectives — a senior strategist responds within one business day.', $page)
            ->breadcrumbs(['Contact' => null]);

        return view('pages.contact', ['page' => $page]);
    }

    public function store(ContactRequest $request, LeadService $leads): RedirectResponse
    {
        $data = $request->validated();

        if ($data['form'] === 'talent') {
            $data['service_interest'] = 'talent';
            $data['message'] = trim('Role needed: '.config('advertally.talent_roles.'.$data['talent_role'])."\n".($data['message'] ?? ''));
        }

        $lead = $leads->capture($data, $data['form'], $request);

        return redirect()->route('thank-you')->with('lead_form', $lead->form_type);
    }

    public function thankYou(): View|RedirectResponse
    {
        if (! session()->has('lead_form')) {
            return redirect()->route('home');
        }

        $this->seo->page('Thank you')->noindex();

        return view('pages.thank-you', ['form' => session('lead_form')]);
    }
}
