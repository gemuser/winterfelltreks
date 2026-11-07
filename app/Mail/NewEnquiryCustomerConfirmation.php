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

class NewEnquiryCustomerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Enquiry $enquiry
    ) {}

    public function envelope(): Envelope
    {
        $fromEmail = SiteSetting::getValue('email_primary') ?: config('mail.from.address', 'hello@winterfelltreks.com');
        $fromName = config('mail.from.name', 'Winterfell Treks & Expedition');

        $subject = SiteSetting::getValue('enquiry_auto_reply_subject')
            ?: "We received your enquiry [{$this->enquiry->reference_number}] - Winterfell Treks & Expedition";

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_confirmation',
            with: [
                'enquiry' => $this->enquiry,
            ],
        );
    }
}

