<?php

use App\Jobs\NotifyInternshipApplication;
use App\Models\Brand;
use App\Models\ClientProject;
use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Models\Statistic;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

function brand(string $name, array $attributes = []): Brand
{
    return Brand::query()->create([
        'name' => $name, 'slug' => str($name)->slug(), 'logo' => 'brands/'.str($name)->slug().'.svg',
        'website_url' => 'https://'.str($name)->slug().'.example', 'industry' => 'Education',
        'is_public' => true, 'display_permission' => 'approved', 'show_on_internships' => true, 'status' => 'active',
        ...$attributes,
    ]);
}

function applicationPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Aarav Mehta', 'email' => 'Aarav@Example.com', 'phone' => '+91 98765 43210',
        'education' => 'B.Com, Delhi University', 'motivation' => 'I want to learn SEO and AI search by working on real campaigns with a team.',
        'resume' => UploadedFile::fake()->create('aarav-resume.pdf', 120, 'application/pdf'),
        'consent' => '1', '_ts' => time() - 30,
    ], $overrides);
}

it('lists published internships and renders the landing page flow', function () {
    $internship = Internship::query()->where('slug', 'digital-marketing-internship')->firstOrFail();

    $this->get('/internships')->assertOk()->assertSee($internship->title);
    $this->get('/careers')->assertOk()->assertSee('/internships');

    $this->get($internship->url())->assertOk()
        ->assertSee('Learn digital marketing')
        ->assertSee('Internship FAQs')
        ->assertSee('"@type":"JobPosting"', false)
        ->assertSee('id="apply"', false);
});

it('shows only public, approved, active brands assigned to the internship', function () {
    $internship = Internship::query()->firstOrFail();

    $shown = brand('Northwind Learning');
    $private = brand('Secret Pharma', ['is_public' => false]);
    $internal = brand('Internal Retail Co', ['display_permission' => 'internal']);
    $inactive = brand('Dormant Travel', ['status' => 'inactive']);
    $notForInternships = brand('Opted Out Labs', ['show_on_internships' => false]);
    brand('Unassigned Brand');

    $internship->brands()->attach([$shown->id, $private->id, $internal->id, $inactive->id, $notForInternships->id]);
    $internship->update(['brands_heading' => "Brands You'll Work With"]);

    $html = $this->get($internship->url())->assertOk()
        ->assertSee("Brands You'll Work With")
        ->assertSee('Northwind Learning')
        ->assertSee(Internship::DISCLAIMER)
        ->getContent();

    foreach ([$private, $internal, $inactive, $notForInternships] as $hidden) {
        expect($html)->not->toContain($hidden->name)
            ->not->toContain($hidden->logo)
            ->not->toContain($hidden->website_url);
    }
    expect($html)->not->toContain('Unassigned Brand');

    // Structured data never lists brands, even approved ones.
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $schema);
    expect(implode('', $schema[1]))->not->toContain('Northwind Learning');
});

it('hides the brand section when no brand is cleared for display', function () {
    $internship = Internship::query()->firstOrFail();
    $internship->brands()->attach(brand('Secret Pharma', ['is_public' => false]));

    $this->get($internship->url())->assertOk()
        ->assertDontSee('Secret Pharma')
        ->assertDontSee(Internship::DISCLAIMER);
});

it('labels projects of confidential brands without naming them', function () {
    $internship = Internship::query()->firstOrFail();
    $private = brand('Secret Pharma', ['is_public' => false, 'industry' => 'Healthcare']);

    $project = ClientProject::query()->create([
        'brand_id' => $private->id, 'title' => 'Patient education content hub', 'industry' => 'Healthcare',
        'is_public' => true, 'display_permission' => 'approved', 'status' => 'active',
    ]);
    $hiddenProject = ClientProject::query()->create([
        'title' => 'Internal pricing study', 'is_public' => false, 'display_permission' => 'internal', 'status' => 'active',
    ]);
    $internship->projectLinks()->create(['client_project_id' => $project->id, 'intern_contribution' => 'Keyword research and briefs']);
    $internship->projectLinks()->create(['client_project_id' => $hiddenProject->id]);

    $this->get($internship->url())->assertOk()
        ->assertSee('Patient education content hub')
        ->assertSee('Confidential client')
        ->assertDontSee('Secret Pharma')
        ->assertDontSee('Internal pricing study');
});

it('shows statistics only when enabled and visible', function () {
    $internship = Internship::query()->firstOrFail();
    $internship->brands()->attach(brand('Northwind Learning'));
    Statistic::query()->create(['context' => 'internships', 'value' => '12+', 'label' => 'Industries supported', 'is_visible' => true]);
    Statistic::query()->create(['context' => 'internships', 'value' => '99', 'label' => 'Hidden figure', 'is_visible' => false]);

    $this->get($internship->url())->assertDontSee('Industries supported');

    $internship->update(['show_statistics' => true]);
    $this->get($internship->url())->assertSee('Industries supported')->assertDontSee('Hidden figure');
});

it('accepts an application with a private résumé upload', function () {
    Queue::fake();
    Storage::fake('local');
    $internship = Internship::query()->firstOrFail();

    $this->post(route('internships.apply', $internship->slug), applicationPayload())
        ->assertRedirect()
        ->assertSessionHas('application_submitted');

    $application = InternshipApplication::query()->sole();
    expect($application)
        ->email->toBe('aarav@example.com')
        ->internship_id->toBe($internship->id)
        ->status->toBe('new')
        ->resume_name->toBe('aarav-resume.pdf');
    Storage::disk('local')->assertExists($application->resume_path);
    Queue::assertPushed(NotifyInternshipApplication::class);
});

it('rejects invalid or spam applications', function () {
    Queue::fake();
    Storage::fake('local');
    $internship = Internship::query()->firstOrFail();

    $this->post(route('internships.apply', $internship->slug), applicationPayload(['resume' => UploadedFile::fake()->create('cv.exe', 10)]))
        ->assertSessionHasErrors('resume');
    $this->post(route('internships.apply', $internship->slug), applicationPayload(['hp_trap' => 'x']))
        ->assertSessionHasErrors('email');

    $internship->update(['status' => 'draft']);
    $this->get($internship->url())->assertNotFound();

    expect(InternshipApplication::query()->count())->toBe(0);
});
