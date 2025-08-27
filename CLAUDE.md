# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture Overview

This is a Laravel 12 application with Filament 4 for the admin interface, designed as an inventory management tool. The project uses:

- **Laravel Framework**: PHP 8.2+ web framework
- **Filament**: Laravel admin panel for CRUD operations and UI
- **Pest**: PHP testing framework
- **Vite**: Frontend build tool with Tailwind CSS 4
- **SQLite**: Default database (configured for development)

## Development Commands

### Backend (PHP/Laravel)
```bash
# Start development environment (runs all services concurrently)
composer dev
# Equivalent to: php artisan serve + php artisan queue:listen + php artisan pail + npm run dev

# Individual services
php artisan serve                    # Start Laravel dev server
php artisan queue:listen --tries=1  # Start queue worker
php artisan pail --timeout=0        # Start log viewer
php artisan migrate                  # Run database migrations
php artisan migrate:fresh --seed    # Fresh migrations with seeders
php artisan tinker                   # Laravel REPL

# Code quality
./vendor/bin/pint                    # Format code (Laravel Pint)
```

### Frontend (Node/Vite)
```bash
npm run dev    # Start Vite dev server with hot reload
npm run build  # Build assets for production
```

### Testing
```bash
# Run all tests
composer test
# Equivalent to: php artisan config:clear && php artisan test

# Run specific test types
php artisan test                     # All tests
php artisan test tests/Feature       # Feature tests only
php artisan test tests/Unit          # Unit tests only
php artisan test --filter=TestName  # Specific test
```

## Key Architecture Patterns

### Filament Integration
- Admin panel accessible at `/admin` route
- Resources auto-discovered in `app/Filament/Resources`
- Pages auto-discovered in `app/Filament/Pages` 
- Widgets auto-discovered in `app/Filament/Widgets`
- Primary color scheme uses Amber theme
- Authentication required for admin access

### Database Architecture
- Uses SQLite by default for development
- Migrations in `database/migrations/`
- Factories in `database/factories/`
- Seeders in `database/seeders/`
- In-memory SQLite for testing

### Testing Structure
- Pest PHP testing framework
- Feature tests in `tests/Feature/`
- Unit tests in `tests/Unit/`
- Test configuration uses array drivers for cache/session/mail
- Database uses in-memory SQLite for tests

### Asset Pipeline
- Vite for build system with Laravel integration
- Tailwind CSS 4 for styling
- Entry points: `resources/css/app.css`, `resources/js/app.js`
- Hot reload enabled in development

## Project Structure Notes

- Standard Laravel directory structure
- Only User model currently exists - inventory models to be created
- No custom Filament resources yet - admin interface ready for inventory CRUD
- Concurrency setup for development workflow (server + queue + logs + vite)