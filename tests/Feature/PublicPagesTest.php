<?php

use App\Models\CaseStudy;
use App\Models\Service;

it('renders the core public pages', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/', '/pricing', '/about', '/contact', '/book-consultation', '/free-website-audit',
    '/case-studies', '/privacy-policy', '/terms', '/refund-policy',
]);

it('renders every service hub and child service page', function () {
    Service::with('parent')->where('is_active', true)->get()->each(function (Service $s) {
        $this->get($s->url)->assertOk()->assertSee($s->title, false);
    });
});

it('shows the next growth-ladder step on a service page (upsell)', function () {
    $this->get('/digital-marketing')->assertOk()->assertSee('Your next step')->assertSee('Websites &amp; E-commerce', false);
});

it('renders case study pages', function () {
    $cs = CaseStudy::first();
    $this->get(route('case-studies.show', $cs))->assertOk()->assertSee($cs->client);
});

it('returns 404 for unknown services', function () {
    $this->get('/digital-marketing/does-not-exist')->assertNotFound();
});

it('serves an XML sitemap containing service URLs', function () {
    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee(url('/digital-marketing/seo'), false);
});

it('outputs FAQ and Organization schema on the homepage', function () {
    $this->get('/')->assertSee('"@type":"FAQPage"', false)->assertSee('ProfessionalService', false);
});
