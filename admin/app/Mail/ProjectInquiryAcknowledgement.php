<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectInquiryAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $name, public string $projectType) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your SutharTech project brief');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.project-inquiry-acknowledgement');
    }
}
