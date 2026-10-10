# SutharTech content administration

The cloned admin remains at `admin/` and keeps its existing login and visual design. Its SutharTech content managers write to the public client's database through a private token-protected API. The client theme and existing fixed page layouts remain unchanged.

## Local setup

1. In `client/`, set `INTERNAL_CONTENT_TOKEN` to a random 64-character hex secret, then run `php artisan migrate` and `php artisan storage:link`.
2. In `admin/`, set the same value as `SUTHARTECH_CONTENT_TOKEN`; set `SUTHARTECH_CONTENT_URL`, `SUTHARTECH_ADMIN_URL`, and `SUTHARTECH_PUBLIC_URL` for the local domains, then run `php artisan migrate`.
3. Generate a token with `php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"`. Keep it in local environment files and deployment secrets only.
4. Sign in at the existing admin login. Use SutharTech Projects, Certifications, Website Blogs, Website Services, Website Feedback, and Fixed Page SEO in the sidebar.
5. Set valid SMTP credentials under Admin → Account before sending a campaign or activating a certification. Subscriptions require email confirmation. Campaign sends are synchronous; `php artisan queue:work` is not required.

For local review only, run `php artisan db:seed --class=LegacyDemoContentSeeder`. It seeds preview projects, blogs, feedback, and certifications (plus existing services) so public pages and admin workflows have data. These entries are explicitly sample content, and the seeder refuses to run in production. `DatabaseSeeder` does not create content or admin users.

Fixed Page SEO only edits meta title and description for existing routes. It does not create, rename, delete, or change a route's layout. The About page's team stays static.

Legacy seeded sample projects and feedback are retained as inactive records so they can be inspected, but are not shown publicly. Enter verified project and feedback details before activating them.

## Production

Set production environment and database values outside source control. Use the same private API token in both apps, point the admin API URL to the client's private origin, set public/admin canonical URLs, configure SMTP in the existing admin settings, run migrations with `--force` in both apps, create the public storage symlink in `client/`, and run `php artisan optimize` in each app. Keep both web roots pointed at their `public` folders. The Admin → Integrations image-check toggle controls public contact/newsletter forms; if the admin API is on a different internal URL, set `SUTHARTECH_FORM_PROTECTION_SETTINGS_URL` in the client environment. The admin's public newsletter subscribe endpoint is rate limited; confirmation and unsubscribe links use random tokens.
