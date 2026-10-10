<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FormBotProtection;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        if (! FormBotProtection::passes($request)) {
            return back()->with('newsletter_success', 'Please check your inbox to confirm your subscription.');
        }

        $data = $request->validate(['email' => ['required', 'email:rfc', 'max:190']]);
        try {
            Http::timeout(10)->post(config('services.newsletter_url'), $data + [
                'bot_token' => $request->input('bot_token'),
                'website' => $request->input('website') ?? '',
            ])->throw();
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('newsletter_error', 'We could not send a confirmation email right now. Please try again later.');
        }

        return back()->with('newsletter_success', 'Please check your inbox to confirm your subscription.');
    }
}
