<?php

namespace App\Jobs;

use App\Mail\InternshipApplicationAcknowledgement;
use App\Mail\InternshipApplicationReceived;
use App\Models\InternshipApplication;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotifyInternshipApplication implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public InternshipApplication $application) {}

    public function handle(): void
    {
        $application = $this->application->loadMissing('internship');

        $this->safely('team email', function () use ($application) {
            $to = array_filter(array_map('trim', explode(',', (string) (setting('careers_email') ?: implode(',', config('advertally.lead_alert_emails'))))));
            if ($to) {
                Mail::to($to)->send(new InternshipApplicationReceived($application));
            }
        });

        $this->safely('acknowledgement', fn () => Mail::to($application->email, $application->name)->send(new InternshipApplicationAcknowledgement($application)));

        $this->safely('admin notification', function () use ($application) {
            $recipients = User::query()->where('is_active', true)->get()->filter(fn (User $u) => $u->hasPermission('applications.view'));
            Notification::make()
                ->title("New internship application: {$application->name}")
                ->body($application->internship?->title ?? 'Internship')
                ->icon('heroicon-o-academic-cap')
                ->actions([Action::make('view')->url(url('/admin/internship-applications'))->markAsRead()])
                ->sendToDatabase($recipients);
        });
    }

    private function safely(string $channel, callable $fn): void
    {
        try {
            $fn();
        } catch (Throwable $e) {
            Log::warning("Internship application #{$this->application->id}: {$channel} failed — {$e->getMessage()}");
        }
    }
}
