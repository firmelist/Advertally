<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
        $this->seed();
    })
    ->in('Feature');

/** A valid strategy-request payload for the contact form. */
function contactPayload(array $overrides = []): array
{
    return array_merge([
        'form' => 'contact',
        'name' => 'Priya Nair',
        'company' => 'Nair Analytics',
        'email' => 'priya@nairanalytics.in',
        'website' => 'nairanalytics.in',
        'industry' => 'saas',
        'challenge' => 'not-visible',
        'objective' => 'pipeline',
        'budget' => '3l-10l',
        'message' => 'We want to appear in AI answers for our category.',
        'consent' => '1',
        '_ts' => time() - 30,
    ], $overrides);
}
