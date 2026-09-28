<?php

use App\Jobs\NotifyNewLead;
use App\Mail\LeadThankYou;
use App\Mail\NewLeadAlert;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

it('captures a lead, normalises the phone, scores it and redirects to thank-you', function () {
    Bus::fake([NotifyNewLead::class]);

    $this->post('/leads', leadPayload())->assertRedirect('/thank-you');

    $lead = Lead::latest('id')->first();
    expect($lead->phone)->toBe('9810012345')
        ->and($lead->services)->toBe(['seo', 'website'])
        ->and($lead->status)->toBe('new')
        ->and($lead->score)->toBeGreaterThanOrEqual(50);

    Bus::assertDispatched(NotifyNewLead::class);
});

it('returns JSON for AJAX submissions', function () {
    Bus::fake();
    $this->postJson('/leads', leadPayload())->assertOk()->assertJson(['ok' => true]);
});

it('stores first-touch UTM attribution on the lead', function () {
    Bus::fake();
    $this->get('/?utm_source=google&utm_medium=cpc&utm_campaign=seo-delhi&gclid=abc123');
    $this->post('/leads', leadPayload());

    $lead = Lead::latest('id')->first();
    expect($lead->utm_source)->toBe('google')
        ->and($lead->utm_campaign)->toBe('seo-delhi')
        ->and($lead->gclid)->toBe('abc123');
});

it('validates required fields and phone format', function () {
    $this->postJson('/leads', leadPayload(['name' => '', 'phone' => '123']))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'phone']);
});

it('requires consent', function () {
    $this->postJson('/leads', leadPayload(['consent' => null]))->assertJsonValidationErrors(['consent']);
});

it('rejects bots via honeypot and time-trap', function () {
    $this->postJson('/leads', leadPayload(['company_website' => 'http://spam.test']))->assertStatus(422);
    $this->postJson('/leads', leadPayload(['_ts' => time()]))->assertStatus(422);
    expect(Lead::where('email', 'rahul@example.com')->count())->toBe(0);
});

it('rate limits lead submissions', function () {
    Bus::fake();
    foreach (range(1, 5) as $i) {
        $this->postJson('/leads', leadPayload(['email' => "r{$i}@example.com"]))->assertOk();
    }
    $this->postJson('/leads', leadPayload())->assertStatus(429);
});

it('round-robins leads across active sales users', function () {
    Bus::fake();
    $a = User::where('role', 'sales')->first();
    $b = User::factory()->sales()->create();

    $this->postJson('/leads', leadPayload(['email' => 'one@example.com']));
    $this->postJson('/leads', leadPayload(['email' => 'two@example.com']));

    $owners = Lead::whereIn('email', ['one@example.com', 'two@example.com'])->pluck('assigned_to')->all();
    expect($owners)->toContain($a->id)->toContain($b->id);
});

it('emails the sales team and the prospect', function () {
    Mail::fake();
    $this->post('/leads', leadPayload());

    Mail::assertSent(NewLeadAlert::class);
    Mail::assertSent(LeadThankYou::class, fn ($m) => $m->hasTo('rahul@example.com'));
});

it('logs a timeline note when the status changes', function () {
    Bus::fake();
    $this->post('/leads', leadPayload());
    $lead = Lead::latest('id')->first();

    $lead->update(['status' => 'contacted']);

    expect($lead->notes()->where('type', 'status')->exists())->toBeTrue()
        ->and($lead->fresh()->contacted_at)->not->toBeNull();
});
