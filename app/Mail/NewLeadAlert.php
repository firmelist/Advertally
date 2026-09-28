<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        $temp = strtoupper($this->lead->temperature);

        return new Envelope(
            subject: "[{$temp} {$this->lead->score}] New lead: {$this->lead->name}".($this->lead->company ? " – {$this->lead->company}" : ''),
            replyTo: $this->lead->email ? [$this->lead->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-lead-alert');
    }
}
