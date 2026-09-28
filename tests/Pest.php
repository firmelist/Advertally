<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
        $this->seed();
    })
    ->in('Feature');

/** Build a valid lead payload for the public forms. */
function leadPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Rahul Mehta',
        'phone' => '+91 98100 12345',
        'email' => 'rahul@example.com',
        'company' => 'Mehta Industries',
        'business_size' => 'small',
        'budget' => '25k_60k',
        'services' => ['seo', 'website'],
        'form_type' => 'quote',
        'form_id' => 'test',
        'consent' => '1',
        '_ts' => time() - 30,
    ], $overrides);
}
