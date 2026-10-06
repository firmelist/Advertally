<?php

namespace App\Jobs;

use App\Mail\LeadAcknowledgement;
use App\Mail\NewLeadAlert;
use App\Models\Lead;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Fans a new lead out to every channel. Each channel is isolated so one failure never blocks the others.
 */
class NotifyNewLead implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public Lead $lead) {}

    public function handle(): void
    {
        $lead = $this->lead->loadMissing('assignee');

        $this->safely('team email', function () use ($lead) {
            $to = collect(config('advertally.lead_alert_emails'))->push($lead->assignee?->email)->filter()->unique()->values()->all();
            if ($to) {
                Mail::to($to)->send(new NewLeadAlert($lead));
            }
        });

        $this->safely('acknowledgement email', function () use ($lead) {
            Mail::to($lead->email, $lead->name)->send(new LeadAcknowledgement($lead));
        });

        $this->safely('admin notification', function () use ($lead) {
            $recipients = User::query()->where('is_active', true)
                ->where(fn ($q) => $q->whereHas('role', fn ($r) => $r->where('is_super', true))->orWhere('id', $lead->assigned_to))
                ->get();

            Notification::make()
                ->title("New lead: {$lead->name}")
                ->body(($lead->company ? "{$lead->company} · " : '').$lead->label('form_type').' · fit '.$lead->score)
                ->icon('heroicon-o-bolt')
                ->actions([Action::make('view')->url(url('/admin/leads/'.$lead->id))->markAsRead()])
                ->sendToDatabase($recipients);
        });

        $this->safely('slack', function () use ($lead) {
            if ($hook = config('advertally.slack_lead_webhook')) {
                Http::timeout(5)->post($hook, [
                    'text' => "*New lead* ({$lead->label('form_type')}, fit {$lead->score}): {$lead->name}"
                        .($lead->company ? " · {$lead->company}" : '')."\nSource: {$lead->source}",
                ]);
            }
        });

        $this->safely('outbound webhook', function () use ($lead) {
            if ($url = config('advertally.lead_webhook_url')) {
                Http::timeout(8)->post($url, [
                    'event' => 'lead.created',
                    'lead' => $lead->only([
                        'id', 'name', 'company', 'email', 'phone', 'website', 'job_title', 'industry', 'service_interest',
                        'challenge', 'objective', 'budget', 'message', 'form_type', 'source', 'landing_page',
                        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                        'first_touch_source', 'last_touch_source', 'score', 'created_at',
                    ]),
                ]);
            }
        });
    }

    private function safely(string $channel, callable $fn): void
    {
        try {
            $fn();
        } catch (Throwable $e) {
            Log::warning("Lead #{$this->lead->id}: {$channel} failed — {$e->getMessage()}");
        }
    }
}
