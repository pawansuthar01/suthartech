<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Certification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use LogicException;

class BlogCertificateDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Blog and certificate demo content is restricted to local and testing environments.');
        }

        $articles = [
            ['Planning a SaaS Product MVP', 'A focused MVP answers the riskiest product questions with the smallest useful release.', [
                'Start with the user problem and the evidence that would prove your proposed solution helps. List assumptions about users, workflows, and adoption, then rank them by risk.',
                'Choose one end-to-end workflow that tests the most important assumptions. Keep the first release narrow, but complete enough that a user can reach a meaningful outcome.',
                'Agree on a small set of signals before implementation: task completion, time to value, repeat use, and qualitative feedback. Review them together to decide what to build next.',
            ]],
            ['A Practical Guide to Laravel APIs', 'Clear boundaries, validation, and consistent responses make Laravel APIs easier to operate and evolve.', [
                'Define the resources and actions your API exposes. Keep route names predictable and validate requests at the boundary, before malformed input reaches business logic.',
                'Use focused controllers and the project established service layer for multi-step domain work. Return consistent status codes and avoid exposing internal exception details.',
                'Check authorization on every sensitive operation. Document pagination, filtering, and error behavior so client teams do not depend on accidental implementation details.',
            ]],
            ['Designing Better Admin Workflows', 'An effective admin interface helps operators finish common tasks confidently and recover safely from mistakes.', [
                'Map the operator actual sequence of work before designing screens. Put common actions beside the information needed to make each decision, with familiar labels.',
                'Use clear status names, useful filters, and visible save feedback. For destructive actions, explain what will change and provide a recovery path when possible.',
                'Keyboard access, readable validation errors, and responsive layouts matter in internal tools too. Review workflows with representative users and remove steps that support no real decision.',
            ]],
            ['Building Reliable Cloud Deployments', 'Repeatable releases reduce avoidable deployment errors and make recovery more predictable.', [
                'A dependable deployment starts with a known artifact and a clear record of its source revision. Keep build and release steps repeatable instead of relying on undocumented manual actions.',
                'Separate readiness checks from deployment. Verify configuration, database compatibility, and health signals before shifting traffic, and retain a known rollback path.',
                'After release, observe user-facing health signals. A successful command is useful evidence, but it does not prove that the application serves requests correctly.',
            ]],
            ['Where AI Automation Fits in Operations', 'AI automation is most useful when the task is bounded, reviewable, and connected to a clear human workflow.', [
                'Look for repetitive work where inputs and acceptable outputs can be described. Start with assistance such as summarization or drafting, and keep people responsible for consequential decisions.',
                'Provide only the context required for the task. Make uncertain results visible, record meaningful actions, and define what happens when the system cannot answer dependably.',
                'Evaluate representative cases before expanding access. Measure time saved alongside correction rates and user trust, then improve places where review effort outweighs benefit.',
            ]],
            ['Improving Core Web Performance', 'Performance work starts with real user measurements and targets the slowest part of the experience.', [
                'Measure loading and interaction on representative devices and networks. Field data reveals delays that a fast developer machine can hide.',
                'Prioritize likely causes: oversized images, render-blocking resources, unnecessary JavaScript, and slow server responses. Set media dimensions and defer below-the-fold assets.',
                'Recheck the same measurements after each change. Track regressions in release workflows so performance remains part of product quality.',
            ]],
            ['Choosing a Scalable Data Model', 'A data model should make current rules explicit while leaving a clear path for likely changes.', [
                'Write down entities, ownership rules, and lifecycle states before adding tables. Identify required fields, unique values, and how records relate across accounts.',
                'Use constraints for invariants the database can enforce, and indexes for real query patterns. Keep derived data only when its update rules are clear.',
                'As requirements change, use deliberate migrations and preserve data during transitions. Check both the migration and the application behavior that depends on the model.',
            ]],
            ['Making Mobile Products Accessible', 'Accessible mobile interfaces help across different abilities, devices, and environments.', [
                'Use semantic controls with useful labels. Ensure touch targets are comfortable, focus order follows the screen, and important state changes reach assistive technology.',
                'Do not communicate status through color alone. Check text contrast, support larger system text, and keep layouts usable when content wraps or the keyboard opens.',
                'Test main flows with screen readers and reduced motion enabled. Accessibility works best when considered during design and implementation.',
            ]],
            ['Security Basics for Business Applications', 'Application security combines safe defaults, access checks, careful data handling, and timely updates.', [
                'Validate input at system boundaries and encode output for its destination. Use framework protections for sessions, CSRF, password handling, and parameterized database access.',
                'Check authorization on every sensitive operation, including direct requests. Give accounts only the access they need and keep secrets outside source control.',
                'Maintain dependencies and review useful security signals without logging passwords or tokens. A practiced response plan helps teams act quickly when an issue is found.',
            ]],
            ['A Checklist for Product Launches', 'A launch checklist aligns product, support, engineering, and measurement around a safe release.', [
                'Confirm that the intended audience, release scope, and success measures are understood. Review the critical user journey, permissions, content, and notifications.',
                'Prepare health monitoring, support ownership, incident contacts, backups, and a rollback decision. Check that analytics capture agreed signals without excess personal data.',
                'After launch, watch real usage and support reports. Communicate known limitations clearly and use evidence from the release to set product priorities.',
            ]],
            ['Measuring Useful Product Analytics', 'Useful analytics answer specific product questions while respecting the people who use the service.', [
                'Start with a decision the team expects to make. Define the events needed to answer it, and record what each metric cannot tell you.',
                'Use consistent event names and document when events fire. Avoid sensitive identifying information in payloads, and apply suitable retention and access controls.',
                'Combine quantitative patterns with user feedback. Review whether the numbers change a decision, and remove tracking without a clear benefit.',
            ]],
            ['Maintaining Software After Launch', 'Software stays dependable when teams budget for maintenance and observe real behavior.', [
                'Assign ownership for dependencies, backups, monitoring, and upgrades. Small regular maintenance tasks are easier to review than deferred work.',
                'Use logs and alerts that help diagnose failures without exposing sensitive data. Practice restoring backups and document operational steps that are easy to forget.',
                'Keep a clear path for user feedback and bug reports. Prioritize fixes by impact and reserve time to simplify areas that repeatedly slow safe changes.',
            ]],
        ];

        $topics = ['product', 'engineering', 'product', 'cloud', 'ai-data', 'engineering', 'ai-data', 'accessibility', 'security', 'product', 'ai-data', 'engineering'];
        $images = [
            'images/blog/product-systems.svg', 'images/blog/engineering-systems.svg',
            'images/blog/product-systems.svg', 'images/blog/cloud-operations.svg',
            'images/blog/ai-data-systems.svg', 'images/blog/engineering-systems.svg',
            'images/blog/engineering-systems.svg', 'images/blog/accessibility-systems.svg',
            'images/blog/security-systems.svg', 'images/blog/product-systems.svg',
            'images/blog/ai-data-systems.svg', 'images/blog/engineering-systems.svg',
        ];

        foreach ($articles as $index => [$title, $excerpt, $paragraphs]) {
            Blog::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => implode("\n\n", $paragraphs),
                'topic' => $topics[$index],
                'image_path' => $images[$index],
                'image_alt' => Str::headline($topics[$index]).' editorial illustration',
                'status' => 'active',
                'published_at' => now()->subDays($index * 3),
                'meta_title' => $title.' | SutharTech Blog',
                'meta_description' => $excerpt,
            ]);
        }

        $additionalArticles = [
            ['Validating a Problem Before Building', 'Talk with target users and observe the work before committing to a solution.', 'product'],
            ['Writing a Product Requirements Brief', 'A concise brief connects user needs, constraints, outcomes, and open questions.', 'product'],
            ['Building a Product Roadmap That Adapts', 'Keep roadmaps focused on outcomes so teams can respond when evidence changes.', 'product'],
            ['Prioritizing Product Work with Evidence', 'Compare customer impact, delivery effort, and uncertainty before choosing the next task.', 'product'],
            ['Moving from Prototype to Production', 'A prototype becomes a product after reliability, security, and support are planned.', 'product'],
            ['Planning a Useful Beta Program', 'A focused beta collects feedback from representative users around clear questions.', 'product'],
            ['Measuring Adoption Beyond Signups', 'Activation and repeated successful use reveal more than account creation alone.', 'product'],
            ['Designing Stable API Contracts', 'Consistent request and response shapes let client teams integrate with confidence.', 'engineering'],
            ['Choosing Between a Monolith and Services', 'Choose deployment boundaries from real ownership and scaling needs.', 'engineering'],
            ['Handling Background Jobs Safely', 'Reliable workers need idempotency, retries, timeouts, and useful failure visibility.', 'engineering'],
            ['Managing Database Migrations in Production', 'Small reversible migrations reduce risk when application versions overlap.', 'engineering'],
            ['Designing Reliable Webhooks', 'Signed requests, retry rules, and delivery records make webhook integrations dependable.', 'engineering'],
            ['Reducing Technical Debt without a Rewrite', 'Target debt that repeatedly slows delivery and improve it alongside product work.', 'engineering'],
            ['Building Observable Laravel Applications', 'Structured logs, metrics, and traces help teams find failures faster.', 'engineering'],
            ['Testing Critical User Workflows', 'Exercise the journeys users rely on, including validation and recovery paths.', 'engineering'],
            ['Structuring a Frontend for Change', 'Clear component boundaries keep shared behavior consistent as screens grow.', 'engineering'],
            ['Setting Sensible Software Boundaries', 'Separate responsibilities where change and ownership genuinely differ.', 'engineering'],
            ['Versioning APIs without Breaking Clients', 'Compatibility rules give consumers time to move safely between API versions.', 'engineering'],
            ['Planning Backups and Recovery', 'A backup plan matters only when restore steps are documented and practiced.', 'cloud'],
            ['Improving Deployment Rollbacks', 'A release process should make rollback criteria and recovery steps clear.', 'cloud'],
            ['Scaling a Web Application Gradually', 'Measure load patterns and add capacity to the slowest constrained part first.', 'cloud'],
            ['Monitoring Costs in Cloud Systems', 'Useful cost monitoring connects spend to owners, services, and workload trends.', 'cloud'],
            ['Designing a Safer Release Pipeline', 'Automated checks and staged rollouts catch problems before broad exposure.', 'cloud'],
            ['Managing Application Secrets Properly', 'Keep credentials out of source control and rotate them through managed stores.', 'cloud'],
            ['Preparing for Incident Response', 'Clear ownership and practiced runbooks help teams respond under pressure.', 'cloud'],
            ['Evaluating an AI Feature before Launch', 'Test representative cases and define where human review remains necessary.', 'ai-data'],
            ['Keeping Human Review in AI Workflows', 'Make uncertain outputs visible and place review where decisions carry impact.', 'ai-data'],
            ['Defining Data Retention Rules', 'Collect only useful information and document how long each class is kept.', 'ai-data'],
            ['Building a Reliable Data Import', 'Validate source files, report rejected rows, and make retries safe.', 'ai-data'],
            ['Monitoring Data Quality over Time', 'Freshness, completeness, and consistency checks catch drift before it spreads.', 'ai-data'],
            ['Using Analytics to Answer Product Questions', 'Start from a decision and instrument only the events needed to inform it.', 'ai-data'],
            ['Selecting Metrics that Guide Decisions', 'Good product metrics have clear definitions, owners, and known limitations.', 'ai-data'],
            ['Auditing Keyboard Navigation', 'Test focus order and keyboard operation across the full user journey.', 'accessibility'],
            ['Writing Useful Form Error Messages', 'Specific, nearby error guidance helps people recover without guesswork.', 'accessibility'],
            ['Testing Layouts at High Zoom', 'Responsive interfaces should remain usable as text grows and content wraps.', 'accessibility'],
            ['Building Clear Focus States', 'Visible focus indicators show keyboard users where interaction will occur.', 'accessibility'],
            ['Designing Accessible Navigation Menus', 'Predictable menu behavior and semantic controls support more users.', 'accessibility'],
            ['Reviewing Access Control for Admin Tools', 'Check permissions on every sensitive action, including direct requests.', 'security'],
            ['Protecting User Data in Logs', 'Diagnostic records should help investigation without retaining private payloads.', 'security'],
            ['Strengthening Session Security', 'Secure cookies, expiration, and session rotation reduce account exposure.', 'security'],
            ['Preparing a Dependency Update Process', 'Regular updates and focused review keep maintenance work manageable.', 'security'],
            ['Making File Uploads Safer', 'Restrict file types and sizes, store uploads safely, and verify access rules.', 'security'],
        ];

        $guidance = [
            'product' => ['Write down the user, the task, and the outcome the work should improve. Check assumptions with evidence before increasing scope.', 'Give the team a small success measure and a review date. Use what users do and report to decide what should change next.'],
            'engineering' => ['Make the expected behavior explicit at the system boundary and handle invalid or repeated requests safely.', 'Add focused automated coverage for the failure cases. Review operational signals after release and simplify the design where maintenance becomes difficult.'],
            'cloud' => ['Document ownership, dependencies, and the conditions that require intervention before automating the workflow.', 'Exercise the recovery path in a safe environment. Measure reliability and cost after each change so capacity follows actual usage.'],
            'ai-data' => ['Define the data inputs, expected outputs, and cases where the system must abstain or request review.', 'Evaluate representative examples before release, then monitor quality and user corrections. Keep retention and access rules visible to the team.'],
            'accessibility' => ['Use semantic controls and test the interaction with a keyboard and assistive technology, not only with a pointer.', 'Check contrast, zoom, and content reflow on narrow screens. Fix issues in shared components so the improvement reaches every page.'],
            'security' => ['Identify the information and actions that need protection, then enforce access and validation at the server boundary.', 'Test unauthorized and malformed requests as well as the normal workflow. Keep a clear update and recovery process for the people operating the system.'],
        ];
        $topicImages = [
            'product' => 'images/blog/product-systems.svg',
            'engineering' => 'images/blog/engineering-systems.svg',
            'cloud' => 'images/blog/cloud-operations.svg',
            'ai-data' => 'images/blog/ai-data-systems.svg',
            'accessibility' => 'images/blog/accessibility-systems.svg',
            'security' => 'images/blog/security-systems.svg',
        ];

        foreach ($additionalArticles as $index => [$title, $excerpt, $topic]) {
            Blog::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => implode("\n\n", [$excerpt, ...$guidance[$topic]]),
                'topic' => $topic,
                'image_path' => $topicImages[$topic],
                'image_alt' => Str::headline($topic).' editorial illustration',
                'status' => 'active',
                'published_at' => now()->subDays(39 + $index * 3),
                'meta_title' => $title.' | SutharTech Blog',
                'meta_description' => $excerpt,
            ]);
        }

        $advancedArticles = [
            ['A Safer Image Upload Workflow in Laravel', 'Validate image uploads at the boundary, keep files on the intended disk, and make replacement and removal predictable.', 'security', 'images/blog/security-systems.svg', <<<'MARKDOWN'
## Start with the publishing boundary

An image picker is not a security control. Keep upload actions behind the authenticated content manager, validate the actual file as an allowed image type, and set a size limit that matches the product. Laravel's [file validation rules](https://laravel.com/docs/validation#validating-files) provide a server-side starting point; the browser's `accept` attribute only helps people choose a file.

## Store and serve deliberately

Use a random stored filename rather than trusting the original name. Keep private material on a private disk, and use the public disk only for images intended to appear on the website. Laravel documents the [public disk and its URL setup](https://laravel.com/docs/filesystem#the-public-disk). The web server should not expose application configuration or private storage.

## Make replacement safe

Store the replacement before deleting the old file, then update the record. If the database update can fail after the new upload, clean up the unused file. Offer an explicit remove action and never delete a shared source asset that lives under the application’s public image directory.

## Review the image in context

Preview the selected file before saving, check that it crops well in the listing and article, and write useful alternative text when the image conveys information. For purely decorative thumbnails, an empty alt is more helpful than repeating the article title. See the [W3C guidance on text alternatives](https://www.w3.org/WAI/WCAG22/Understanding/non-text-content.html).
MARKDOWN
            ],
            ['A Practical Accessibility Pass for Article Pages', 'A short, repeatable review catches common barriers in article content, navigation, images, and controls.', 'accessibility', 'images/blog/accessibility-systems.svg', <<<'MARKDOWN'
## Check the page without a mouse

Tab through the navigation, topic filters, article links, and pagination. Focus should stay visible and move in a sensible reading order. Links should describe their destination; avoid a feed full of identical “read more” labels without nearby context.

## Give images the right alternative

Ask what information an image contributes. Describe that information in alt text if it matters to the article. If a thumbnail merely decorates a linked title that already names the destination, keep the thumbnail silent to assistive technology. The [W3C non-text content guide](https://www.w3.org/WAI/WCAG22/Understanding/non-text-content.html) explains this distinction.

## Keep the article structure readable

Use one page heading, then nested section headings in order. Write link text that still makes sense outside its paragraph, and use lists for actual groups of items. Do not use heading levels only to make text look larger.

## Test real content, not just the template

Long titles, narrow screens, large text, missing images, and validation errors all change the experience. Review one representative article at mobile width and with keyboard navigation after the content is saved.
MARKDOWN
            ],
            ['Optimize Images for a Faster Editorial Site', 'Treat image dimensions, loading priority, and useful alternatives as part of the publishing workflow.', 'engineering', 'images/blog/engineering-systems.svg', <<<'MARKDOWN'
## Prepare an image before publishing

Start from an image with enough resolution for its largest display size, then export an appropriately sized web image. Avoid uploading a multi-megabyte original when a smaller derivative will look identical in the card. Keep the displayed aspect ratio stable so the page does not jump while media loads.

## Load media according to its position

The first visible feature image may deserve high priority. Images lower in a long feed should load lazily so the browser does not fetch every image immediately. Do not lazy-load the image that is the page’s primary visual if it is needed for the initial view.

## Make the source path reliable

Use one URL rule for stored uploads and checked-in public illustrations. The article page, feed, Open Graph metadata, and structured data should all resolve to the same working image. A correct database path is not enough if the generated URL points at the wrong disk.

## Validate after upload

Open the saved article and request its image URL directly. Check the browser network response, the mobile crop, and the alternative text. These checks catch broken storage links and awkward crops before the post is promoted.
MARKDOWN
            ],
            ['A Reviewable Blog Publishing Workflow', 'Keep drafts editable, make publication deliberate, and verify the public page after every content change.', 'product', 'images/blog/product-systems.svg', <<<'MARKDOWN'
## Write for a clear reader task

Choose one audience and one question for each post. Use a title that tells readers what they will learn, a short summary that adds context, and sections that move from the problem to a practical next step.

## Separate editing from publishing

Save an early draft while the outline, sources, and image are still being reviewed. Before publishing, check factual statements, outbound links, dates, spelling, image rights, alt text, and the mobile layout. A status control should make it obvious whether an item is public.

## Treat SEO fields as page metadata

Write a page-specific title and description that accurately summarize the article. Keep the URL stable after publication; if a slug must change, add a redirect rather than leaving old links broken. Structured data should mirror visible content instead of introducing claims readers cannot find on the page.

## Verify the finished result

Open the public URL in a signed-out browser, check its canonical and social preview metadata, confirm the sitemap contains the published URL, and ensure a draft remains inaccessible. Publishing is complete only when the rendered page works, not merely when the admin form reports success.
MARKDOWN
            ],
            ['How to Verify a Company Certification Before Publishing', 'A careful verification record names the issuer, carries a unique ID, and links to primary evidence.', 'security', 'images/blog/security-systems.svg', <<<'MARKDOWN'
## Confirm what the credential represents

Check that the document names the organization being represented and that its scope actually applies to that organization. A course completion certificate held by an individual is not automatically a company accreditation, supplier approval, or product certification.

## Use evidence from the issuer

Record the exact credential ID and issue date from the certificate. Prefer a verification page on the issuer’s own domain. If no lookup exists, retain the issuer-provided document and record who reviewed it and when; do not substitute a logo or an unrelated certificate image.

## Keep public records accurate over time

Show expiry dates when the issuer provides them, and mark expired records as expired rather than removing their history without explanation. Keep drafts private until the evidence is checked, and update or deactivate a record if the issuer revokes it.

## Avoid unsupported company claims

Do not publish sample IDs or invented issuers as real credentials. If no verified company certificate is available, an empty public directory is more trustworthy than a fabricated badge. This review process is editorial guidance, not a claim that any specific organization holds a credential.
MARKDOWN
            ],
            ['Designing Reliable Content APIs Between Admin and Website', 'A private content API should validate uploads, preserve status rules, and report errors clearly to its editor.', 'engineering', 'images/blog/engineering-systems.svg', <<<'MARKDOWN'
## Define the contract once

Use the same field names and status values at both ends of the integration. Document which fields are required for each content type, how pagination works, and what an upload response returns. Inconsistent “published” and “active” labels create filters that silently hide records.

## Keep private writes authenticated

Require a server-to-server credential for content creation, changes, and deletion. Never place that token in browser code. Check authorization before accepting files, validate the request on the receiving app, and return structured field errors so the admin can correct a draft without losing its text.

## Handle partial failures

An admin save may involve a remote API, file storage, and a database update. Show a clear failure if the API is unavailable, avoid reporting success before the write completes, and remove an unused new file when persistence fails. Keep timeouts bounded and preserve the editor's inputs after validation errors.

## Verify the public result

After a successful save, check the public listing and detail URL, image response, SEO metadata, and sitemap behavior. The API response proves the record was accepted; it does not prove the public page presents it correctly.
MARKDOWN
            ],
        ];

        foreach ($advancedArticles as $index => [$title, $excerpt, $topic, $image, $body]) {
            Blog::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'topic' => $topic,
                'image_path' => $image,
                'image_alt' => Str::headline($topic).' editorial illustration',
                'status' => 'active',
                'published_at' => now()->subDays(1 + $index * 2),
                'meta_title' => $title.' | SutharTech Blog',
                'meta_description' => $excerpt,
            ]);
        }

        foreach ([
            ['Secure Application Development', 'Sample Learning Board'],
            ['Cloud Operations Fundamentals', 'Sample Technology Institute'],
            ['Accessible Product Design', 'Sample Digital Academy'],
            ['Data Protection Practices', 'Sample Security Institute'],
            ['Quality Assurance Workflow', 'Sample Software Academy'],
            ['Responsible AI Foundations', 'Sample Learning Board'],
        ] as $index => [$subject, $issuer]) {
            $title = 'Sample: '.$subject;
            Certification::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'issuer' => $issuer.' (demo)',
                'credential_id' => sprintf('SAMPLE-CERT-%03d', $index + 1),
                'credential_url' => null,
                'issued_at' => now()->subMonths($index + 2)->startOfMonth()->toDateString(),
                'expires_at' => null,
                'description' => 'Demonstration record for local page and admin testing only. This is not an issued certificate, a real accrediting organization, or a claim about SutharTech qualifications.',
                'image_path' => null,
                'status' => 'active',
                'sort_order' => $index + 1,
                'meta_title' => $title.' | SutharTech Demo',
                'meta_description' => 'Sample certificate page for local website testing. Not a real accreditation.',
            ]);
        }
    }
}
