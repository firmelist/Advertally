<?php

namespace App\Services;

use App\Models\Lead;

/**
 * Rule-based lead scoring (0–100). Tuned for the SME focus:
 * small & medium businesses with real budgets and multi-service needs score highest.
 */
class LeadScorer
{
    public function score(Lead $lead): int
    {
        $score = 10; // base for any genuine enquiry

        $score += match ($lead->business_size) {
            'small', 'medium' => 20,
            'large' => 12,
            'micro' => 5,
            default => 0,
        };

        $score += match ($lead->budget) {
            '25k_60k', '60k_2l', 'above_2l' => 15,
            'project', '10k_25k' => 8,
            default => 0,
        };

        $services = count($lead->services ?? []);
        if ($services >= 2) {
            $score += 10; // multi-service = "one partner" fit
        }

        if ($lead->form_type === 'audit') {
            $score += 10;
        }
        if (in_array($lead->form_type, ['quote', 'plan_builder', 'hire', 'consultation'], true)) {
            $score += 8;
        }

        $score += min(20, 5 * max(0, (int) $lead->pages_viewed - 1));

        if (filled($lead->email)) {
            $score += 3;
        }
        if (filled($lead->company)) {
            $score += 4;
        }
        if (filled($lead->utm_source) && in_array($lead->utm_medium, ['cpc', 'ppc', 'paid'], true)) {
            $score += 2;
        }

        return max(0, min(100, $score));
    }
}
