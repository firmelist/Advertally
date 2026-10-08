<?php

use App\Filament\Pages\Analytics;
use App\Filament\Resources;
use App\Filament\Widgets;
use App\Models\ActivityLog;
use App\Models\AuditRequest;
use App\Models\Lead;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Livewire\Livewire;

function superAdmin(): User
{
    return User::query()->where('email', config('advertally.admin.email'))->firstOrFail();
}

it('renders every admin screen for a super admin', function () {
    $this->actingAs(superAdmin());

    $lead = Lead::factory()->create();
    $lead->activities()->create(['type' => 'call', 'body' => 'Discussed AI search', 'user_id' => superAdmin()->id]);
    $audit = AuditRequest::query()->create(['type' => 'ai_visibility', 'company' => 'Test', 'email' => 't@test.com', 'status' => 'needs_review']);

    $this->get('/admin')->assertOk();
    foreach ([Widgets\GrowthStatsOverview::class, Widgets\LeadsTrendChart::class, Widgets\LeadSourcesChart::class, Widgets\TopInterestsChart::class, Widgets\LatestLeads::class] as $widget) {
        Livewire::test($widget)->assertOk();
    }
    $this->get(Analytics::getUrl())->assertOk()->assertSee('Integrations');

    $resources = [
        Resources\LeadResource::class, Resources\AuditRequestResource::class, Resources\ContactSubmissionResource::class,
        Resources\NewsletterSubscriberResource::class, Resources\PageResource::class, Resources\ServiceCategoryResource::class,
        Resources\ServiceResource::class, Resources\IndustryResource::class, Resources\CaseStudyResource::class,
        Resources\PostResource::class, Resources\PostCategoryResource::class, Resources\AuthorResource::class,
        Resources\AiResearchResource::class, Resources\TestimonialResource::class, Resources\ClientLogoResource::class,
        Resources\MediaResource::class, Resources\AuditDimensionResource::class, Resources\NavigationItemResource::class,
        Resources\SeoMetadataResource::class, Resources\SettingResource::class, Resources\UserResource::class,
        Resources\RoleResource::class, Resources\ActivityLogResource::class,
        Resources\InternshipResource::class, Resources\BrandResource::class, Resources\ClientProjectResource::class,
        Resources\StatisticResource::class, Resources\InternshipApplicationResource::class,
    ];

    foreach ($resources as $resource) {
        $this->get($resource::getUrl('index'))->assertOk();

        $model = $resource::getModel();
        $record = $model === Lead::class ? $lead : ($model === AuditRequest::class ? $audit : $model::query()->first());

        foreach (['create', 'edit', 'view'] as $page) {
            if (! $resource::hasPage($page) || ($page !== 'create' && ! $record)) {
                continue;
            }
            $this->get($page === 'create' ? $resource::getUrl('create') : $resource::getUrl($page, ['record' => $record]))->assertOk();
        }
    }
});

it('saves the homepage block builder', function () {
    $this->actingAs(superAdmin());
    $home = Page::query()->where('slug', 'home')->first();

    Livewire::test(Resources\PageResource\Pages\EditPage::class, ['record' => $home->getRouteKey()])
        ->fillForm(['title' => 'Home'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($home->fresh()->blocks)->toHaveCount(count($home->blocks));
    $this->get('/')->assertOk()->assertSee('Make Your Business')->assertSee('Impossible to Ignore.');
});

it('logs admin changes to the activity log', function () {
    $this->actingAs(superAdmin());
    Page::query()->where('slug', 'about')->first()->update(['title' => 'About us']);

    expect(ActivityLog::query()->where('action', 'updated')->where('subject_type', Page::class)->exists())->toBeTrue();
});

it('enforces role permissions', function () {
    $editor = User::factory()->role('content-editor')->create();
    $consultant = User::factory()->role('growth-consultant')->create();

    $this->actingAs($editor);
    $this->get(Resources\PostResource::getUrl('index'))->assertOk();
    $this->get(Resources\LeadResource::getUrl('index'))->assertForbidden();
    $this->get(Resources\UserResource::getUrl('index'))->assertForbidden();

    $this->actingAs($consultant);
    $mine = Lead::factory()->create(['assigned_to' => $consultant->id]);
    $other = Lead::factory()->create();
    $this->get(Resources\LeadResource::getUrl('index'))->assertOk()->assertSee($mine->name)->assertDontSee($other->name);
    $this->get(Resources\PageResource::getUrl('index'))->assertForbidden();
});

it('blocks inactive users and users without a role from the admin', function () {
    $this->actingAs(User::factory()->create(['role_id' => null]));
    $this->get('/admin')->assertForbidden();

    $this->actingAs(User::factory()->role('content-editor')->create(['is_active' => false]));
    $this->get('/admin')->assertForbidden();
});
