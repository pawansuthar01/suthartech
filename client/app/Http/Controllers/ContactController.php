<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactInquiryRequest;
use App\Models\ContactInquiry;
use App\Services\FormBotProtection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(ContactInquiryRequest $request): RedirectResponse|JsonResponse
    {
        if (! FormBotProtection::passes($request)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true], 202);
            }

            return to_route('contact')->with('success', 'Thanks. Your project brief has been received and we will be in touch shortly.');
        }

        $inquiry = $request->validated();
        $isCallRequest = ($inquiry['form_type'] ?? null) === 'call_request';
        if ($isCallRequest) {
            $inquiry['message'] = sprintf(
                'Requested a call on %s between %s and %s.',
                date('F j, Y', strtotime($inquiry['call_date'])),
                date('g:i A', strtotime($inquiry['call_start'])),
                date('g:i A', strtotime($inquiry['call_end']))
            );
            unset($inquiry['form_type'], $inquiry['call_date'], $inquiry['call_start'], $inquiry['call_end']);
        }

        $inquiry = array_merge(['phone' => null, 'company' => null, 'budget' => null], $inquiry);
        $attachment = $request->file('attachment')?->store('contact-inquiries', 'local');
        unset($inquiry['attachment']);
        $record = ContactInquiry::create($inquiry + ['attachment_path' => $attachment]);
        try {
            Http::acceptJson()->withToken((string) config('services.internal_content_token'))
                ->timeout(30)
                ->post(config('services.forms_notification_url'), $inquiry + [
                    'source_id' => 'public-inquiry-'.$record->id,
                ])
                ->throw();
        } catch (\Throwable $exception) {
            report($exception);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'We saved your request, but could not notify the team. Please try again shortly.',
                ], 503);
            }

            return to_route('contact')->withInput()->withErrors([
                'contact' => 'We saved your project brief, but could not notify the team. Please retry in a moment.',
            ]);
        }

        $message = $isCallRequest
            ? 'Thanks. Your preferred call time has been sent to our team.'
            : 'Thanks. Your project brief has been received and we will be in touch shortly.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message], 201);
        }

        return to_route('contact')->with('success', $message);
    }
}
