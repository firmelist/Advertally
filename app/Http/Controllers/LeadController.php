<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request, LeadService $leads): RedirectResponse|JsonResponse
    {
        $lead = $leads->capture($request->validated(), $request);

        session()->flash('lead_name', $lead->name);
        session()->flash('lead_form', $lead->form_type);
        session()->flash('lead_tracked', true);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'redirect' => route('thank-you')]);
        }

        return redirect()->route('thank-you');
    }
}
