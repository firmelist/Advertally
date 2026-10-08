<?php

namespace App\Mail;

use App\Models\InternshipApplication;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Team alert. The résumé is not attached — it stays in private storage and is downloaded from the admin. */
class InternshipApplicationReceived extends Mailable
{
    public function __construct(public InternshipApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New internship application: '.$this->application->name.' — '.($this->application->internship?->title ?? 'Internship'),
            replyTo: [new Address($this->application->email, $this->application->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.internship-application');
    }
}
