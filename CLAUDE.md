# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Therco Base** is a school management and accounting web application (Unifiedtransform v2.X) built on Laravel 11 with a Bootstrap 4 frontend. It handles academic sessions, teacher/student management, grading, attendance, and more.

## Commands

### PHP / Laravel

```bash
php artisan test                              # Run all tests
php artisan test tests/Feature/SomeTest.php  # Run a single test file
php artisan test --filter=methodName         # Run a specific test method
php artisan migrate:fresh --seed             # Reset and seed database
php artisan serve                            # Run local dev server (without Docker)
```

### Frontend Assets (Laravel Mix / Webpack)

```bash
npm run dev          # One-time development build
npm run watch        # Watch and rebuild on changes
npm run hot          # Hot module reloading
npm run prod         # Production build
```

### Docker

```bash
docker-compose up -d        # Start all containers (Nginx, PHP, MySQL, MailHog, PHPMyAdmin)
docker exec -it app sh      # Open shell in the PHP app container
```

Run all `php artisan` and `composer` commands inside the `app` container when using Docker.

## Architecture

### Repository Pattern

The codebase enforces a strict **Interface → Repository → Controller** dependency chain:

1. **Interfaces** (`app/Interfaces/`) — contracts (e.g., `UserInterface`)
2. **Repositories** (`app/Repositories/`) — concrete implementations (e.g., `UserRepository`)
3. **Service Providers** (`app/Providers/`) — bind each interface to its repository via the IoC container
4. **Controllers** (`app/Http/Controllers/`) — depend on interfaces (injected), never on repositories directly
5. **Models** (`app/Models/`) — Eloquent ORM; used inside repositories only

When adding a new feature, create/update all four layers and register the binding in the appropriate service provider.

### Shared Traits

Three traits are used across controllers and repositories:

- `SchoolSession` — helpers for reading the active school session from the database
- `Base64ToFile` — converts base64-encoded image data to stored files
- `AssignedTeacherCheck` — verifies a teacher is assigned to the course they are acting on

### Role-Based Access Control

Uses **Spatie Laravel Permission**. Three roles exist: `Admin`, `Teacher`, `Student`. Route groups and controller gates are role-scoped. All school routes live under the `/school` prefix in `routes/web.php`.

### Testing

- Framework: PHPUnit 10.5
- Test database: SQLite in-memory (`:memory:`) — configured in `phpunit.xml`
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Base class: `tests/TestCase.php` using the `CreatesApplication` trait

### Frontend

Assets are compiled via **Laravel Mix** (`webpack.mix.js`). The stack is jQuery + Bootstrap 4 + Axios + Sass. Blade templates live in `resources/views/`.

### Key Environment Variables

Set these in `.env` (copy from `.env.example`):

| Variable | Notes |
|---|---|
| `DB_HOST` | Use `db` for Docker, `127.0.0.1` for local |
| `DB_DATABASE` | `unifiedtransform` by default |
| `MAIL_MAILER` | MailHog available at port 1025 in Docker |
