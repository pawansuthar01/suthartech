# SutharTech Project Rules

These rules apply to every future agent, developer, and automated code change in this repository.

## 1. Theme Is Locked

- Keep the current SutharTech visual language. Do not introduce a new theme, palette, font system, or visual direction without explicit approval.
- Use the existing design tokens in `public/css/home.css`:
  - Background: `--bg-main` (`#FAFAF9`)
  - Primary text: `--text-primary` (`#0F172A`)
  - Secondary text: `--text-secondary` (`#64748B`)
  - Buttons: `--btn-primary` and `--btn-primary-hover`
  - Borders: `--border-subtle`
  - Accents: `--accent-pink` and `--accent-blue`
- Use the existing `Geist`, `Plus Jakarta Sans`, and Material Symbols font setup. Do not add another font for a one-off section.
- Do not add unrelated neon colors, random gradients, heavy shadows, dark blue/slate-only pages, decorative blobs, or visual styles that conflict with the current neutral glass and slate theme.
- If a new color is genuinely required, derive it from the existing palette and add it as one reusable token before using it.
- Do not change existing user-facing copy, section order, or layout direction unless the request explicitly asks for it.

## 2. Reusable Architecture

- Keep repeated UI reusable and easy to edit. Use Blade components or partials under `resources/views/components` or `resources/views/partials` for repeated cards, buttons, navigation, footer groups, tickers, and form controls.
- Keep repeated content in arrays/configuration and render it with Blade loops where practical. Do not copy the same markup many times when a loop or component can express it clearly.
- Keep page-specific styling in semantic classes in `public/css/home.css`; avoid new long inline style blocks and avoid scattered inline styles.
- Keep interaction code in a dedicated JS module when it grows beyond a small local behavior. Do not duplicate event listeners or animation logic across sections.
- Prefer existing Laravel, Blade, Vite, Tailwind, and Material Symbols patterns already used by the project. Do not add a dependency for a small visual or utility task.
- Keep files focused. If a file becomes difficult to understand or safely change, split it by ownership instead of adding more unrelated code.

## 3. Safe Code Changes

- Inspect the surrounding code before editing. Preserve unrelated user changes and do not rewrite whole files for a small request.
- Use `apply_patch` for manual edits and keep each change narrowly scoped.
- Use semantic names for classes, variables, components, and data keys. Avoid unexplained magic numbers and duplicated timing values.
- Keep desktop and mobile behavior intentional. Every new layout must be checked at a desktop and a narrow mobile width.
- Keep animations smooth and restrained. Infinite marquees must be duplicated consistently, hide their scrollbar, and use timing that does not make content unreadable.
- Keep carousel, ticker, and auto-cycle code pauseable or keyboard accessible where applicable. Do not create motion that blocks reading or interaction.
- Preserve accessibility: meaningful `alt` text, keyboard focus states, button labels, heading order, sufficient contrast, and reduced-motion consideration for animated content.

## 4. Assets And Routing

- Use `asset()` or Laravel route helpers for URLs. Do not hardcode local asset paths in templates.
- Every new image must exist under `public/images` or another intentional public asset directory, have meaningful `alt` text, and be checked for a non-404 response.
- Do not expose `.env`, application root files, `storage`, or private configuration through the web root. The web server document root must remain `public`.
- Do not add fake external image URLs or placeholders when a real local asset is required.

## 5. Validation Before Completion

For any meaningful change, run the checks that apply and report failures instead of hiding them:

```text
git diff --check
php artisan optimize:clear
php artisan view:cache
php artisan test
```

- Confirm the homepage returns HTTP `200`.
- Confirm all newly referenced assets return HTTP `200`.
- Confirm Blade compiles and no JavaScript syntax errors are introduced.
- Check the changed workflow visually at desktop and mobile widths when the change affects UI.
- Do not mark work complete while a known 404, compile error, broken responsive state, or console error remains.
- Do not run `npm run build`, `tsx`, `npx tsx`, or arbitrary/random counting or probing commands just to consume time or tokens. Use only direct checks that are relevant to the change and available in the environment.

## 6. Production Discipline

- Production must use `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_KEY`, correct database credentials, and a web root pointing to `public`.
- Run `composer install --no-dev --optimize-autoloader`, the project-approved frontend deployment step, migrations with `--force` when appropriate, and `php artisan optimize` during deployment.
- Never commit secrets, local `.env` values, generated credentials, or private API keys.
- If queues or scheduled jobs are introduced, document their worker and cron requirements in the same change.

## 7. Completion Standard

A change is complete only when it is implemented, reusable, theme-consistent, responsive, validated, and easy for the next developer to locate and edit. The final response must list changed files, checks run, and any remaining limitation.
