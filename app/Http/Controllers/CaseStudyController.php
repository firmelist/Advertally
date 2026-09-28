<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function index(Request $request): View
    {
        $industry = $request->string('industry')->toString() ?: null;

        return view('pages.case-studies.index', [
            'caseStudies' => CaseStudy::published()
                ->when($industry, fn ($q) => $q->where('industry', $industry))
                ->get(),
            'industries' => CaseStudy::published()->pluck('industry')->unique()->filter()->values(),
            'active' => $industry,
        ]);
    }

    public function show(CaseStudy $caseStudy): View
    {
        abort_unless($caseStudy->is_published, 404);

        return view('pages.case-studies.show', [
            'cs' => $caseStudy,
            'more' => CaseStudy::published()->where('id', '!=', $caseStudy->id)->take(2)->get(),
        ]);
    }
}
