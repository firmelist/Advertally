<?php

use App\Models\AiResearch;
use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;

it('renders the homepage with the core positioning and one H1', function () {
    $html = $this->get('/')->assertOk()
        ->assertSee('Make Your Business Impossible to Ignore.')
        ->assertSee('One Growth System. Six Engines.')
        ->assertSee('Get Your Growth Score')
        ->assertSee('Sample data')
        ->getContent();

    expect(substr_count($html, '<h1'))->toBe(1);
});

it('renders every static and listing page', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/solutions', '/growth-technology', '/technology-talent', '/industries', '/case-studies', '/insights',
    '/insights?type=report', '/ai-search-lab', '/resources', '/growth-score', '/ai-visibility-audit', '/contact',
    '/about', '/approach', '/careers', '/privacy-policy', '/terms', '/cookie-policy',
]);

it('renders every engine, service, industry, insight, research and case study page', function () {
    ServiceCategory::query()->published()->with('services')->get()->each(function ($category) {
        $this->get($category->url())->assertOk()->assertSee($category->headline);
        $category->services->each(fn ($s) => $this->get($s->url())->assertOk());
    });

    Industry::all()->each(fn ($i) => $this->get($i->url())->assertOk()->assertSee($i->name));
    Post::all()->each(fn ($p) => $this->get($p->url())->assertOk()->assertSee($p->title));
    AiResearch::all()->each(fn ($r) => $this->get($r->url())->assertOk()->assertSee('Key findings'));
    CaseStudy::all()->each(fn ($c) => $this->get($c->url())->assertOk()->assertSee('Sample case study'));
});

it('keeps each vertical in its own URL space', function () {
    $talent = Service::query()->where('slug', 'hire-developers')->first();
    $tech = Service::query()->where('slug', 'websites')->first();

    expect($talent->url())->toEndWith('/technology-talent/hire-developers')
        ->and($tech->url())->toEndWith('/growth-technology/websites');

    $this->get('/services/hire-developers')->assertNotFound();
    $this->get('/services/websites')->assertNotFound();
});

it('hides draft content', function () {
    Post::query()->where('slug', 'speed-to-lead')->update(['status' => 'draft']);

    $this->get('/insights/speed-to-lead')->assertNotFound();
});

it('shows the branded 404 page', function () {
    $this->get('/this-does-not-exist')->assertNotFound()->assertSee('This Growth Signal Went Offline.');
});

it('outputs organisation, breadcrumb, service and FAQ schema', function () {
    $html = $this->get('/services/geo')->getContent();

    expect($html)->toContain('"@type":"Organization"')
        ->toContain('"@type":"BreadcrumbList"')
        ->toContain('"@type":"Service"')
        ->toContain('"@type":"FAQPage"')
        ->toContain('rel="canonical"');
});

it('serves sitemap, robots and llms.txt', function () {
    $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee(url('/solutions/ai-search'), false)->assertSee(url('/ai-search-lab/ai-visibility-framework'), false);
    $this->get('/robots.txt')->assertOk();
    $this->get('/llms.txt')->assertOk()->assertSee('# Advertally')->assertSee('AI Search');
});
