# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0] - Unreleased

### Added

- Laravel/Inertia React wishlist application with parent, child, and guest flows.
- Gift and external-list management with reservation and cancellation rules.
- Former-system import command for reproducible migration from MySQL data.
- Pest feature and unit tests for sessions, profiles, gifts, reservations, relations, age formatting, and import idempotency.
- ESLint, Prettier, Laravel Pint, Larastan/PHPStan, npm audit, Composer audit, GitHub Actions CI, and Dependabot.
- Git hooks for Conventional Commits, pre-commit formatting, and pre-push lint/test checks.
- Public README, contributor guide, and functional specification.

### Changed

- Rebuilt the historical PHP/Twig application as a modern Laravel, Inertia, React, TypeScript, Tailwind, and Vite application.
- Preserved the former visual identity with modern frontend implementation patterns.
