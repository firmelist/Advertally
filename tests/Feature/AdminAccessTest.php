<?php

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use App\Models\User;

it('lets an admin open the dashboard and lead list', function () {
    $admin = User::where('role', 'admin')->first();
    $this->actingAs($admin)->get('/admin')->assertOk();
    $this->actingAs($admin)->get('/admin/leads')->assertOk();
    $this->actingAs($admin)->get('/admin/lead-pipeline')->assertOk();
});

it('shows sales users only their own leads', function () {
    $sales = User::factory()->sales()->create();
    $mine = Lead::factory()->create(['assigned_to' => $sales->id]);
    $other = Lead::factory()->create(['assigned_to' => null]);

    $this->actingAs($sales);
    $ids = LeadResource::getEloquentQuery()->pluck('id');

    expect($ids)->toContain($mine->id)->not->toContain($other->id);
    $this->get(LeadResource::getUrl('view', ['record' => $other]))->assertNotFound();
});

it('keeps editors out of sales data and sales out of content', function () {
    $editor = User::factory()->editor()->create();
    $sales = User::factory()->sales()->create();

    $this->actingAs($editor)->get('/admin/leads')->assertForbidden();
    $this->actingAs($editor)->get('/admin/services')->assertOk();
    $this->actingAs($sales)->get('/admin/services')->assertForbidden();
    $this->actingAs($sales)->get('/admin/settings')->assertForbidden();
});

it('blocks inactive users from the panel', function () {
    $user = User::factory()->create(['is_active' => false]);
    $this->actingAs($user)->get('/admin')->assertForbidden();
});
