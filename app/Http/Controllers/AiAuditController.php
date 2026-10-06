<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiAuditRequest;
use App\Models\AuditDimension;
use App\Models\AuditRequest;
use App\Services\Audit\AuditService;
use App\Services\Audit\SafeFetcher;
use App\Services\LeadService;
use App\Services\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

class AiAuditController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function create(): View
    {
        $this->seo->page('AI Visibility Audit: Can AI Find, Understand and Recommend You?',
            'Run a free Advertally AI Visibility Audit. Score your AI visibility, search visibility, authority, entity strength, content coverage and conversion readiness.')
            ->breadcrumbs(['Resources' => route('resources'), 'AI Visibility Audit' => null]);

        return view('audit.create', [
            'dimensions' => AuditDimension::query()->ofType('ai_visibility')->get(),
        ]);
    }

    public function store(AiAuditRequest $request, LeadService $leads, AuditService $audits, SafeFetcher $fetcher): RedirectResponse
    {
        $data = $request->validated();

        try {
            $data['website'] = $fetcher->normalise($data['website']);
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['website' => $e->getMessage()]);
        }

        $audit = DB::transaction(function () use ($data, $leads, $request) {
            $lead = $leads->capture([
                ...$data,
                'service_interest' => 'ai-search',
                'message' => "AI Visibility Audit for {$data['website']} ({$data['country']}). Primary service: {$data['primary_service']}."
                    .(! empty($data['competitor']) ? " Competitor: {$data['competitor']}." : ''),
            ], 'ai_audit', $request);

            return AuditRequest::query()->create([
                ...collect($data)->only(['website', 'company', 'name', 'email', 'industry', 'country', 'primary_service', 'competitor'])->all(),
                'type' => 'ai_visibility',
                'lead_id' => $lead->id,
                'ip_address' => $request->ip(),
            ]);
        });

        $audits->runAiVisibility($audit);

        return redirect()->to($audit->url());
    }

    public function report(AuditRequest $audit): View
    {
        abort_unless($audit->type === 'ai_visibility', 404);

        $audit->load(['scores.dimension', 'recommendations.dimension']);

        $this->seo->page("AI Visibility Report — {$audit->company}")->noindex();

        return view('audit.report', ['audit' => $audit]);
    }
}
