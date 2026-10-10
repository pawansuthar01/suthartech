<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterPromotion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $subjectLine, public string $bodyText, public NewsletterSubscriber $subscriber) {}

    public function build(): self
    {
        $url = rtrim(config('services.suthartech_admin_url'), '/').'/newsletter/unsubscribe/'.$this->subscriber->unsubscribe_token;
        return $this->subject($this->subjectLine)->view('emails.newsletter-promotion', ['unsubscribeUrl' => $url]);
    }
}
