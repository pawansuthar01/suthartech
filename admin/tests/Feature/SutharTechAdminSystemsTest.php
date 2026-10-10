<?php

namespace Tests\Feature;

use App\Mail\NewsletterConfirmation;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SutharTechAdminSystemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_website_content_manager_remains_behind_existing_admin_login(): void
    {
        $this->get('/website-content/projects')->assertRedirect(route('admin.login'));
    }

    public function test_admin_panel_uses_root_and_pet_client_routes_are_not_served_here(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/admin')->assertStatus(301)->assertRedirect('/');
        $this->get('/login')->assertOk()->assertSee('SutharTech');
        $this->get('/adoption')->assertNotFound();
        $this->get('/vaccination-booking')->assertNotFound();
        $this->assertSame('/', route('admin.dashboard', absolute: false));
    }

    public function test_cities_management_page_is_removed_from_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/cities')
            ->assertNotFound();
    }

    public function test_local_admin_root_dashboard_does_not_block_on_its_client_api_callback(): void
    {
        Config::set('app.env', 'local');
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/services*' => Http::response([
            'data' => [], 'total' => 0,
        ])]);

        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee('Fixed Website Pages')
            ->assertSee('—');
    }

    public function test_newsletter_requires_confirmation_before_subscriber_is_eligible(): void
    {
        Mail::fake();
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        $issuedAt = now()->subSeconds(3)->timestamp;
        $this->postJson('/api/newsletter/subscribe', [
            'email' => 'Reader@Example.test',
            'website' => '',
            'bot_token' => $issuedAt.'.'.hash_hmac('sha256', (string) $issuedAt, 'test-only-private-token'),
        ])->assertAccepted();
        Mail::assertSent(NewsletterConfirmation::class, function (NewsletterConfirmation $mail): bool {
            $path = parse_url($mail->url, PHP_URL_PATH);
            $token = basename($path);
            $this->get('/newsletter/confirm/'.$token)->assertRedirect(config('services.suthartech_public_url').'/?newsletter=confirmed#newsletter');
            return true;
        });

        $subscriber = NewsletterSubscriber::where('email', 'reader@example.test')->firstOrFail();
        $this->assertNotNull($subscriber->confirmed_at);
        $this->assertNull($subscriber->confirmation_token);
        $this->assertSame(1, NewsletterSubscriber::whereNotNull('confirmed_at')->whereNull('unsubscribed_at')->count());
    }

    public function test_newsletter_bot_trap_does_not_create_subscribers_or_send_email(): void
    {
        Mail::fake();
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        $issuedAt = now()->subSeconds(3)->timestamp;

        $this->postJson('/api/newsletter/subscribe', [
            'email' => 'bot@example.test',
            'website' => 'spam-link.example',
            'bot_token' => $issuedAt.'.'.hash_hmac('sha256', (string) $issuedAt, 'test-only-private-token'),
        ])->assertAccepted();

        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'bot@example.test']);
        Mail::assertNothingOutgoing();
    }

    public function test_public_project_form_notifies_multiple_admins_by_email_and_whatsapp(): void
    {
        Mail::fake();
        Http::fake(['wa-api.suthartech.com/api/messages/send' => Http::response(['success' => true])]);
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        \App\Models\Setting::set('wa_enabled', '1');
        \App\Models\Setting::set('wa_api_key', 'test-wa-key');
        \App\Models\Setting::set('wa_session_key', 'test-session');
        \App\Models\Setting::set('admin_whatsapp_numbers', '+919876543210, +919812345678');
        \App\Models\Setting::set('form_admin_emails', 'owner@example.test; sales@example.test');
        \App\Models\Setting::set('mail_from_contact_address', 'contact@example.test');
        \App\Models\Setting::set('mail_from_sales_address', 'sales@example.test');

        $payload = [
            'source_id' => 'public-inquiry-test-1',
            'name' => 'Project Visitor',
            'email' => 'visitor@example.test',
            'phone' => '+919123456789',
            'company' => 'Example Company',
            'project_type' => 'Web platform',
            'budget' => 'Discuss after review',
            'message' => 'We need a project inquiry notification workflow.',
        ];

        $this->postJson('/api/internal/forms/contact', $payload)->assertUnauthorized();
        $this->withToken('test-only-private-token')->postJson('/api/internal/forms/contact', $payload)->assertAccepted();
        $this->withToken('test-only-private-token')->postJson('/api/internal/forms/contact', $payload)->assertAccepted();

        $this->assertDatabaseCount('contacts', 1);
        Mail::assertSent(\App\Mail\ProjectInquiryNotification::class, fn ($mail) =>
            $mail->hasTo('owner@example.test') && $mail->hasTo('sales@example.test') && ($mail->from[0]['address'] ?? null) === 'sales@example.test');
        Mail::assertSent(\App\Mail\ProjectInquiryAcknowledgement::class, fn ($mail) =>
            $mail->hasTo('visitor@example.test') && ($mail->from[0]['address'] ?? null) === 'contact@example.test');
        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => $request->url() === 'https://wa-api.suthartech.com/api/messages/send'
            && $request->hasHeader('x-api-key', 'test-wa-key')
            && $request['session'] === 'test-session'
            && str_starts_with($request['to'], '919'));
    }

    public function test_confirmed_newsletter_sends_a_welcome_email_and_admin_notice(): void
    {
        Mail::fake();
        $token = 'newsletter-confirm-token';
        \App\Models\NewsletterSubscriber::create([
            'email' => 'reader@example.test',
            'confirmation_token' => hash('sha256', $token),
            'unsubscribe_token' => 'reader-unsubscribe-token',
        ]);
        \App\Models\Setting::set('newsletter_admin_emails', 'marketing@example.test');
        \App\Models\Setting::set('mail_from_newsletter_address', 'noreply@example.test');

        $this->get('/newsletter/confirm/'.$token)->assertRedirect(config('services.suthartech_public_url').'/?newsletter=confirmed#newsletter');

        Mail::assertSent(\App\Mail\NewsletterWelcome::class, fn ($mail) =>
            $mail->hasTo('reader@example.test') && ($mail->from[0]['address'] ?? null) === 'noreply@example.test');
        Mail::assertSent(\App\Mail\NewsletterSignupNotification::class);
        $this->assertNotNull(\App\Models\NewsletterSubscriber::where('email', 'reader@example.test')->firstOrFail()->confirmed_at);
    }

    public function test_integrations_save_separate_mail_senders_admin_recipients_and_wa_only(): void
    {
        \App\Models\Setting::set('wa_api_key', 'keep-this-key');
        \App\Models\Setting::set('mail_password', 'keep-this-password');
        \App\Models\Setting::set('msg91_enabled', '1');
        \App\Models\Setting::set('recaptcha_enabled', '1');
        \App\Models\Setting::set('recaptcha_site_key', 'old-site-key');
        \App\Models\Setting::set('recaptcha_secret_key', 'old-secret-key');

        $this->actingAs(User::factory()->create())->put('/integrations', [
            'wa_enabled' => '1',
            'wa_api_key' => '',
            'wa_session_key' => '',
            'mail_host' => 'smtp.example.test',
            'mail_port' => '587',
            'mail_username' => 'smtp-user',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_contact_address' => 'contact@example.test',
            'mail_from_sales_address' => 'sales@example.test',
            'mail_from_newsletter_address' => 'noreply@example.test',
            'form_admin_emails' => "owner@example.test\nsales@example.test",
            'newsletter_admin_emails' => 'marketing@example.test',
            'admin_whatsapp_numbers' => "+919876543210\n+919812345678",
        ])->assertRedirect();

        $this->assertSame('keep-this-key', \App\Models\Setting::get('wa_api_key'));
        $this->assertSame('keep-this-password', \App\Models\Setting::get('mail_password'));
        $this->assertSame('0', \App\Models\Setting::get('msg91_enabled'));
        $this->assertSame('1', \App\Models\Setting::get('wa_enabled'));
        $this->assertSame('noreply@example.test', \App\Models\Setting::get('mail_from_newsletter_address'));
        $this->assertSame('marketing@example.test', \App\Models\Setting::get('newsletter_admin_emails'));
        $this->assertNull(\App\Models\Setting::get('recaptcha_enabled'));
        $this->assertNull(\App\Models\Setting::get('recaptcha_site_key'));
        $this->assertNull(\App\Models\Setting::get('recaptcha_secret_key'));
    }

    public function test_integrations_page_never_returns_saved_messaging_secrets(): void
    {
        \App\Models\Setting::set('wa_api_key', 'private-wa-key-value');
        \App\Models\Setting::set('wa_session_key', 'private-wa-session-value');
        \App\Models\Setting::set('mail_password', 'private-smtp-password-value');

        $this->actingAs(User::factory()->create())->get('/integrations')
            ->assertOk()
            ->assertSee('SMTP Email Delivery')
            ->assertSee('Form Admin Notifications')
            ->assertDontSee('Google reCAPTCHA')
            ->assertDontSee('private-wa-key-value')
            ->assertDontSee('private-wa-session-value')
            ->assertDontSee('private-smtp-password-value');
    }

    public function test_smtp_management_is_only_on_integrations_and_theme_customization_is_removed(): void
    {
        $this->actingAs(User::factory()->create())->get('/account')
            ->assertOk()
            ->assertDontSee('SMTP / Mail Settings')
            ->assertDontSee('Save SMTP Settings');

        $this->actingAs(User::factory()->create())->get('/settings')
            ->assertOk()
            ->assertDontSee('Theme Customization');

        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.account.update-smtp'));
    }

    public function test_existing_admin_session_can_read_public_project_manager_through_the_private_api(): void
    {
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/projects*' => Http::response([
            'data' => [], 'current_page' => 1, 'last_page' => 1,
        ])]);

        $this->actingAs(User::factory()->create())->get('/website-content/projects')
            ->assertOk()->assertSee('SutharTech Projects');
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-only-private-token'));
    }

    public function test_services_route_opens_the_public_database_service_manager(): void
    {
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/services*' => Http::response([
            'data' => [[
                'id' => 12,
                'title' => 'Database managed service',
                'slug' => 'database-managed-service',
                'description' => 'This service comes from the public database.',
                'status' => 'active',
                'sort_order' => 1,
            ]],
            'current_page' => 1,
            'last_page' => 1,
            'total' => 1,
        ])]);

        $this->actingAs(User::factory()->create())
            ->followingRedirects()
            ->get('/services')
            ->assertOk()
            ->assertSee('SutharTech Services')
            ->assertSee('Database managed service');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/services')
            && $request->hasHeader('Authorization', 'Bearer test-only-private-token'));
    }

    public function test_faq_manager_reads_from_the_public_content_store(): void
    {
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/faqs*' => Http::response([
            'data' => [['id' => 7, 'question' => 'What do you build?', 'answer' => 'Web products.', 'sort_order' => 1, 'status' => 'active']],
            'current_page' => 1, 'last_page' => 1,
        ])]);

        $this->actingAs(User::factory()->create())->get('/faqs')
            ->assertOk()->assertSee('What do you build?');
        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/faqs')
            && $request->hasHeader('Authorization', 'Bearer test-only-private-token'));
    }

    public function test_faq_manager_saves_active_faqs_to_the_public_content_store(): void
    {
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/faqs' => Http::response([
            'id' => 9, 'question' => 'How do I start?', 'answer' => 'Send a project brief.', 'page' => 'contact', 'status' => 'active', 'sort_order' => 1,
        ], 201)]);

        $this->actingAs(User::factory()->create())->post('/faqs', [
            'question' => 'How do I start?', 'answer' => 'Send a project brief.', 'page' => 'contact', 'sort_order' => 1, 'is_active' => '1',
        ])->assertRedirect(route('admin.faqs.index'));

        Http::assertSent(fn ($request) => $request->url() === 'https://client.test/api/internal/content/faqs'
            && $request->hasHeader('Authorization', 'Bearer test-only-private-token')
            && $request['status'] === 'active'
            && $request['page'] === 'contact');
    }

    public function test_blog_editor_forwards_image_alt_and_uploaded_cover_to_the_public_content_api(): void
    {
        Config::set('services.suthartech_content_url', 'https://client.test/api/internal/content');
        Config::set('services.suthartech_content_token', 'test-only-private-token');
        Http::fake(['client.test/api/internal/content/blogs' => Http::response([
            'id' => 42,
            'title' => 'Uploaded editorial article',
            'slug' => 'uploaded-editorial-article',
            'status' => 'draft',
        ], 201)]);

        $this->actingAs(User::factory()->create())
            ->post('/website-content/blogs', [
                'title' => 'Uploaded editorial article',
                'topic' => 'Engineering',
                'image_alt' => 'A diagram showing an editorial review workflow.',
                'excerpt' => 'A useful article summary.',
                'body' => 'A detailed article body.',
                'status' => 'draft',
                'image' => UploadedFile::fake()->image('editorial-cover.jpg'),
            ])
            ->assertRedirect(route('admin.website-content.index', 'blogs'));

        Http::assertSent(fn ($request) => $request->url() === 'https://client.test/api/internal/content/blogs'
            && $request->hasHeader('Authorization', 'Bearer test-only-private-token')
            && $request->hasFile('image')
            && str_contains($request->body(), 'image_alt')
            && str_contains($request->body(), 'A diagram showing an editorial review workflow.'));
    }
}
