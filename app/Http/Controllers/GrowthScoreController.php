<?php

namespace App\Http\Controllers;

use App\Models\AuditDimension;
use App\Models\AuditRequest;
use App\Services\Seo;
use Illuminate\View\View;

class GrowthScoreController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function show(): View
    {
        $this->seo->page('Advertally Growth Score™: Measure Your Growth System',
            'A 4-minute diagnostic that scores your business across AI Visibility, Search Visibility, Authority, Demand, Conversion and Intelligence — with prioritised recommendations.')
            ->breadcrumbs(['Growth Score' => null]);

        return view('growth-score.show', [
            'dimensions' => AuditDimension::query()->ofType('growth_score')->get(),
        ]);
    }

    public function report(AuditRequest $audit): View
    {
        abort_unless($audit->type === 'growth_score' && $audit->status === 'completed', 404);

        $audit->load(['scores.dimension', 'recommendations.dimension']);

        $this->seo->page("Growth Score Report — {$audit->company}")->noindex();

        return view('growth-score.report', ['audit' => $audit]);
    }
}
