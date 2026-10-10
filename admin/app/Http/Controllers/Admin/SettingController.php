<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Services\WaaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'site_name',
            'site_tagline',
            'site_email',
            'site_phone',
            'site_address',
            'default_meta_title',
            'default_meta_description',
            'default_meta_keywords',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'whatsapp_number',

        ];


        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('site_logo', $filename);
        }

        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('site_favicon', $filename);
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function testRazorpay(Request $request)
    {
        $keyId = Setting::get('razorpay_key_id', '');
        $keySecret = Setting::get('razorpay_key_secret', '');

        if (!$keyId || !$keySecret) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay API keys are not configured.',
            ]);
        }

        try {
            // Test API connection with a minimal request
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($keyId, $keySecret)
                ->get('https://api.razorpay.com/v1/items');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Razorpay API connection successful!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API connection failed: ' . $response->status() . ' - ' . $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function integrations()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.integrations', compact('settings'));
    }

    public function updateIntegrations(Request $request)
    {
        $emailList = function (string $attribute, mixed $value, \Closure $fail): void {
            foreach (preg_split('/[,;\s]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $email) {
                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $fail("{$email} is not a valid email address.");
                }
            }
        };
        $phoneList = function (string $attribute, mixed $value, \Closure $fail): void {
            foreach (preg_split('/[,;\r\n]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $phone) {
                if (! preg_match('/^\+?[0-9 ()-]{10,20}$/', trim($phone))) {
                    $fail("{$phone} is not a valid WhatsApp number.");
                }
            }
        };

        $request->validate([
            'tinymce_api_key' => 'nullable|string|max:255',
            'tiny_drive_api_key' => 'nullable|string|max:255',
            'extra_api_settings' => 'nullable|string',
            'razorpay_key_id' => 'nullable|string|max:255',
            'razorpay_key_secret' => 'nullable|string|max:255',
            'wa_api_key' => 'nullable|string|max:255',
            'wa_session_key' => 'nullable|string|max:255',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|between:1,65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,starttls',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_contact_address' => 'nullable|email|max:255',
            'mail_from_sales_address' => 'nullable|email|max:255',
            'mail_from_newsletter_address' => 'nullable|email|max:255',
            'form_admin_emails' => ['nullable', 'string', 'max:2000', $emailList],
            'newsletter_admin_emails' => ['nullable', 'string', 'max:2000', $emailList],
            'admin_whatsapp_numbers' => ['nullable', 'string', 'max:2000', $phoneList],
            'suthartech_captcha_enabled' => ['nullable', 'boolean'],
        ]);

        $fields = [
            'tinymce_api_key', 'tiny_drive_api_key', 'extra_api_settings',
            'razorpay_key_id',
            'mail_host', 'mail_port', 'mail_username', 'mail_encryption',
            'mail_from_address', 'mail_from_name', 'mail_from_contact_address',
            'mail_from_sales_address', 'mail_from_newsletter_address',
            'form_admin_emails', 'newsletter_admin_emails', 'admin_whatsapp_numbers',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $value = trim((string) $request->input($field, ''));
                Setting::set($field, $field === 'mail_encryption' && $value === 'starttls' ? 'tls' : $value);
            }
        }
        foreach (['razorpay_key_secret', 'wa_api_key', 'wa_session_key', 'mail_password'] as $secret) {
            if ($request->filled($secret)) {
                Setting::set($secret, $request->input($secret));
            }
        }

        Setting::set('wa_enabled', $request->boolean('wa_enabled') ? '1' : '0');
        Setting::set('suthartech_captcha_enabled', $request->boolean('suthartech_captcha_enabled') ? '1' : '0');
        Setting::set('msg91_enabled', '0');
        Setting::set('wa_api_url', 'https://wa-api.suthartech.com/api/messages/send');
        Setting::whereIn('key', ['recaptcha_enabled', 'recaptcha_site_key', 'recaptcha_secret_key'])->delete();

        return back()->with('success', 'API & Integrations settings updated successfully!');
    }

    public function testWhatsApp(Request $request, WaaService $whatsapp)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9 ()-]{10,20}$/'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);
        abort_unless($whatsapp->isEnabled(), 422, 'Enable WhatsApp and save its API key and session first.');

        $sent = $whatsapp->sendToPhone($data['phone'], $data['message'] ?: 'SutharTech WhatsApp integration test.', 'SutharTech Admin');

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'WhatsApp test message sent.' : 'WhatsApp provider could not send the test message. Check the session and API key.',
        ], $sent ? 200 : 502);
    }

    // ──────────────────────────────────────────────
    // Admin Account (email and password)
    // ──────────────────────────────────────────────
    public function account()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.account', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Current password is incorrect.'])->withInput();
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('email_success', 'Login email updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', 'Password changed successfully.');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notification_email' => [
                'nullable',
                'string',
                'max:1000',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;
                    $emails = array_map('trim', explode(',', $value));
                    foreach ($emails as $email) {
                        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("\"$email\" is not a valid email address.");
                        }
                    }
                },
            ],
        ]);

        Setting::set('notification_email', trim($request->input('notification_email', '')));

        return back()->with('notif_success', 'Notification email(s) saved successfully.');
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from SutharTech SMTP configuration.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('SMTP Test Email - SutharTech');
            });

            return back()->with('test_mail_success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('test_mail_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

}
