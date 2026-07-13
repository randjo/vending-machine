# Vending Machine — Backend

Laravel API for the vending machine application. See the root [README.md](../README.md) for the full project overview.

## Tech stack

- PHP 8.3
- Laravel 13
- Laravel Sanctum (session-based API auth)
- MySQL
- Pest (testing)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Configure `backend/.env` with your database credentials and:

```env
APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

## Running

```bash
php artisan serve
```

Or use the Composer dev script (starts server, queue worker, logs, and Vite for Laravel assets):

```bash
composer dev
```

## Testing

```bash
composer test
# or
php artisan test
```

## Project structure

```
backend/
├── app/
│   ├── Domain/Vending/      # Core vending machine domain logic
│   ├── Http/Controllers/    # API controllers
│   ├── Repositories/        # Data access layer
│   └── Services/            # Application services
├── database/migrations/     # Schema and default currency seed
├── routes/api.php           # API routes
└── tests/Feature/           # Pest feature tests
```

## Demo script

```bash
php demo.php
```
