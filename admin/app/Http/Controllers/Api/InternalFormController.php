<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ProjectInquiryAcknowledgement;
use App\Mail\ProjectInquiryNotification;
use App\Models\Contact;
use App\Services\MailService;
use App\Services\WaaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InternalFormController extends Controller
{
    public function protectionSettings(): JsonResponse
    {
        return response()->json([
            'bot_protection_enabled' => \App\Models\Setting::get('suthartech_captcha_enabled', '1') === '1',
        ]);
    }

    public function contact(Request $request, WaaService $whatsapp): JsonResponse
    {
        $data = $request->validate([
            'source_id' => ['required', 'string', 'max:80'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'project_type' => ['required', 'string', 'max:100'],
            'budget' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contact = Contact::firstOrCreate(['source_id' => $data['source_id']], [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => 'Project inquiry: '.$data['project_type'].($data['company'] ? ' — '.$data['company'] : ''),
            'message' => collect([
                'Project type: '.$data['project_type'],
                $data['company'] ? 'Company: '.$data['company'] : null,
                $data['budget'] ? 'Budget: '.$data['budget'] : null,
                'Project brief:',
                $data['message'],
            ])->filter()->implode("\n"),
        ]);

        if ($contact->wasRecentlyCreated) {
            $adminEmails = MailService::configuredRecipients('form_admin_emails', 'notification_email', 'site_email');
            $adminEmailSent = MailService::deliver(new ProjectInquiryNotification($contact), $adminEmails, 'sales');
            $customerEmailSent = MailService::deliver(
                new ProjectInquiryAcknowledgement($data['name'], $data['project_type']),
                $data['email'],
                'contact'
            );
            $whatsappSent = $whatsapp->notifyAdmins(
                "New SutharTech project inquiry\n{$data['name']} — {$data['project_type']}\n{$data['email']}\n".($data['phone'] ?: 'No phone provided'),
                $data['name']
            );

            Log::info('Project inquiry notification attempt completed.', [
                'contact_id' => $contact->id,
                'admin_email_sent' => $adminEmailSent,
                'customer_email_sent' => $customerEmailSent,
                'admin_whatsapp_count' => $whatsappSent,
            ]);
        }

        return response()->json(['accepted' => true], 202);
    }

}
