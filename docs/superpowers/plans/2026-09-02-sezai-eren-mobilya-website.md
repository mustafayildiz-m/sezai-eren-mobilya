# Sezai Eren Mobilya Website Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** A dockerized PHP 8.3 + SQLite showcase website for an Ankara furniture maker with an admin panel for uploading project photos and receiving quote requests.

**Architecture:** Single-entry `public/index.php` dispatches to a tiny Router; controllers render PHP templates through a layout. SQLite via PDO with idempotent migrations. GD-based image pipeline converts uploads to WebP. Tailwind is compiled once to a static CSS file; vanilla JS handles animations, lightbox, filters and admin uploads.

**Tech Stack:** PHP 8.3 (php:8.3-apache), SQLite/PDO, GD, Tailwind CSS (compiled via npx once), vanilla JS, PHPUnit 11, Docker Compose.

**Spec:** `docs/superpowers/specs/2026-09-02-sezai-eren-mobilya-website-design.md`

## Global Constraints
- No Composer dependency at runtime; PHPUnit is dev-only (run via `docker compose run --rm test` using a phpunit.phar).
- Runtime must work on shared hosting via FTP: no build step in production, `public/` is the web root.
- All DB access via prepared statements; all admin POST routes carry a CSRF token.
- Palette: walnut `#2B1D14`, ground `#1A130E`, cream `#F5EFE6`, copper `#B87333`, beige `#E8DCC8`. Fonts: Cormorant Garamond (headings), Inter (body).
- Turkish UI copy throughout; admin path prefix `/yonetim`.
- Images: 1600px WebP q82 + 480px thumb `{uuid}.webp` / `{uuid}_thumb.webp`.
- Respect `prefers-reduced-motion`.

---

## File Structure
```
Dockerfile, docker-compose.yml, .env.example, .gitignore, phpunit.xml, README.md
public/index.php, public/.htaccess, public/assets/{app.css,app.js,admin.js,seed/}, public/uploads/
src/bootstrap.php            env loading, autoload (PSR-4 like: App\ -> src/), helpers (e(), url(), csrf_token())
src/Router.php               add(method, pattern, handler), dispatch(method, path)
src/Database.php             singleton PDO, migrate()
src/Slug.php                 Slug::make(string): string (Turkish chars)
src/Image.php                Image::process(tmpPath, destDir): array{filename, thumb}
src/Auth.php                 login/logout/check/attemptLimit
src/Csrf.php                 token()/verify()
src/RateLimit.php            hit(key, max, windowSec): bool
src/Seo.php                  meta(array), localBusiness(settings), breadcrumb(items), product(project)
src/View.php                 render(template, vars, layout)
src/Models/{Category,Project,ImageModel,Quote,Setting,User}.php
src/Controllers/{HomeController,ProjectController,PageController,QuoteController,SitemapController}.php
src/Controllers/Admin/{AuthController,DashboardController,ProjectAdminController,CategoryAdminController,QuoteAdminController,SettingAdminController}.php
templates/layout.php, templates/admin/layout.php, templates/{home,projects,project,about,contact,quote,quote_done,404}.php
templates/admin/{login,dashboard,projects,project_form,categories,quotes,settings}.php
scripts/seed.php, scripts/download_images.sh
tests/{RouterTest,SlugTest,ImageTest,RateLimitTest,QuoteValidationTest,SeoTest,AuthTest}.php
```

---

### Task 1: Docker skeleton + bootstrap + Router (TDD)
**Files:** Dockerfile, docker-compose.yml, .env.example, .gitignore, phpunit.xml, public/index.php, public/.htaccess, src/bootstrap.php, src/Router.php, tests/RouterTest.php
**Produces:** `Router::add(string $method, string $pattern, callable $handler)`, `Router::dispatch(string $method, string $path): mixed` where pattern `/projeler/{slug}` yields `$params['slug']`; returns handler result or throws `NotFound`.
- [ ] Write `tests/RouterTest.php`: static match, param match, 404 → `App\NotFound` exception, method mismatch → 404.
- [ ] Run `docker compose run --rm test` → fails (class missing).
- [ ] Implement Router with regex compiled from `{name}` → `(?P<name>[^/]+)`.
- [ ] Tests pass. Commit `feat: docker skeleton, bootstrap, router`.

