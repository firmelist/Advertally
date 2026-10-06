<?php

namespace App\Services\Audit;

use App\Models\AuditDimension;
use App\Models\AuditRequest;
use App\Models\GrowthScoreQuestion;
use App\Services\AI\AiException;
use App\Services\AI\AiManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Runs and persists both scoring products:
 *  - AI Visibility Audit: on-site signal analysis → six dimension scores + top opportunities.
 *  - Growth Score™: self-assessment questionnaire → six dimension scores + recommendations.
 * An optional AI provider writes the executive summary; rule-based text is used otherwise.
 */
class AuditService
{
    public function __construct(private AiVisibilityAnalyzer $analyzer, private AiManager $ai) {}

    public function runAiVisibility(AuditRequest $audit): AuditRequest
    {
        $audit->update(['status' => 'processing', 'engine' => 'onsite-signals']);

        try {
            $result = $this->analyzer->analyse((string) $audit->website, $audit->company);
        } catch (InvalidArgumentException $e) {
            // Bot-blocked or offline sites are completed manually by an analyst.
            $audit->update(['status' => 'needs_review', 'error' => $e->getMessage()]);

            return $audit;
        }

        $dimensions = AuditDimension::query()->ofType('ai_visibility')->get()->keyBy('key');
        $scores = collect($result['dimensions'])->map(fn ($d) => $d['score']);

        DB::transaction(function () use ($audit, $result, $dimensions, $scores) {
            $audit->scores()->delete();
            $audit->recommendations()->delete();

            foreach ($result['dimensions'] as $key => $dimension) {
                if ($model = $dimensions->get($key)) {
                    $audit->scores()->create([
                        'audit_dimension_id' => $model->id,
                        'score' => $dimension['score'],
                        'checks' => $dimension['checks'],
                        'summary' => $this->dimensionSummary($dimension['checks']),
                    ]);
                }
            }

            // Top opportunities: the heaviest failing checks across all dimensions.
            collect($result['dimensions'])
                ->flatMap(fn ($d, $key) => collect($d['checks'])->map(fn ($c) => [...$c, 'dimension' => $key]))
                ->reject(fn ($c) => $c['status'] === 'pass')
                ->sortByDesc(fn ($c) => $c['weight'] * ($c['status'] === 'fail' ? 2 : 1))
                ->take(5)
                ->values()
                ->each(fn ($c, $i) => $audit->recommendations()->create([
                    'audit_dimension_id' => $dimensions->get($c['dimension'])?->id,
                    'title' => $c['label'],
                    'description' => $c['fix'],
                    'impact' => $c['status'] === 'fail' && $c['weight'] >= 20 ? 'high' : ($c['status'] === 'fail' ? 'medium' : 'low'),
                    'sort_order' => $i,
                ]));

            $audit->update([
                'status' => 'completed',
                'overall_score' => $this->weighted($scores, $dimensions),
                'signals' => $result['facts'],
                'completed_at' => now(),
            ]);
        });

        $audit->update(['summary' => $this->summarise($audit->fresh(['scores.dimension', 'recommendations']))]);

        return $audit;
    }

    /**
     * @param  array<int|string, int>  $answers  question id => selected option index
     */
    public function runGrowthScore(AuditRequest $audit, array $answers): AuditRequest
    {
        $questions = GrowthScoreQuestion::query()->where('is_active', true)->with('dimension')->get()->keyBy('id');
        $dimensions = AuditDimension::query()->ofType('growth_score')->get()->keyBy('key');

        $points = collect($answers)
            ->filter(fn ($option, $id) => isset($questions[$id]))
            ->map(fn ($option, $id) => [
                'dimension' => $questions[$id]->dimension->key,
                'points' => (int) ($questions[$id]->options[(int) $option]['points'] ?? 0),
            ])
            ->groupBy('dimension')
            ->map(fn (Collection $rows) => (int) round($rows->avg('points')));

        DB::transaction(function () use ($audit, $answers, $dimensions, $points) {
            $audit->scores()->delete();
            $audit->recommendations()->delete();

            $order = 0;
            foreach ($dimensions as $key => $dimension) {
                $score = $points->get($key, 0);
                $audit->scores()->create(['audit_dimension_id' => $dimension->id, 'score' => $score]);
            }

            // Recommendations for the weakest dimensions first.
            $dimensions->sortBy(fn ($d) => $points->get($d->key, 0))
                ->flatMap(fn ($d) => collect($d->recommendationsFor($points->get($d->key, 0)))->take(1)->map(fn ($r) => [...$r, 'dimension_id' => $d->id]))
                ->take(5)
                ->each(function ($r) use ($audit, &$order) {
                    $audit->recommendations()->create([
                        'audit_dimension_id' => $r['dimension_id'],
                        'title' => $r['title'],
                        'description' => $r['description'] ?? null,
                        'impact' => $r['impact'] ?? 'medium',
                        'sort_order' => $order++,
                    ]);
                });

            $audit->update([
                'answers' => $answers,
                'engine' => 'questionnaire',
                'status' => 'completed',
                'overall_score' => $this->weighted($points, $dimensions),
                'completed_at' => now(),
            ]);
        });

        $audit->update(['summary' => $this->summarise($audit->fresh(['scores.dimension', 'recommendations']))]);

        return $audit;
    }

    private function weighted(Collection $scores, Collection $dimensions): int
    {
        $total = 0;
        $weights = 0;
        foreach ($dimensions as $key => $dimension) {
            $total += $scores->get($key, 0) * $dimension->weight;
            $weights += $dimension->weight;
        }

        return $weights ? (int) round($total / $weights) : 0;
    }

    private function dimensionSummary(array $checks): string
    {
        $passed = count(array_filter($checks, fn ($c) => $c['status'] === 'pass'));

        return "{$passed} of ".count($checks).' signals in place.';
    }

    private function summarise(AuditRequest $audit): string
    {
        $scores = $audit->scores->sortByDesc('score');
        $strongest = $scores->first()?->dimension?->name;
        $weakest = $scores->last()?->dimension?->name;

        if ($this->ai->enabled()) {
            try {
                return $this->ai->provider()->complete(
                    'You are a senior B2B growth strategist at Advertally. Write a concise, factual 3-sentence executive summary of an audit. '
                    .'Never invent numbers beyond those given. Never promise rankings or AI citations.',
                    json_encode([
                        'company' => $audit->company,
                        'overall_score' => $audit->overall_score,
                        'scores' => $audit->scores->mapWithKeys(fn ($s) => [$s->dimension?->name => $s->score]),
                        'top_opportunities' => $audit->recommendations->pluck('title'),
                    ]),
                    ['max_tokens' => 300],
                );
            } catch (AiException) {
                // Fall back to rule-based summary.
            }
        }

        $level = match (score_tone($audit->overall_score)) {
            'strong' => 'a strong foundation to build on',
            'fair' => 'a workable foundation with clear gaps',
            default => 'significant untapped growth potential',
        };

        return "Overall score {$audit->overall_score}/100 indicates {$level}. "
            .($strongest ? "{$strongest} is currently the strongest area" : '')
            .($weakest && $weakest !== $strongest ? ", while {$weakest} offers the biggest opportunity for improvement." : '.');
    }
}
