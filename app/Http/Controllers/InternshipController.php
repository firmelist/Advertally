<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternshipApplicationRequest;
use App\Jobs\NotifyInternshipApplication;
use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Models\Statistic;
use App\Services\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InternshipController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(): View
    {
        $this->seo->page('Internships at Advertally',
            'Paid and project-based internships in digital marketing, development, design and AI. Work alongside the Advertally team on real growth problems.')
            ->breadcrumbs(['Careers' => url('careers'), 'Internships' => null]);

        return view('internships.index', [
            'internships' => Internship::query()->published()->orderByDesc('is_featured')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $internship = Internship::query()->published()->where('slug', $slug)->with('seo')->firstOrFail();

        // Only brands/projects cleared for public display are loaded — confidential clients never reach the view.
        $brands = $internship->show_brands ? $internship->publicBrands()->get() : collect();
        $projects = $internship->projectLinks()
            ->whereHas('project', fn ($q) => $q->displayable())
            ->with(['project.brand'])
            ->get();
        $stats = $internship->show_statistics
            ? Statistic::query()->where('context', 'internships')->where('is_visible', true)->orderBy('sort_order')->get()
            : collect();

        $this->seo->page("{$internship->title} Internship", $internship->summary, $internship)
            ->breadcrumbs(['Careers' => url('careers'), 'Internships' => route('internships.index'), $internship->title => null])
            ->faq($internship->faqs ?? [])
            ->schema($this->jobPosting($internship));

        return view('internships.show', compact('internship', 'brands', 'projects', 'stats'));
    }

    public function apply(InternshipApplicationRequest $request, string $slug): RedirectResponse
    {
        $internship = Internship::query()->published()->where('slug', $slug)->firstOrFail();
        abort_unless($internship->isOpen(), 410, 'Applications for this internship are closed.');

        $data = $request->validated();
        $file = $request->file('resume');
        $path = $file->storeAs('applications/'.now()->format('Y/m'), Str::uuid().'.'.$file->extension(), 'local');

        $application = InternshipApplication::query()->create([
            ...collect($data)->except(['resume', 'consent'])->all(),
            'internship_id' => $internship->id,
            'resume_path' => $path,
            'resume_name' => mb_substr($file->getClientOriginalName(), 0, 190),
            'ip_address' => $request->ip(),
        ]);

        NotifyInternshipApplication::dispatch($application);

        return redirect()->to($internship->url().'#apply')->with('application_submitted', $application->name);
    }

    /** JobPosting structured data. Never includes client brands. */
    private function jobPosting(Internship $internship): array
    {
        return array_filter([
            '@type' => 'JobPosting',
            'title' => "{$internship->title} Internship",
            'description' => strip_tags((string) ($internship->summary ?: $internship->headline)),
            'datePosted' => $internship->created_at?->toDateString(),
            'validThrough' => $internship->apply_by?->endOfDay()->toIso8601String(),
            'employmentType' => 'INTERN',
            'hiringOrganization' => ['@id' => $this->seo->organizationId()],
            'jobLocationType' => $internship->work_mode === 'remote' ? 'TELECOMMUTE' : null,
            'jobLocation' => $internship->work_mode !== 'remote' && $internship->location
                ? ['@type' => 'Place', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $internship->location, 'addressCountry' => setting('country', 'IN')]]
                : null,
            'applicantLocationRequirements' => $internship->work_mode === 'remote' ? ['@type' => 'Country', 'name' => 'India'] : null,
            'url' => $internship->url(),
        ]);
    }
}
