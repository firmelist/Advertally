<?php

namespace App\Livewire;

use App\Models\AuditDimension;
use App\Models\AuditRequest;
use App\Services\Audit\AuditService;
use App\Services\LeadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Advertally Growth Score™ — one step per dimension, then a short contact step, then the report.
 */
class GrowthScoreWizard extends Component
{
    /** @var array<int, array{key:string, name:string, description:?string, questions:array}> */
    #[Locked]
    public array $steps = [];

    #[Locked]
    public string $sourcePage = '';

    public int $step = 0;

    /** @var array<int|string, int|string> question id => option index */
    public array $answers = [];

    public string $name = '';

    public string $email = '';

    public string $company = '';

    public string $website = '';

    public string $industry = '';

    public bool $consent = false;

    public string $hp_trap = ''; // honeypot

    public function mount(): void
    {
        $this->sourcePage = request()->fullUrl();

        // Option points stay on the server; the browser only sees labels.
        $this->steps = AuditDimension::query()->ofType('growth_score')
            ->with(['questions' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->filter(fn ($d) => $d->questions->isNotEmpty())
            ->map(fn ($d) => [
                'key' => $d->key,
                'name' => $d->name,
                'description' => $d->description,
                'questions' => $d->questions->map(fn ($q) => [
                    'id' => $q->id,
                    'question' => $q->question,
                    'help' => $q->help_text,
                    'options' => array_column($q->options ?? [], 'label'),
                ])->all(),
            ])
            ->values()
            ->all();
    }

    public function next(): void
    {
        if ($this->step < count($this->steps)) {
            $this->validateStep($this->step);
        }

        $this->step = min($this->step + 1, count($this->steps));
    }

    public function back(): void
    {
        $this->step = max(0, $this->step - 1);
    }

    public function submit(LeadService $leads, AuditService $audits)
    {
        foreach (array_keys($this->steps) as $index) {
            $this->validateStep($index);
        }

        $this->email = strtolower(trim($this->email));

        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'company' => ['required', 'string', 'max:190'],
            'website' => ['nullable', 'string', 'max:255'],
            'industry' => ['required', Rule::in(array_keys(config('advertally.industries')))],
            'consent' => ['accepted'],
        ], ['consent.accepted' => 'Please agree so we can send your report and follow up.']);

        if ($this->hp_trap !== '') {
            $this->addError('email', 'Something went wrong. Please try again.');

            return null;
        }

        $key = 'growth-score:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many attempts. Please try again in a few minutes.');

            return null;
        }
        RateLimiter::hit($key, 600);

        $audit = DB::transaction(function () use ($leads) {
            $lead = $leads->capture([
                'name' => $this->name,
                'email' => $this->email,
                'company' => $this->company,
                'website' => $this->website ?: null,
                'industry' => $this->industry,
                'service_interest' => 'not-sure',
                'source_page' => $this->sourcePage,
                'consent' => true,
                'message' => 'Completed the Advertally Growth Score.',
            ], 'growth_score', request());

            return AuditRequest::query()->create([
                'type' => 'growth_score',
                'lead_id' => $lead->id,
                'name' => $this->name,
                'email' => $this->email,
                'company' => $this->company,
                'website' => $this->website ?: null,
                'industry' => $this->industry,
                'ip_address' => request()->ip(),
            ]);
        });

        $audits->runGrowthScore($audit, array_map('intval', $this->answers));

        return $this->redirect($audit->url());
    }

    private function validateStep(int $index): void
    {
        $rules = [];
        foreach ($this->steps[$index]['questions'] ?? [] as $question) {
            $rules["answers.{$question['id']}"] = ['required', 'integer', 'min:0', 'max:'.(count($question['options']) - 1)];
        }

        $this->validate($rules, ['answers.*.required' => 'Please choose the answer that fits best.']);
    }

    public function render()
    {
        return view('livewire.growth-score-wizard', [
            'total' => count($this->steps),
            'industries' => config('advertally.industries'),
        ]);
    }
}
