<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewLeadAlert extends Mailable
{
    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New {$this->lead->label('form_type')} lead: {$this->lead->name}".($this->lead->company ? " ({$this->lead->company})" : ''),
            replyTo: [new Address($this->lead->email, $this->lead->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-lead-alert');
    }
}
