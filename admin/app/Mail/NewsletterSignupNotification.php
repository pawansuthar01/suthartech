<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSignupNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $email) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'A reader confirmed SutharTech updates');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter-signup-notification');
    }
}
