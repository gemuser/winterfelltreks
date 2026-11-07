<?php

namespace App\Mail;

use App\Models\Enquiry;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class EnquiryStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Enquiry $enquiry,
        public ?string $oldStatus = null,
        public ?string $newStatus = null
    ) {
        $this->newStatus = $newStatus ?: $enquiry->status;
    }

    public function envelope(): Envelope
    {
        $fromEmail = SiteSetting::getValue('email_primary') ?: config('mail.from.address', 'hello@winterfelltreks.com');
        $fromName = config('mail.from.name', 'Winterfell Treks & Expedition');

        $statusLabels = [
            'new' => 'Received',
            'under_review' => 'Under Review',
            'contacted' => 'Contacted',
            'quotation_sent' => 'Quotation Sent',
            'follow_up_required' => 'Follow-up Required',
            'closed' => 'Completed',
        ];
        $label = $statusLabels[$this->newStatus] ?? ucfirst(str_replace('_', ' ', $this->newStatus));

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: "Update on your enquiry [{$this->enquiry->reference_number}] - {$label} | Winterfell Treks",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.status_updated',
            with: [
                'enquiry' => $this->enquiry,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
            ],
        );
    }
}

