<?php

use App\Filament\Pages\LeadPipeline;
use App\Filament\Resources;
use App\Filament\Widgets;
use Livewire\Livewire;
use App\Models\AuditReport;
use App\Models\Client;
use App\Models\Lead;
use App\Models\User;

/**
 * Opens every admin screen as a Super Admin to catch broken forms, tables and widgets.
 */
it('renders every admin screen', function () {
    $admin = User::where('role', 'admin')->first();
    $this->actingAs($admin);

    $lead = Lead::factory()->create(['assigned_to' => $admin->id, 'deal_value' => 50000]);
    $lead->notes()->create(['type' => 'call', 'body' => 'Discussed SEO + website', 'user_id' => $admin->id]);
    Client::create(['company' => 'Test Co', 'active_pillars' => ['grow'], 'monthly_value' => 24999, 'account_manager_id' => $admin->id]);
    AuditReport::create(['lead_id' => $lead->id, 'url' => 'https://example.com/', 'score' => 64, 'checks' => []]);

    $this->get('/admin')->assertOk()->assertSee('Dashboard');

    // Dashboard widgets are lazy-loaded, so render each one directly.
    Livewire::test(Widgets\LeadStatsOverview::class)->assertSee('Leads today');
    Livewire::test(Widgets\LeadsTrendChart::class)->assertOk();
    Livewire::test(Widgets\LeadsBySourceChart::class)->assertOk();
    Livewire::test(Widgets\FollowUpsDue::class)->assertOk();
    Livewire::test(Widgets\UpsellOpportunities::class)->assertSee('Test Co')->assertSee('Websites');

    // Pipeline drag & drop action
    Livewire::test(LeadPipeline::class)->call('moveLead', $lead->id, 'qualified');
    expect($lead->fresh()->status)->toBe('qualified');
    $this->get(LeadPipeline::getUrl())->assertOk()->assertSee($lead->name);

    $resources = [
        Resources\LeadResource::class, Resources\ClientResource::class, Resources\AuditReportResource::class,
        Resources\ServiceResource::class, Resources\PricingPlanResource::class, Resources\PlanBuilderItemResource::class,
        Resources\TestimonialResource::class, Resources\FaqResource::class, Resources\CaseStudyResource::class,
        Resources\ClientLogoResource::class, Resources\SettingResource::class, Resources\UserResource::class,
    ];

    foreach ($resources as $resource) {
        $this->get($resource::getUrl('index'))->assertOk();

        $pages = $resource::getPages();
        if (isset($pages['create'])) {
            $this->get($resource::getUrl('create'))->assertOk();
        }
        $record = $resource::getModel()::query()->first();
        if ($record && isset($pages['edit'])) {
            $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();
        }
        if ($record && isset($pages['view'])) {
            $this->get($resource::getUrl('view', ['record' => $record]))->assertOk();
        }
    }
});
