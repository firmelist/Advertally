<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class LeadAcknowledgement extends Mailable
{
    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We have your request — next steps from Advertally');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.lead-acknowledgement');
    }
}
