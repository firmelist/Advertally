<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Mail\AuditReportReady;
use App\Models\AuditReport;
use App\Models\Faq;
use App\Services\LeadService;
use App\Services\WebsiteAuditor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Throwable;

class AuditController extends Controller
{
    public function create(): View
    {
        return view('pages.audit.create', ['faqs' => Faq::forPage('audit')->get()]);
    }

    /**
     * Runs the audit, captures the visitor as a lead (form_type=audit) and emails the PDF.
     * Uses StoreLeadRequest so the same spam protection & validation apply.
     */
    public function store(StoreLeadRequest $request, WebsiteAuditor $auditor, LeadService $leads): RedirectResponse
    {
        $request->validate(['website' => ['required', 'string', 'max:255']]);

        try {
            $url = $auditor->normalise($request->string('website')->toString());
            $result = $auditor->run($url);
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['website' => $e->getMessage()]);
        } catch (Throwable $e) {
            Log::warning('Audit failed', ['url' => $request->input('website'), 'error' => $e->getMessage()]);

            return back()->withInput()->withErrors(['website' => 'We could not analyse that website right now. Please try again in a minute.']);
        }

        $lead = $leads->capture([...$request->validated(), 'website' => $url, 'form_type' => 'audit'], $request);

        $report = AuditReport::create([
            'lead_id' => $lead->id,
            'url' => $url,
            'score' => $result['score'],
            'performance_score' => $result['performance_score'],
            'checks' => $result['checks'],
        ]);

        if ($lead->email) {
            Mail::to($lead->email, $lead->name)->queue(new AuditReportReady($report));
        }

        return redirect()->route('audit.show', $report)->with('lead_tracked', true);
    }

    public function show(AuditReport $report): View
    {
        return view('pages.audit.show', ['report' => $report]);
    }
}
