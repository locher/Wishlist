# Repository Guidelines

## Project Structure

This repository is a Laravel/Inertia React wishlist application. Laravel code lives in `app/`, with controllers in `app/Http/Controllers`, form validation in `app/Http/Requests`, models in `app/Models`, and the former-system import command in `app/Console/Commands`. Inertia pages and components live in `resources/js`, shared CSS and Tailwind styling live in `resources/css/app.css`, and public assets live in `public/images` and `public/favicon`. Database migrations live in `database/migrations`; Pest tests live in `tests/`. Private dataset snapshots must stay out of Git.

## Commands

- `composer install`: installs PHP dependencies.
- `npm install`: installs frontend dependencies.
- `composer dev`: runs Laravel, the queue listener, and Vite concurrently.
- `php artisan migrate`: applies database migrations.
- `php artisan wishlist:import-legacy`: imports legacy MySQL tables through the `legacy_mysql` connection.
- `composer check`: runs Pint, Larastan/PHPStan, and Pest.
- `composer test`: clears config and runs Pest.
- `composer coverage`: runs Pest with the minimum coverage gate.
- `composer lint`: checks PHP formatting with Laravel Pint.
- `composer analyse`: runs Larastan/PHPStan static analysis.
- `composer format`: formats PHP with Pint.
- `npm run dev`: starts Vite only.
- `npm run check`: runs ESLint, Prettier check, and the production build.
- `npm run build`: type-checks TypeScript and builds frontend assets.

When native PHP is unavailable, run commands through Docker, for example `docker run --rm -v "$PWD:/app" -w /app composer:2 php artisan test`.

## Coding Style

Follow Laravel conventions for PHP classes, routes, Form Requests, and Eloquent relationships. Use descriptive names such as `ProfileController`, `GiftRequest`, `profile_relations`, and `reserved_by_guest_name`. Keep React components in PascalCase and TypeScript props explicit. Prefer existing shadcn-style primitives in `resources/js/Components/ui`. Preserve the legacy visual identity: deep navy, illustrated avatars, large headings, rounded controls, and the white/navy background composition.

## Testing Guidelines

Use Pest for automated tests. Feature tests cover sessions, profile CRUD, gift CRUD, reservations, and permissions. Unit tests cover Eloquent relations, age formatting, and idempotent legacy import. Add tests when changing authorization, reservations, import behavior, or shared model logic. Run `composer test` before submitting changes and `npm run build` after frontend edits.

## Commit & Pull Request Guidelines

Use Conventional Commits in English, for example `feat: add guest access page` or `fix: sort profiles alphabetically`. Husky runs Commitlint on `commit-msg`, lint-staged formatting on `pre-commit`, and `npm run check` plus `composer check` on `pre-push`. Keep commits focused. Pull requests should include a summary, linked issue when available, migration/import notes, test results, and screenshots for visual changes.

## Changelog

Maintain `CHANGELOG.md` using Keep a Changelog 1.1.0. Add user-visible changes under `Unreleased` with `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, or `Security` headings as applicable.

## Security & Configuration Tips

Do not commit production credentials, local `.env` values, or private dataset snapshots. Configure import credentials only through `.env` and the `legacy_mysql` connection. Keep CSRF-protected Laravel forms and Form Request validation for all state-changing flows.
