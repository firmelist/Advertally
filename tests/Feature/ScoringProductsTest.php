<?php

use App\Livewire\GrowthScoreWizard;
use App\Models\AuditRequest;
use App\Models\GrowthScoreQuestion;
use App\Models\Lead;
use App\Services\Audit\SafeFetcher;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

beforeEach(function () {
    Queue::fake();

    // Skip real DNS lookups in tests; HTTP is faked below.
    app()->bind(SafeFetcher::class, fn () => new class extends SafeFetcher
    {
        public function assertPublicHost(string $host): void {}
    });
});

it('runs the Growth Score end to end', function () {
    $answers = GrowthScoreQuestion::query()->pluck('id')->mapWithKeys(fn ($id) => [$id => 1])->all();

    $component = Livewire::test(GrowthScoreWizard::class);
    $steps = count($component->get('steps'));
    expect($steps)->toBe(6);

    // Cannot continue without answering.
    $component->call('next')->assertHasErrors();

    $component->set('answers', $answers);
    foreach (range(1, $steps) as $_) {
        $component->call('next')->assertHasNoErrors();
    }

    $component->set('name', 'Arjun Rao')->set('email', 'arjun@raotech.io')->set('company', 'Rao Tech')
        ->set('industry', 'technology')->set('consent', true)
        ->call('submit')->assertHasNoErrors();

    $audit = AuditRequest::query()->where('type', 'growth_score')->sole();
    expect($audit->status)->toBe('completed')
        ->and($audit->overall_score)->toBeBetween(1, 99)
        ->and($audit->scores)->toHaveCount(6)
        ->and($audit->recommendations->count())->toBeGreaterThan(0)
        ->and(Lead::query()->sole()->form_type)->toBe('growth_score');

    $component->assertRedirect($audit->url());
    $this->get($audit->url())->assertOk()->assertSee('Growth Score for Rao Tech')->assertSee('What to fix first');
});

it('runs the AI Visibility Audit against website signals', function () {
    Http::fake([
        'https://acme-b2b.com/' => Http::response(<<<'HTML'
            <html><head><title>Acme B2B — Cloud consulting for fintech</title>
            <meta name="description" content="Acme helps fintech companies migrate to the cloud securely with certified engineers and proven playbooks.">
            <meta name="viewport" content="width=device-width"><link rel="canonical" href="https://acme-b2b.com/">
            <script type="application/ld+json">{"@context":"https://schema.org","@type":"Organization","name":"Acme B2B","sameAs":["https://www.linkedin.com/company/acme"]}</script>
            </head><body><h1>Cloud consulting for fintech</h1><h2>Services</h2><h2>Case studies</h2>
            <a href="/about">About</a><a href="/services/migration">Migration</a><a href="https://www.linkedin.com/company/acme">LinkedIn</a>
            <form><input name="email"></form><p>Book a consultation. Read our case studies and client testimonials.</p></body></html>
            HTML),
        'https://acme-b2b.com/robots.txt' => Http::response("User-agent: GPTBot\nDisallow: /\n"),
        'https://acme-b2b.com/llms.txt' => Http::response('', 404),
        'https://acme-b2b.com/sitemap.xml' => Http::response('<urlset></urlset>'),
    ]);

    $this->post('/ai-visibility-audit', [
        'website' => 'acme-b2b.com', 'company' => 'Acme B2B', 'name' => 'Neha', 'email' => 'neha@acme-b2b.com',
        'industry' => 'technology', 'country' => 'India', 'primary_service' => 'Cloud consulting',
        'consent' => '1', '_ts' => time() - 30,
    ])->assertRedirect();

    $audit = AuditRequest::query()->where('type', 'ai_visibility')->sole();
    expect($audit->status)->toBe('completed')
        ->and($audit->scores)->toHaveCount(6)
        ->and($audit->signals['blocked_ai_bots'])->toContain('GPTBot')
        ->and($audit->signals['schema_types'])->toContain('Organization')
        ->and($audit->recommendations)->toHaveCount(5);

    $this->get($audit->url())->assertOk()->assertSee('AI Visibility Report: Acme B2B')->assertSee('Discuss My Growth Opportunities');
});

it('hands unreachable sites to an analyst instead of failing', function () {
    Http::fake(['*' => Http::response('blocked', 403)]);

    $this->post('/ai-visibility-audit', [
        'website' => 'locked-down.example', 'company' => 'Locked', 'name' => 'Sam', 'email' => 'sam@locked.example',
        'industry' => 'consulting', 'country' => 'India', 'primary_service' => 'Advisory', 'consent' => '1', '_ts' => time() - 30,
    ])->assertRedirect();

    $audit = AuditRequest::query()->sole();
    expect($audit->status)->toBe('needs_review');
    $this->get($audit->url())->assertOk()->assertSee('Your audit needs an analyst');
});

it('does not expose growth score answers points to the browser', function () {
    $steps = Livewire::test(GrowthScoreWizard::class)->get('steps');

    expect(json_encode($steps))->not->toContain('points');
});
