<?php

use App\Models\AuditReport;
use App\Models\Lead;
use App\Services\WebsiteAuditor;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

it('blocks private and internal addresses (SSRF protection)', function (string $url) {
    expect(fn () => app(WebsiteAuditor::class)->normalise($url))->toThrow(InvalidArgumentException::class);
})->with(['http://localhost', 'http://127.0.0.1', 'http://10.0.0.5', 'http://192.168.1.1', 'http://169.254.169.254', 'https://intranet.local', 'http://example.com:8080']);

it('scores a website and flags missing essentials', function () {
    $auditor = Mockery::mock(WebsiteAuditor::class)->makePartial();
    $auditor->shouldReceive('assertPublicHost')->andReturnNull();

    Http::fake([
        'https://good.example/' => Http::response('<html><head><title>Best Dental Clinic in Gurugram | Smile Care</title><meta name="viewport" content="width=device-width"><meta name="description" content="Painless dental treatment in Gurugram. Book your appointment today on WhatsApp or call us for same-day slots."></head><body><h1>Dental care</h1><a href="https://wa.me/919999999999">WhatsApp</a><a href="tel:+919999999999">Call</a><form></form></body></html>'),
        'https://good.example/robots.txt' => Http::response('User-agent: *'),
        'https://good.example/sitemap.xml' => Http::response('<urlset></urlset>'),
    ]);

    $result = $auditor->run('https://good.example/');
    $byLabel = collect($result['checks'])->keyBy('label');

    expect($result['score'])->toBeGreaterThan(60)
        ->and($byLabel['SSL certificate (HTTPS)']['status'])->toBe('pass')
        ->and($byLabel['WhatsApp chat button']['status'])->toBe('pass')
        ->and($byLabel['Analytics / tracking installed']['status'])->toBe('warn');
});

it('creates an audit lead and report from the public form', function () {
    Bus::fake();
    Mail::fake();

    $this->mock(WebsiteAuditor::class, function ($mock) {
        $mock->shouldReceive('normalise')->andReturn('https://acme.example/');
        $mock->shouldReceive('run')->andReturn(['score' => 58, 'performance_score' => null, 'checks' => [
            ['category' => 'security', 'label' => 'SSL certificate (HTTPS)', 'status' => 'pass', 'weight' => 15, 'detail' => 'ok'],
        ]]);
    });

    $response = $this->post('/free-website-audit', leadPayload(['website' => 'acme.example', 'form_type' => 'audit', 'form_id' => 'audit']));

    $report = AuditReport::first();
    $response->assertRedirect(route('audit.show', $report));
    expect(Lead::where('form_type', 'audit')->count())->toBe(1)
        ->and($report->score)->toBe(58);

    $this->get(route('audit.show', $report))->assertOk()->assertSee('acme.example');
});
