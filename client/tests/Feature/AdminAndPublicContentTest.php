<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use App\Models\SitePage;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAndPublicContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_public_routes_render_without_demo_records(): void
    {
        foreach (['/', '/about', '/services', '/projects', '/testimonials', '/contact', '/blogs', '/certifications'] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/')->assertSee(asset('css/home.css'));
        $this->get('/contact')->assertSee(asset('css/contact.css'));
        $this->get('/testimonials')->assertSee(asset('css/testimonials.css'));
        $this->get('/')->assertDontSee('Premium Platform - LLM Pipeline');
        $this->get('/testimonials')->assertDontSee('Rohan Mehta');
    }

    public function test_homepage_shows_only_published_featured_projects(): void
    {
        Project::create(['title' => 'Published project', 'slug' => 'published-project', 'category' => 'Product', 'summary' => 'Verified project copy.', 'is_featured' => true, 'status' => 'published']);
        Project::create(['title' => 'Draft project', 'slug' => 'draft-project', 'category' => 'Preview', 'summary' => 'Hidden draft.', 'is_featured' => true, 'status' => 'draft']);

        $this->get('/')->assertOk()->assertSee('Published project')->assertDontSee('Draft project');
    }

    public function test_homepage_services_come_from_published_database_records(): void
    {
        Service::create([
            'title' => 'Database managed service',
            'slug' => 'database-managed-service',
            'icon' => 'database',
            'description' => 'This service is controlled from the database.',
            'status' => 'active',
        ]);
        Service::create([
            'title' => 'Private service draft',
            'slug' => 'private-service-draft',
            'icon' => 'lock',
            'description' => 'This draft remains private.',
            'status' => 'draft',
        ]);

        $this->get('/')->assertOk()
            ->assertSee('Database managed service')
            ->assertSee(route('service.show', 'database-managed-service'))
            ->assertDontSee('Private service draft')
            ->assertDontSee('Web Engineering');
    }

    public function test_project_and_service_demo_seeder_is_idempotent(): void
    {
        Artisan::call('db:seed', ['--class' => \Database\Seeders\ProjectServiceDemoSeeder::class]);
        Artisan::call('db:seed', ['--class' => \Database\Seeders\ProjectServiceDemoSeeder::class]);

        $this->assertDatabaseCount('services', count(config('agency.services')));
        $this->assertDatabaseCount('projects', 12);
        $this->assertDatabaseHas('projects', [
            'slug' => 'saas-operations-dashboard',
            'status' => 'active',
            'category' => 'Concept · SaaS platform',
        ]);
    }

    public function test_homepage_shows_only_active_faqs_before_the_project_contact_cta(): void
    {
        Faq::create(['question' => 'How do I start?', 'answer' => 'Send us a project brief.', 'status' => 'active', 'sort_order' => 1]);
        Faq::create(['question' => 'Draft answer?', 'answer' => 'This stays private.', 'status' => 'draft', 'sort_order' => 2]);

        $this->get('/')->assertOk()
            ->assertSee('How do I start?')
            ->assertSee('Start a project')
            ->assertDontSee('Draft answer?')
            ->assertSee(route('contact'));
    }

    public function test_contact_page_shows_only_its_active_faqs(): void
    {
        Faq::create(['page' => 'home', 'question' => 'Home-only question?', 'answer' => 'Home-only answer.', 'status' => 'active']);
        Faq::create(['page' => 'contact', 'question' => 'What should my brief include?', 'answer' => 'Share goals, users, current stage, and constraints.', 'status' => 'active']);
        Faq::create(['page' => 'contact', 'question' => 'Private contact draft?', 'answer' => 'This stays private.', 'status' => 'draft']);

        $this->get('/contact')->assertOk()
            ->assertSee('Project questions, answered.')
            ->assertSee('What should my brief include?')
            ->assertSee('Share goals, users, current stage, and constraints.')
            ->assertDontSee('Home-only question?')
            ->assertDontSee('Private contact draft?');
    }

    public function test_blog_index_has_recent_and_topic_navigation_and_filters_articles(): void
    {
        Blog::create([
            'title' => 'Engineering patterns for product teams', 'slug' => 'engineering-patterns',
            'topic' => 'product-engineering', 'excerpt' => 'A practical engineering article.', 'body' => 'Engineering article content.',
            'status' => 'active', 'published_at' => now(), 'image_path' => 'images/project-telemetry-dashboard.png',
        ]);
        Blog::create([
            'title' => 'Building a reliable API', 'slug' => 'reliable-api',
            'topic' => 'laravel', 'excerpt' => 'A practical API article.', 'body' => 'API article content.',
            'status' => 'active', 'published_at' => now()->subDay(),
        ]);
        Blog::create([
            'title' => 'AI product evaluation', 'slug' => 'ai-product-evaluation',
            'topic' => 'ai-data', 'excerpt' => 'A practical AI article.', 'body' => 'AI article content.',
            'status' => 'active', 'published_at' => now()->subDays(2),
        ]);

        $this->get('/blogs')
            ->assertOk()
            ->assertSee('SutharTech Blog')
            ->assertSee('Recent')
            ->assertSee('Topics')
            ->assertSee('Product Engineering')
            ->assertSee('Laravel')
            ->assertSee('AI &amp; Data', false)
            ->assertSee('images/project-telemetry-dashboard.png')
            ->assertSee('Building a reliable API');

        $this->get('/blogs?topic=product-engineering')
            ->assertOk()
            ->assertViewHas('activeTopic', 'product-engineering')
            ->assertViewHas('blogs', fn ($blogs) => $blogs->total() === 1 && $blogs->first()->slug === 'engineering-patterns');
    }

    public function test_blog_pagination_shows_first_two_and_last_two_pages_for_a_long_feed(): void
    {
        foreach (range(1, 54) as $number) {
            Blog::create([
                'title' => "Pagination article {$number}", 'slug' => "pagination-article-{$number}",
                'topic' => 'engineering', 'excerpt' => 'A pagination test article.', 'body' => 'Test content.',
                'status' => 'active', 'published_at' => now()->subDays($number),
            ]);
        }

        $this->get('/blogs')
            ->assertOk()
            ->assertViewHas('blogs', fn ($blogs) => $blogs->total() === 54 && $blogs->lastPage() === 6)
            ->assertSee('…', false)
            ->assertSee(route('blogs', ['page' => 5]), false)
            ->assertSee(route('blogs', ['page' => 6]), false);
    }

    public function test_public_certificate_directory_and_id_lookup_show_published_records_only(): void
    {
        $published = Certification::create([
            'title' => 'Application Security Certificate',
            'slug' => 'application-security-certificate',
            'issuer' => 'Example Standards Board',
            'credential_id' => 'CERT-VERIFY-123',
            'issued_at' => '2026-01-15',
            'expires_at' => '2027-01-15',
            'status' => 'published',
        ]);
        Certification::create([
            'title' => 'Unpublished draft certificate',
            'slug' => 'unpublished-draft-certificate',
            'issuer' => 'Private issuer',
            'credential_id' => 'DRAFT-123',
            'status' => 'draft',
        ]);

        $this->get('/certifications')
            ->assertOk()
            ->assertSee('Certificates you can verify.')
            ->assertSee('CERT-VERIFY-123')
            ->assertDontSee('DRAFT-123');

        $this->get(route('certifications.verify', ['credential_id' => ' cert-verify-123 ']))
            ->assertRedirect(route('certification.show', $published->slug));

        $this->get(route('certifications.verify', ['credential_id' => 'missing-id']))
            ->assertOk()
            ->assertSee('No published certificate matched that certificate ID.')
            ->assertSee('missing-id');

        $this->get(route('certification.show', 'unpublished-draft-certificate'))->assertNotFound();
    }

    public function test_issuer_image_replaces_generated_certificate_preview_on_detail_page(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certifications/issuer-document.jpg', 'issuer evidence');
        Certification::create([
            'title' => 'Verified security certificate',
            'slug' => 'verified-security-certificate',
            'issuer' => 'Issuer Organization',
            'credential_id' => 'VERIFIED-SEC-001',
            'credential_url' => 'https://issuer.example/verify/VERIFIED-SEC-001',
            'issued_at' => '2026-01-15',
            'image_path' => 'certifications/issuer-document.jpg',
            'status' => 'active',
        ]);

        $this->get(route('certification.show', 'verified-security-certificate'))
            ->assertOk()
            ->assertSee('/storage/certifications/issuer-document.jpg', false)
            ->assertDontSee('certificate-preview');
    }

    public function test_seeded_editorials_and_sample_certificates_are_database_content_for_testing(): void
    {
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BlogCertificateDemoSeeder']);

        $this->assertSame(60, Blog::published()->count());
        $this->assertSame(6, Certification::published()->count());
        $blog = Blog::where('slug', 'a-safer-image-upload-workflow-in-laravel')->firstOrFail();
        $this->assertFileExists(public_path($blog->image_path));
        $this->get(route('blog.show', $blog->slug))
            ->assertOk()
            ->assertSee('A Safer Image Upload Workflow in Laravel')
            ->assertSee('laravel.com/docs/filesystem');
        $this->get(route('certifications'))
            ->assertOk()
            ->assertSee('SAMPLE-CERT-001')
            ->assertSee('Sample: Secure Application Development')
            ->assertSee('certificate-preview')
            ->assertSee('SAMPLE FOR TESTING · NOT AN ISSUED CERTIFICATE')
            ->assertDontSee('No company certifications published yet');
        $this->get(route('certification.show', 'sample-secure-application-development'))
            ->assertOk()
            ->assertSee('credential-detail-preview')
            ->assertSee('SAMPLE FOR TESTING · NOT AN ISSUED CERTIFICATE');
    }

    public function test_private_content_api_persists_uploaded_blog_image_alt_and_public_image_url(): void
    {
        Storage::fake('public');
        Config::set('services.internal_content_token', 'test-only-private-token');

        $response = $this->withToken('test-only-private-token')->post('/api/internal/content/blogs', [
            'title' => 'Uploaded image workflow',
            'slug' => 'uploaded-image-workflow',
            'topic' => 'Engineering',
            'excerpt' => 'A database-managed article.',
            'body' => 'The article content comes from the database.',
            'image_alt' => 'A review screen with a selected article cover.',
            'image' => UploadedFile::fake()->image('article-cover.jpg'),
            'status' => 'active',
        ])->assertCreated();

        $blog = Blog::where('slug', 'uploaded-image-workflow')->firstOrFail();
        $this->assertSame('A review screen with a selected article cover.', $blog->image_alt);
        Storage::disk('public')->assertExists($blog->image_path);
        $this->get(route('blog.show', $blog->slug))
            ->assertOk()
            ->assertSee('A review screen with a selected article cover.')
            ->assertSee('/storage/blogs/', false);
        $this->assertSame($blog->image_path, $response->json('image_path'));
    }

    public function test_active_certification_requires_issuer_identity_date_and_evidence(): void
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        $payload = [
            'title' => 'Organization security certificate',
            'issuer' => 'Issuer Organization',
            'credential_id' => 'ORG-SEC-2026',
            'issued_at' => '2026-01-01',
            'status' => 'active',
        ];

        $this->withToken('test-only-private-token')->postJson('/api/internal/content/certifications', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');

        $this->withToken('test-only-private-token')->postJson('/api/internal/content/certifications', $payload + [
            'credential_url' => 'https://issuer.example.test/verify/ORG-SEC-2026',
        ])->assertCreated();
        $this->assertDatabaseHas('certifications', ['credential_id' => 'ORG-SEC-2026', 'status' => 'active']);
    }

    public function test_draft_pages_and_projects_are_omitted_from_public_routes_and_sitemap(): void
    {
        SitePage::create(['title' => 'About draft', 'slug' => 'about', 'hero_title' => 'Hidden about copy', 'status' => 'draft']);
        SitePage::create(['title' => 'Privacy', 'slug' => 'privacy', 'hero_title' => 'Privacy information', 'body' => 'Published page content.', 'status' => 'published']);
        Project::create(['title' => 'Hidden project', 'slug' => 'hidden-project', 'category' => 'Preview', 'summary' => 'Not published.', 'status' => 'draft']);
        Testimonial::create(['client_name' => 'Hidden feedback', 'quote' => 'Not published.', 'status' => 'draft']);

        $this->get('/about')->assertDontSee('Hidden about copy');
        $this->get('/privacy')->assertOk()->assertSee('Privacy information');
        $this->get('/projects')->assertDontSee('Hidden project');
        $this->get('/sitemap.xml')->assertOk()->assertSee('/privacy')->assertDontSee('hidden-project')->assertDontSee('Hidden about copy');

        $project = Project::create(['title' => 'Published project', 'slug' => 'published-project', 'category' => 'Product', 'summary' => 'Verified project copy.', 'status' => 'published']);
        $service = Service::create(['title' => 'Published service', 'slug' => 'published-service', 'description' => 'Verified service copy.', 'status' => 'published']);
        $certification = Certification::create(['title' => 'Published certificate', 'slug' => 'published-certificate', 'issuer' => 'Verified issuer', 'status' => 'published']);

        $this->get(route('project.show', $project->slug))->assertSee('Verified project copy.');
        $this->get(route('service.show', $service->slug))->assertSee('Verified service copy.');
        $this->get(route('certification.show', $certification->slug))->assertSee('Verified issuer');
    }

    public function test_contact_form_saves_an_inquiry_for_admin_review(): void
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        Config::set('services.forms_notification_url', 'https://admin.test/api/internal/forms/contact');
        Http::fake(['admin.test/api/internal/forms/contact' => Http::response(['accepted' => true], 202)]);
        $this->get('/contact')
            ->assertOk()
            ->assertSee('name="bot_token"', false)
            ->assertSee('name="website"', false)
            ->assertDontSee('google.com/recaptcha');

        $this->post('/contact', $this->withBotProtection([
            'name' => 'Test Contact',
            'email' => 'visitor@example.test',
            'project_type' => 'General inquiry',
            'message' => 'This message is long enough for validation.',
        ]))->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_inquiries', ['email' => 'visitor@example.test', 'read_at' => null]);
        $this->assertDatabaseHas('contact_inquiries', ['email' => 'visitor@example.test']);
        Http::assertSent(fn ($request) => $request->url() === 'https://admin.test/api/internal/forms/contact'
            && $request->hasHeader('Authorization', 'Bearer test-only-private-token')
            && $request['name'] === 'Test Contact'
            && $request['source_id'] === 'public-inquiry-1');
    }

    public function test_contact_honeypot_discards_bot_submission_without_creating_an_inquiry(): void
    {
        $data = $this->withBotProtection([
            'name' => 'Bot Submission',
            'email' => 'bot@example.test',
            'project_type' => 'General inquiry',
            'message' => 'This message is long enough for validation.',
        ]);
        $data['website'] = 'https://spam.example';

        $this->post('/contact', $data)->assertRedirect(route('contact'))->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_inquiries', ['email' => 'bot@example.test']);
    }

    public function test_contact_form_reports_when_admin_notification_api_is_unavailable(): void
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        Config::set('services.forms_notification_url', 'https://admin.test/api/internal/forms/contact');
        Http::fake(['admin.test/api/internal/forms/contact' => Http::response(['message' => 'Unavailable'], 503)]);

        $this->from('/contact')->post('/contact', $this->withBotProtection([
            'name' => 'Test Contact',
            'email' => 'visitor@example.test',
            'project_type' => 'General inquiry',
            'message' => 'This message is long enough for validation.',
        ]))->assertRedirect('/contact')->assertSessionHasErrors('contact');

        $this->assertDatabaseHas('contact_inquiries', ['email' => 'visitor@example.test']);
    }

    public function test_internal_content_api_requires_token_and_creates_public_content_only_when_active(): void
    {
        $payload = ['title' => 'Verified work', 'slug' => 'verified-work', 'category' => 'Product', 'summary' => 'Approved public case study.', 'status' => 'active'];
        $this->postJson('/api/internal/content/projects', $payload)->assertUnauthorized();

        Config::set('services.internal_content_token', 'test-only-private-token');
        $this->withToken('test-only-private-token')->postJson('/api/internal/content/projects', $payload)->assertCreated();
        $this->get('/projects/verified-work')->assertOk()->assertSee('Approved public case study.');

        $payload['title'] = 'Draft Work';
        $payload['slug'] = '';
        $payload['status'] = 'draft';
        $this->withToken('test-only-private-token')->postJson('/api/internal/content/projects', $payload)->assertCreated();
        $this->assertDatabaseHas('projects', ['slug' => 'draft-work', 'status' => 'draft']);
        $this->get('/projects/draft-work')->assertNotFound();

        $this->withToken('test-only-private-token')->postJson('/api/internal/content/blogs', [
            'title' => 'Topic API article', 'slug' => 'topic-api-article', 'topic' => 'Product Engineering',
            'excerpt' => 'An approved topic article.', 'body' => 'A published article body.', 'status' => 'active',
        ])->assertCreated();
        $this->assertDatabaseHas('blogs', ['slug' => 'topic-api-article', 'topic' => 'product-engineering']);
    }

    public function test_private_faq_api_creates_records_for_the_public_homepage(): void
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        $payload = ['question' => 'What do you build?', 'answer' => 'Web and mobile products.', 'status' => 'active', 'sort_order' => 1];

        $this->postJson('/api/internal/content/faqs', $payload)->assertUnauthorized();
        $this->withToken('test-only-private-token')->postJson('/api/internal/content/faqs', $payload)->assertCreated();
        $this->get('/')->assertOk()->assertSee('What do you build?')->assertSee('Web and mobile products.');
    }

    public function test_internal_fixed_page_editor_cannot_add_or_rename_routes(): void
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        $this->withToken('test-only-private-token')->putJson('/api/internal/content/fixed-pages/about', [
            'meta_title' => 'About metadata', 'meta_description' => 'Approved search summary.',
        ])->assertOk();
        $this->get('/about')->assertOk()->assertSee('<title>About metadata</title>', false);
        $this->withToken('test-only-private-token')->putJson('/api/internal/content/fixed-pages/new-page', [])->assertNotFound();
        $this->get('/home')->assertNotFound();
    }

    public function test_newsletter_signup_is_forwarded_to_the_admin_mailer(): void
    {
        Http::fake(['admin.localhost:8001/api/newsletter/subscribe' => Http::response(['message' => 'Confirmation email sent.'], 202)]);

        $this->post('/newsletter/subscribe', $this->withBotProtection(['email' => 'reader@example.test']))
            ->assertRedirect()
            ->assertSessionHas('newsletter_success');
        Http::assertSent(fn ($request) => $request->url() === config('services.newsletter_url')
            && $request['email'] === 'reader@example.test'
            && $request['website'] === ''
            && isset($request['bot_token']));
    }

    private function withBotProtection(array $data): array
    {
        Config::set('services.internal_content_token', 'test-only-private-token');
        $issuedAt = now()->subSeconds(3)->timestamp;

        return $data + [
            'website' => '',
            'bot_token' => $issuedAt.'.'.hash_hmac('sha256', (string) $issuedAt, 'test-only-private-token'),
        ];
    }
}
