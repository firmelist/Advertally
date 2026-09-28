<?php

use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Filament\Resources\LeadResource\Pages\CreateLead;
use App\Filament\Resources\LeadResource\Pages\ListLeads;
use App\Filament\Resources\LeadResource\Pages\ViewLead;
use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::where('role', 'admin')->first();
    $this->actingAs($this->admin);
});

it('changes status, logs activity and exports leads from the table', function () {
    $lead = Lead::factory()->create(['status' => 'new']);

    Livewire::test(ListLeads::class)
        ->callTableAction('status', $lead, ['status' => 'qualified'])
        ->assertHasNoTableActionErrors()
        ->callTableAction('log', $lead, ['type' => 'call', 'body' => 'Spoke to owner'])
        ->assertHasNoTableActionErrors()
        ->callTableBulkAction('export', [$lead])
        ->assertFileDownloaded();

    foreach (['new', 'hot', 'follow_up', 'open', 'won'] as $tab) {
        Livewire::test(ListLeads::class)->set('activeTab', $tab)->assertOk();
    }
    Livewire::test(ListLeads::class)->filterTable('hot', true)->filterTable('follow_up_due', true)->assertOk();

    expect($lead->fresh()->status)->toBe('qualified')
        ->and($lead->notes()->where('type', 'call')->exists())->toBeTrue();
});

it('creates a lead manually', function () {
    Livewire::test(CreateLead::class)
        ->fillForm(['name' => 'Walk-in Client', 'phone' => '9876543210', 'status' => 'new', 'form_type' => 'contact', 'score' => 40])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Lead::where('name', 'Walk-in Client')->exists())->toBeTrue();
});

it('converts a won lead into a client with upsell tracking', function () {
    $lead = Lead::factory()->create(['services' => ['seo', 'google_ads'], 'company' => 'Sharma Clinics']);

    Livewire::test(ViewLead::class, ['record' => $lead->getRouteKey()])
        ->callAction('convert', ['active_pillars' => ['grow'], 'monthly_value' => 24999])
        ->assertHasNoActionErrors();

    $client = Client::where('company', 'Sharma Clinics')->first();
    expect($client)->not->toBeNull()
        ->and($client->next_pillar)->toBe('build')
        ->and($lead->fresh()->status)->toBe('won');

    Livewire::test(ListClients::class)
        ->filterTable('missing_pillar', 'build')
        ->assertCanSeeTableRecords([$client]);
});

it('creates a child service that inherits its hub pillar', function () {
    $hub = Service::whereNull('parent_id')->where('slug', 'solutions')->first();

    Livewire::test(CreateService::class)
        ->fillForm([
            'parent_id' => $hub->id, 'pillar' => 'grow', 'title' => 'Tally Integration', 'slug' => 'tally-integration',
            'icon' => 'plug', 'short_description' => 'Sync Tally with your CRM.', 'price_unit' => 'project', 'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $svc = Service::where('slug', 'tally-integration')->first();
    expect($svc->pillar)->toBe('automate');
    $this->get('/solutions/tally-integration')->assertOk()->assertSee('Tally Integration');
});
