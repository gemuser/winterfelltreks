<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class AdminLoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otpCode,
        public ?string $ipAddress = null
    ) {}

    public function envelope(): Envelope
    {
        $fromEmail = SiteSetting::getValue('email_primary') ?: config('mail.from.address', 'winterfelltreks@gmail.com');
        $fromName = config('mail.from.name', 'Winterfell Treks & Expedition');

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: "Your Login Verification Code: [{$this->otpCode}] - Winterfell Treks",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.login_otp',
            with: [
                'user' => $this->user,
                'otpCode' => $this->otpCode,
                'ipAddress' => $this->ipAddress,
            ],
        );
    }
}

