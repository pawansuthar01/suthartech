<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificationAnnouncement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $certification, public NewsletterSubscriber $subscriber) {}

    public function build(): self
    {
        return $this->subject('SutharTech certification: '.$this->certification['title'])->view('emails.certification-announcement', [
            'url' => rtrim(config('services.suthartech_public_url'), '/').'/certifications/'.$this->certification['slug'],
            'unsubscribeUrl' => rtrim(config('services.suthartech_admin_url'), '/').'/newsletter/unsubscribe/'.$this->subscriber->unsubscribe_token,
        ]);
    }
}
