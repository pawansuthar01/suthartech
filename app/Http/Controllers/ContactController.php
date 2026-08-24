<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactInquiryRequest;
use App\Mail\ContactInquiryReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(ContactInquiryRequest $request): RedirectResponse
    {
        $inquiry = $request->validated();
        $recipient = config('mail.from.address');

        if ($recipient) {
            Mail::to($recipient)->send(new ContactInquiryReceived($inquiry));
        }

        return to_route('contact')->with('success', 'Thanks. Your project brief has been received and we will be in touch shortly.');
    }
}
