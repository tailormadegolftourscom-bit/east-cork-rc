# East Cork Reclaim Childhood

A parent-led Laravel application supporting a pilot initiative in East Cork, Ireland, to help families
delay smartphones and social media together — and build a stronger real-world childhood in the meantime.

Live site: https://eastcorkreclaimchildhood.ie

## Stack

- Laravel 12 / PHP 8.2+
- Laravel Fortify (authentication: registration, email verification, password reset, 2FA scaffolding)
- MySQL (production), SQLite (convenient for local development)
- Bootstrap 5.3 (compiled CSS import, themed via CSS custom properties — see `resources/css/app.css`)
- Vite

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build   # or `npm run dev` while developing
php artisan serve
```

By default `.env.example` is configured for SQLite locally and `MAIL_MAILER=log` (mail is written to
`storage/logs/laravel.log` instead of being sent). Production uses MySQL and Resend's SMTP endpoint —
see the administrator guide for the required production values.

## Roles

Three user types share the `users` table (`user_type` column): `admin`, `school`, `parent`. See the
administrator guide for what each role can do and how accounts are created.

## Database

Schema is managed via `database/migrations/`. Production has been fully migrated and adopted into this
migration history — running `php artisan migrate` against a fresh database reproduces the current schema.

Two areas of note:

- `child_school_links` links a `Child` to their current school/class and (for 5th/6th class) an intended
  secondary school, via a `transition_status`.
- `areas` exists to let this pilot (East Cork) expand into a multi-area/nationwide structure later without
  a schema rewrite — `schools` and `committees` both carry a nullable `area_id`.

## Testing

There's no automated test suite covering application behaviour yet (only Laravel's default placeholder
tests in `tests/`). Verification so far has been manual, end-to-end, against disposable local databases —
see project history/commit notes for what's been exercised.
