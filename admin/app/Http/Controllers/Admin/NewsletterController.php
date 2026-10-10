<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterConfirmation;
use App\Mail\NewsletterPromotion;
use App\Mail\NewsletterSignupNotification;
use App\Mail\NewsletterWelcome;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\MailService;
use App\Services\FormBotProtection;
use App\Services\WaaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function __construct(private readonly WaaService $whatsapp) {}

    public function index(): View
    {
        return view('admin.newsletter.index', [
            'subscribers' => NewsletterSubscriber::latest()->paginate(20),
            'confirmedCount' => NewsletterSubscriber::whereNotNull('confirmed_at')->whereNull('unsubscribed_at')->count(),
            'campaigns' => NewsletterCampaign::latest()->limit(20)->get(),
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        if (! FormBotProtection::passes($request)) {
            return response()->json(['message' => 'Confirmation email sent.'], 202);
        }

        $email = Str::lower($request->validate(['email' => ['required', 'email:rfc', 'max:190']])['email']);
        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);
        if ($subscriber->confirmed_at && ! $subscriber->unsubscribed_at) {
            return response()->json(['message' => 'Already subscribed.'], 202);
        }

        $token = Str::random(64);
        $subscriber->forceFill([
            'confirmation_token' => hash('sha256', $token),
            'unsubscribe_token' => $subscriber->unsubscribe_token ?: Str::random(64),
            'confirmed_at' => null,
            'unsubscribed_at' => null,
        ])->save();
        $url = rtrim(config('services.suthartech_admin_url'), '/').'/newsletter/confirm/'.$token;
        if (! MailService::deliver(new NewsletterConfirmation($url), $email, 'newsletter')) {
            return response()->json(['message' => 'Email could not be sent. Please try again later.'], 503);
        }

        return response()->json(['message' => 'Confirmation email sent.'], 202);
    }

    public function confirm(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', hash('sha256', $token))->firstOrFail();
        $subscriber->forceFill(['confirmation_token' => null, 'confirmed_at' => now()])->save();

        MailService::deliver(new NewsletterWelcome($subscriber), $subscriber->email, 'newsletter');
        $adminEmails = MailService::configuredRecipients('newsletter_admin_emails', 'form_admin_emails', 'notification_email', 'site_email');
        MailService::deliver(new NewsletterSignupNotification($subscriber->email), $adminEmails, 'sales');
        $this->whatsapp->notifyAdmins("New confirmed SutharTech newsletter subscriber\n{$subscriber->email}", 'SutharTech');

        return redirect(rtrim(config('services.suthartech_public_url'), '/').'/?newsletter=confirmed#newsletter');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $subscriber->forceFill(['unsubscribed_at' => now()])->save();

        return redirect(rtrim(config('services.suthartech_public_url'), '/').'/?newsletter=unsubscribed#newsletter');
    }

    public function campaign(Request $request): RedirectResponse
    {
        $data = $request->validate(['subject' => ['required', 'string', 'max:190'], 'body' => ['required', 'string', 'max:10000']]);
        $subscribers = NewsletterSubscriber::whereNotNull('confirmed_at')->whereNull('unsubscribed_at')->get();
        abort_if($subscribers->isEmpty(), 422, 'There are no confirmed subscribers.');
        $campaign = NewsletterCampaign::create(['subject' => $data['subject'], 'body' => $data['body'], 'status' => 'sending']);
        $sent = 0;
        $failed = 0;
        // ponytail: delivery is synchronous; move to queued jobs if confirmed lists start exceeding request timeouts.
        foreach ($subscribers as $subscriber) {
            if (MailService::deliver(new NewsletterPromotion($data['subject'], $data['body'], $subscriber), $subscriber->email, 'newsletter')) {
                $sent++;
            } else {
                Log::warning('Newsletter campaign delivery failed.', ['campaign_id' => $campaign->id, 'subscriber_id' => $subscriber->id]);
                $failed++;
            }
        }
        $campaign->update(['sent_count' => $sent, 'failed_count' => $failed, 'status' => $failed ? 'partial' : 'sent', 'sent_at' => now()]);

        return to_route('admin.newsletter.index')->with('success', "Campaign finished: {$sent} sent, {$failed} failed.");
    }
}