### Task 2: Database + migrations + models
**Files:** src/Database.php, src/Models/*.php, src/Slug.php, tests/SlugTest.php
**Produces:** `Database::pdo(): PDO`, `Database::migrate()`; models with static methods: `Category::all()`, `Category::bySlug()`, `Project::all(?int $categoryId)`, `Project::featured(int $limit)`, `Project::bySlug(string)`, `Project::find(int)`, `Project::create(array)`, `Project::update(int, array)`, `Project::delete(int)`, `ImageModel::forProject(int)`, `ImageModel::add(int, string, string)`, `ImageModel::delete(int)`, `Quote::create(array)`, `Quote::all()`, `Quote::markRead(int)`, `Setting::get(string, string $default='')`, `Setting::set(string,string)`, `Setting::all()`, `User::findByUsername()`, `User::ensureAdmin()`.
- [ ] Write `tests/SlugTest.php`: `Slug::make('Şık Mutfak Çankaya')` === `sik-mutfak-cankaya`; duplicates handled by caller with `-2` suffix helper `Slug::unique(string, callable $exists)`.
- [ ] Fail → implement Slug → pass.
- [ ] Implement Database with `DB_PATH` env, `migrate()` creating the 6 tables (IF NOT EXISTS) and seeding default settings + admin user from env.
- [ ] Implement models. Commit `feat: sqlite schema and models`.

### Task 3: Image pipeline (TDD)
**Files:** src/Image.php, tests/ImageTest.php
**Produces:** `Image::process(string $tmpPath, string $destDir): array{filename:string, thumb:string}` throws `InvalidArgumentException` on bad MIME; `Image::validate(array $file): ?string` returns error message or null.
- [ ] Test: create a 2400x1600 JPEG with GD in tmp, process, assert two WebP files exist, main width 1600, thumb width 480. Test rejects a text file.
- [ ] Fail → implement with `imagecreatefromstring`, `imagescale`, `imagewebp` → pass. Commit `feat: image processing to webp`.

### Task 4: Auth, CSRF, RateLimit (TDD)
**Files:** src/Auth.php, src/Csrf.php, src/RateLimit.php, tests/RateLimitTest.php, tests/AuthTest.php
**Produces:** `Auth::attempt(string,string): bool`, `Auth::check(): bool`, `Auth::logout()`, `Auth::require()` (redirect to login); `Csrf::token()`, `Csrf::verify(?string): bool`; `RateLimit::hit(string $key, int $max, int $windowSec): bool` (file-based under `storage/ratelimit/`).
- [ ] RateLimit test: 3 hits allowed, 4th false, new window allowed (inject clock via optional `$now`).
- [ ] Auth test: `password_verify` path with an in-memory sqlite user.
- [ ] Implement → pass. Commit `feat: auth, csrf, rate limit`.

### Task 5: View + Seo + public layout with Tailwind
**Files:** src/View.php, src/Seo.php, templates/layout.php, public/assets/app.css (compiled), tailwind.config.js, src/input.css, public/assets/app.js, tests/SeoTest.php
**Produces:** `View::render(string $tpl, array $vars=[], string $layout='layout'): string`; `Seo::jsonLd(array): string`, `Seo::localBusiness(array $settings): array`, `Seo::breadcrumb(array $items): array`, `Seo::product(array $project, array $images): array`. Layout accepts `$title, $description, $canonical, $ogImage, $jsonLd[]`.
- [ ] SeoTest: localBusiness contains `@type: LocalBusiness`, address locality Ankara; breadcrumb positions 1..n.
- [ ] Implement Seo + View. Build layout: sticky translucent header, mobile menu, footer with NAP (name-address-phone), WhatsApp floating button, Google Fonts, `.reveal` animation CSS, reduced-motion media query.
- [ ] Compile Tailwind: `npx tailwindcss -i src/input.css -o public/assets/app.css --minify`; commit output.
- [ ] app.js: IntersectionObserver reveal, counters, mobile menu, lightbox, gallery filter, hero Ken Burns class toggle.
- [ ] Commit `feat: layout, seo helpers, styles`.

### Task 6: Public pages (Home, Projects, Project detail, About, Contact, 404, sitemap/robots)
**Files:** src/Controllers/*.php, templates/{home,projects,project,about,contact,404}.php, src/Controllers/SitemapController.php, public/index.php routes
- [ ] Register routes: `/`, `/projeler`, `/projeler/{slug}`, `/hakkimizda`, `/iletisim`, `/sitemap.xml`, `/robots.txt`.
- [ ] Home: hero with `assets/seed/hero.webp`, service cards from categories, featured projects grid, counters, testimonials (static), CTA.
- [ ] Projects: category pills filter (server-side via `?kategori=`), grid with thumbs, lightbox data-attrs.
- [ ] Project detail: gallery, description, location, related projects (same category), Product + Breadcrumb JSON-LD.
- [ ] Sitemap: static pages + all project URLs; robots disallows `/yonetim`.
- [ ] Manual check via `curl -s localhost:8080 | head`. Commit `feat: public pages`.

### Task 7: Quote form (TDD on validation)
**Files:** src/Controllers/QuoteController.php, src/QuoteValidator.php, templates/{quote,quote_done}.php, tests/QuoteValidationTest.php
**Produces:** `QuoteValidator::validate(array $post, array $files): array{errors: array, data: array}`.
- [ ] Test: missing name/phone → errors; honeypot filled → error `spam`; valid → clean data with phone normalized digits.
- [ ] Implement; controller: GET form, POST → RateLimit `quote:{ip}` 3/300s → validate → optional photo via `Image::process` → `Quote::create` → redirect `/teklif-al/tesekkurler` with WhatsApp prefilled link.
- [ ] Commit `feat: quote form`.

### Task 8: Admin panel
**Files:** src/Controllers/Admin/*.php, templates/admin/*.php, public/assets/admin.js
- [ ] Routes under `/yonetim`: giris (GET/POST, RateLimit `login:{ip}` 5/600s), cikis, `/` dashboard, projeler CRUD, `projeler/{id}/resim` POST (multi upload via fetch, returns JSON), `resim/{id}/sil`, `resim/{id}/kapak`, `resim/sirala` POST, kategoriler CRUD, teklifler list/okundu/sil, ayarlar.
- [ ] Admin layout: dark sidebar, Tailwind, CSRF hidden input helper `csrf_field()`.
- [ ] admin.js: drag-drop uploader with progress, image reorder (HTML5 drag), delete confirm.
- [ ] Commit `feat: admin panel`.

### Task 9: Seed data + stock images + README
**Files:** scripts/download_images.sh, scripts/seed.php, public/assets/seed/, README.md
- [ ] download_images.sh: curl ~14 Unsplash images (kitchen cabinets, wardrobes, walnut interiors) into `public/assets/seed/raw/`; seed.php processes them via `Image::process` into uploads and creates 6 categories + 12 projects with Ankara district names, marks 6 featured.
- [ ] README: run, admin login, deploy to shared hosting (FTP), backup (copy `storage/database.sqlite` + `public/uploads`).
- [ ] `docker compose up -d --build && docker compose exec app php scripts/seed.php`; verify pages with curl; run full test suite. Commit `feat: seed data and docs`.

## Self-Review
- Spec coverage: pages ✔ (T6,T7), admin ✔ (T8), images ✔ (T3), SEO ✔ (T5,T6), security ✔ (T4,T8), docker ✔ (T1), seed ✔ (T9), tests ✔ (T1-T7).
- Names consistent: `Image::process`, `Slug::make`, `RateLimit::hit`, `Csrf::verify`, `View::render` used identically across tasks.
