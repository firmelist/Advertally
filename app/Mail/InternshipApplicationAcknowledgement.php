<?php

namespace App\Mail;

use App\Models\InternshipApplication;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InternshipApplicationAcknowledgement extends Mailable
{
    public function __construct(public InternshipApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your application — Advertally');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.internship-acknowledgement');
    }
}
