<?php

namespace App\Jobs;

use App\Mail\LeadThankYou;
use App\Mail\NewLeadAlert;
use App\Models\Lead;
use App\Models\User;
use App\Services\WhatsApp;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Fans a new lead out to every channel. Each channel is isolated so one failure
 * (e.g. WhatsApp token expired) never blocks the others.
 */
class NotifyNewLead implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public Lead $lead) {}

    public function handle(WhatsApp $whatsapp): void
    {
        $lead = $this->lead->loadMissing('assignee');

        $this->safely('sales email', function () use ($lead) {
            $to = collect(config('advertally.lead_alert_emails'))
                ->push($lead->assignee?->email)
                ->filter()->unique()->values()->all();
            if ($to) {
                Mail::to($to)->send(new NewLeadAlert($lead));
            }
        });

        $this->safely('auto-responder email', function () use ($lead) {
            if ($lead->email) {
                Mail::to($lead->email, $lead->name)->send(new LeadThankYou($lead));
            }
        });

        $this->safely('whatsapp alert', function () use ($lead, $whatsapp) {
            $to = $lead->assignee?->phone ?: config('advertally.whatsapp.alert_to');
            if ($to) {
                $whatsapp->sendTemplate($to, config('advertally.whatsapp.template_lead_alert'), [
                    $lead->name,
                    $lead->phone,
                    $lead->company ?: '-',
                    $lead->services_label ?: ($lead->form_type ?: '-'),
                    (string) $lead->score,
                ]);
            }
        });

        $this->safely('whatsapp auto-reply', function () use ($lead, $whatsapp) {
            $whatsapp->sendTemplate($lead->phone, config('advertally.whatsapp.template_thank_you'), [$lead->name]);
        });

        $this->safely('admin bell notification', function () use ($lead) {
            $recipients = User::query()->where('is_active', true)
                ->where(fn ($q) => $q->where('role', 'admin')->orWhere('id', $lead->assigned_to))
                ->get();

            Notification::make()
                ->title("New lead: {$lead->name}")
                ->body(($lead->company ? "{$lead->company} · " : '').'Score '.$lead->score.' · '.($lead->services_label ?: $lead->form_type))
                ->icon('heroicon-o-bolt')
                ->iconColor($lead->score >= 60 ? 'danger' : 'warning')
                ->actions([Action::make('view')->url(url('/admin/leads/'.$lead->id))->markAsRead()])
                ->sendToDatabase($recipients);
        });

        $this->safely('slack', function () use ($lead) {
            if ($hook = config('advertally.slack_lead_webhook')) {
                Http::timeout(5)->post($hook, [
                    'text' => "*New lead* ({$lead->score}/100): {$lead->name}, {$lead->phone}".($lead->company ? ", {$lead->company}" : '')."\nWants: ".($lead->services_label ?: '-')."\nSource: ".($lead->utm_source ?: 'direct').' · '.$lead->form_type,
                ]);
            }
        });

        $this->safely('outbound webhook', function () use ($lead) {
            if ($url = config('advertally.lead_webhook_url')) {
                Http::timeout(8)->post($url, [
                    'event' => 'lead.created',
                    'lead' => $lead->only([
                        'id', 'name', 'company', 'phone', 'email', 'city', 'website', 'business_size', 'industry',
                        'services', 'budget', 'message', 'form_type', 'source_page', 'utm_source', 'utm_medium',
                        'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid', 'score', 'created_at',
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
