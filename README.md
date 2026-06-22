# KDO Wishlist

KDO Wishlist is a modern Laravel remake of a family gift-list application. It keeps the original product spirit: no email accounts, no passwords, quick guest access, parent-managed child lists, external wishlists, and one-click gift reservations.

The project is built as a contemporary full-stack app while preserving the legacy visual identity: deep navy, illustrated avatars, oversized editorial typography, rounded controls, and a simple family-first flow.

## Highlights

- Profile-based access without passwords.
- Guest mode for relatives and friends.
- Parent and child profile management.
- Gift ideas with optional descriptions and links.
- External list entries displayed separately from reservable gifts.
- Reservation flow that prevents duplicate reservations.
- Legacy data import from the former PHP/MySQL schema.
- Production-like SQLite snapshot for local demos.

## Tech Stack

- Laravel 13
- Inertia.js 2
- React 19
- TypeScript
- Tailwind CSS 4
- shadcn/ui-inspired components
- Vite 7
- Pest 4
- Laravel Pint
- Laravel Sail / Docker-ready development

## Product Model

KDO uses lightweight profiles instead of traditional user accounts.

- **Parent profiles** appear on the home page and can connect directly.
- **Child profiles** do not appear on the home page and are managed by linked parents.
- **Guests** enter a name and can browse profiles, reserve gifts, and cancel their own reservations.
- **Managers** can edit their own profile, their gifts, and the child profiles linked to them.

## Getting Started

Install dependencies:

```bash
composer install
npm install
```

Create the environment file and database:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

Start the application:

```bash
composer dev
```

Then open `http://127.0.0.1:8000`.

## Demo Data

The project can load a private SQLite-compatible snapshot for local demos. Keep this file out of Git because it may contain personal data:

```bash
php -r '$pdo=new PDO("sqlite:database/database.sqlite"); $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); $pdo->exec(file_get_contents("database/seeders/production_snapshot.sql"));'
```

This resets `profiles`, `profile_relations`, and `gifts`, then inserts local demo profiles, parent-child relations, external lists, and gifts.

## Legacy Import

For a real migration, configure a `legacy_mysql` connection in `.env`, then run:

```bash
php artisan wishlist:import-legacy
```

The import reads legacy `KDO_peoples`, `KDO_gifts`, and `KDO_parents` tables. It keeps legacy identifiers in `legacy_id` columns so the command can run multiple times without creating duplicates.

## Development Commands

```bash
composer dev       # Run Laravel, queue listener, and Vite together
composer check     # Run Pint, Larastan/PHPStan, and Pest
composer test      # Clear config and run Pest tests
composer coverage  # Run Pest with the minimum coverage gate
composer lint      # Check PHP formatting with Pint
composer analyse   # Run Larastan/PHPStan static analysis
composer format    # Format PHP with Pint
npm run dev        # Run Vite only
npm run check      # Run ESLint, Prettier check, and production build
npm run lint       # Lint TypeScript/React files
npm run build      # Type-check and build frontend assets
npm run typecheck  # TypeScript check only
npm run audit:prod # Audit production npm dependencies
```

## Project Structure

```text
app/                 Laravel controllers, models, requests, import command
database/migrations  Profiles, relations, gifts, sessions
database/seeders     Production-like SQL snapshot
resources/js         Inertia React pages and components
resources/css        Tailwind and legacy-inspired design system
public/images        Avatars, backgrounds, icons, favicons
tests/               Pest feature and unit tests
docs/                Functional documentation
```

## Quality

The test suite covers session access, profile management, gift CRUD, reservations, permissions, model relations, age formatting, and idempotent legacy import.

Git hooks are enabled through Husky:

- `commit-msg` validates commit messages with Conventional Commits.
- `pre-commit` formats staged files with lint-staged, Prettier, and Pint.
- `pre-push` runs `npm run check` and `composer check`.

Before opening a pull request, run:

```bash
composer test
composer lint
npm run check
npm run build
```

## Documentation

- [Functional specification](docs/FUNCTIONAL_SPEC.md)
- [Contributor guide](AGENTS.md)
- [Changelog](CHANGELOG.md)

## License

This project is open-source software released under the MIT license.
