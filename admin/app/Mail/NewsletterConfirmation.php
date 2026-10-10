<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $url) {}

    public function build(): self
    {
        return $this->subject('Confirm your SutharTech updates')->view('emails.newsletter-confirmation');
    }
}
