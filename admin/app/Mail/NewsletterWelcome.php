<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to SutharTech updates');
    }

    public function content(): Content
    {
        $unsubscribeUrl = rtrim(config('services.suthartech_admin_url'), '/').'/newsletter/unsubscribe/'.$this->subscriber->unsubscribe_token;

        return new Content(view: 'emails.newsletter-welcome', with: compact('unsubscribeUrl'));
    }
}
