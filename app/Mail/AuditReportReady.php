<?php

namespace App\Mail;

use App\Models\AuditReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuditReportReady extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AuditReport $report) {}

    public function envelope(): Envelope
    {
        $host = parse_url($this->report->url, PHP_URL_HOST);

        return new Envelope(subject: "Your website audit for {$host}: {$this->report->score}/100");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.audit-report');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => Pdf::loadView('pdf.audit-report', ['report' => $this->report])->setPaper('a4')->output(),
                'website-audit-'.parse_url($this->report->url, PHP_URL_HOST).'.pdf',
            )->withMime('application/pdf'),
        ];
    }
}
