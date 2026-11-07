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

class NewEnquiryCompanyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Enquiry $enquiry
    ) {}

    public function envelope(): Envelope
    {
        $fromEmail = config('mail.from.address', 'hello@winterfelltreks.com');
        $fromName = config('mail.from.name', 'Winterfell Treks & Expedition');

        $context = $this->enquiry->getContextName();
        $subject = "[New Enquiry #{$this->enquiry->reference_number}] {$this->enquiry->full_name} - {$context}";

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($this->enquiry->email, $this->enquiry->full_name)],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company_notification',
            with: [
                'enquiry' => $this->enquiry,
            ],
        );
    }
}

