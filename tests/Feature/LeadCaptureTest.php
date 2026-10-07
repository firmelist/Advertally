<?php

use App\Jobs\NotifyNewLead;
use App\Models\ContactSubmission;
use App\Models\Lead;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(fn () => Queue::fake());

it('captures a strategy request with attribution and raw submission', function () {
    // First touch from a LinkedIn campaign …
    $this->get('/?utm_source=linkedin&utm_medium=paid&utm_campaign=ai-search-q4')->assertOk();

    $this->post('/contact', contactPayload())->assertRedirect('/thank-you');

    $lead = Lead::query()->sole();
    expect($lead)
        ->email->toBe('priya@nairanalytics.in')
        ->form_type->toBe('contact')
        ->utm_source->toBe('linkedin')
        ->utm_campaign->toBe('ai-search-q4')
        ->source->toBe('linkedin')
        ->status->toBe('new')
        ->and($lead->score)->toBeGreaterThan(50);

    expect(ContactSubmission::query()->where('lead_id', $lead->id)->exists())->toBeTrue();
    Queue::assertPushed(NotifyNewLead::class);

    $this->get('/thank-you')->assertOk()->assertSee('Thank you');
});

it('validates the contact form', function () {
    $this->post('/contact', contactPayload(['email' => 'not-an-email', 'message' => '', 'consent' => null]))
        ->assertSessionHasErrors(['email', 'message', 'consent']);

    expect(Lead::count())->toBe(0);
});

it('blocks bots via honeypot and time trap', function () {
    $this->post('/contact', contactPayload(['hp_trap' => 'http://spam.example']))->assertSessionHasErrors('email');
    $this->post('/contact', contactPayload(['_ts' => time()]))->assertSessionHasErrors('email');

    expect(Lead::count())->toBe(0);
});

it('captures technology & talent enquiries as a separate journey', function () {
    $this->post('/contact', contactPayload(['form' => 'talent', 'industry' => null, 'message' => null, 'talent_role' => 'developers']))
        ->assertRedirect('/thank-you');

    $lead = Lead::query()->sole();
    expect($lead->form_type)->toBe('talent')
        ->and($lead->service_interest)->toBe('talent')
        ->and($lead->message)->toContain('Developers');
});

it('assigns leads round-robin to growth consultants', function () {
    $a = User::factory()->role('growth-consultant')->create();
    $b = User::factory()->role('growth-consultant')->create();

    $this->post('/contact', contactPayload(['email' => 'one@example.com']));
    $this->post('/contact', contactPayload(['email' => 'two@example.com']));

    expect(Lead::query()->orderBy('id')->pluck('assigned_to')->all())->toBe([$a->id, $b->id]);
});

it('subscribes and unsubscribes from the newsletter', function () {
    $this->post('/newsletter', ['email' => 'Reader@Example.com', '_ts' => time() - 10])->assertSessionHas('newsletter');

    $subscriber = NewsletterSubscriber::query()->sole();
    expect($subscriber->email)->toBe('reader@example.com');

    $this->get(route('newsletter.unsubscribe', $subscriber->token))->assertOk();
    expect($subscriber->fresh()->status)->toBe('unsubscribed');
});
