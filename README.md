# SutharTech

- `client/` is the public Laravel website. Run it from that folder on port `8001`.
- `admin/` is the cloned admin application. It opens at `http://admin.localhost:8001` and keeps its existing login and theme.
- SutharTech website content lives in the client's database and is managed from the cloned admin through a private token-protected API. Newsletter subscribers and campaigns live in the admin database. Run migrations from each app's folder.
- No default admin password is configured. An existing user must have `is_admin` enabled to sign in.

Start the client from its folder with `php artisan serve --host=127.0.0.1 --port=8001`. `localhost` serves the client; `admin.localhost` serves the cloned admin app on the same port.

See [client/ADMIN_SETUP.md](client/ADMIN_SETUP.md) for shared API token, SMTP, fixed page SEO, subscriber confirmation, and migration setup.
