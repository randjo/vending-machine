# Vending Machine

A full-stack vending machine application with a public customer interface and a protected admin panel. The backend implements vending logic in a domain-driven PHP layer, exposes a REST API via Laravel, and persists machine state in MySQL. The frontend is a React SPA that talks to the API over cookie-based authentication.

## Features

### Public vending interface (`/`)

- Browse available drinks with formatted prices
- Insert coins and view the current balance
- Purchase drinks and receive change
- View the machine display log (last messages from the vending flow)
- Collect change when the session ends

### Admin panel (`/admin`)

- Session-based login with Laravel Sanctum
- **Dashboard** — restart the vending machine and reset its runtime state
- **Drinks** — create, edit, and delete drinks
- **Coins** — manage accepted coin denominations
- **Currency** — configure currency sign, spacing, and position (before/after amount)
- **Display** — inspect the machine display message log

### Backend domain logic

- Vending machine core with wallet, change calculation, and display messaging
- Prices and coin values stored in cents internally
- Configurable currency formatting (sign, space, position)
- Persistent machine state (balance, inserted amount, display messages)
- Feature tests covering purchases, change, invalid coins, insufficient funds, and more

## Tech stack

| Layer       | Technologies                                 |
| ----------- | -------------------------------------------- |
| Backend     | PHP 8.3, Laravel 13, Laravel Sanctum         |
| Database    | MySQL                                        |
| Testing     | Pest                                         |
| Frontend    | React 19, TypeScript, Vite 8, Tailwind CSS 4 |
| HTTP client | Axios (cookie + CSRF support)                |
| Routing     | React Router 7                               |
| UI          | SweetAlert2, custom UI components            |

## Requirements

- PHP 8.3+
- Composer
- Node.js 20+ and npm
- MySQL 8+

## Project structure

```
vending-machine/
├── backend/                 # Laravel API (see backend/README.md)
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── tests/
└── frontend/                # React SPA (see frontend/README.md)
```

## Configuration

### 1. Clone and install dependencies

```bash
cd backend && composer install && cd ..
cd frontend && npm install && cd ..
```

### 2. Backend environment

Copy the example environment file and generate an application key:

```bash
cp backend/.env.example backend/.env
cd backend && php artisan key:generate && cd ..
```

### 3. Database

Create a MySQL database and update `backend/.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vending_machine
DB_USERNAME=vending_machine
DB_PASSWORD=vending_machine_password
```

Run migrations and seed the default admin user:

```bash
cd backend
php artisan migrate
php artisan db:seed
cd ..
```

The seeder creates:

| Field    | Value            |
| -------- | ---------------- |
| Email    | `admin@test.com` |
| Password | `admin123`       |

Migrations also seed a default currency record (`лв`, position after amount).

### 4. Sanctum and CORS

For local development with the React dev server, ensure `backend/.env` contains:

```env
APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

CORS is configured in `backend/config/cors.php` to allow `http://localhost:5173` with credentials.

### 5. Frontend environment

Create `frontend/.env` from the example:

```bash
cp frontend/.env.example frontend/.env
```

```env
VITE_API_URL=http://localhost:8000
```

## Running the application

### With Docker (recommended)

See [DOCKER.md](DOCKER.md) for the full guide.

```bash
docker compose up --build
```

### Without Docker

**Backend:**

```bash
cd backend
php artisan serve
```

**Frontend:**

```bash
cd frontend
npm run dev
```

### URLs

| URL                                 | Description            |
| ----------------------------------- | ---------------------- |
| `http://localhost:5173/`            | Public vending machine |
| `http://localhost:5173/admin/login` | Admin login            |
| `http://localhost:5173/admin`       | Admin dashboard        |

## API overview

All API routes are prefixed with `/api`.

### Public vending (`/api/vending`)

| Method | Endpoint   | Description                      |
| ------ | ---------- | -------------------------------- |
| GET    | `/drinks`  | List available drinks            |
| GET    | `/coins`   | List accepted coin denominations |
| POST   | `/coins`   | Insert a coin                    |
| POST   | `/buy`     | Purchase a drink                 |
| GET    | `/change`  | Return change                    |
| GET    | `/amount`  | View inserted amount             |
| GET    | `/balance` | View current balance             |
| GET    | `/display` | Get display messages             |

### Admin (`/api/admin`)

| Method              | Endpoint         | Description              |
| ------------------- | ---------------- | ------------------------ |
| POST                | `/login`         | Authenticate admin       |
| POST                | `/logout`        | End session              |
| GET                 | `/user`          | Get current user         |
| GET/POST/PUT/DELETE | `/drinks`        | Manage drinks            |
| GET/POST/PUT/DELETE | `/coins`         | Manage coins             |
| GET                 | `/currency`      | Get currency settings    |
| PUT                 | `/currency/{id}` | Update currency settings |
| GET                 | `/display`       | View display log         |
| POST                | `/restart`       | Reset machine state      |

Authentication uses Laravel Sanctum session cookies. The frontend requests a CSRF cookie from `/sanctum/csrf-cookie` before login.

## Testing

Tests use an in-memory SQLite database (configured in `backend/phpunit.xml`).

```bash
cd backend
composer test
# or
php artisan test
```

## Initial data

After migration, add drinks and coins through the admin panel before using the vending interface. Example coin values: `0.05`, `0.10`, `0.20`, `0.50`, `1.00` BGN.

Prices are submitted in BGN from the admin UI and stored as cents in the database.

## Demo script

A standalone PHP demo of the domain layer is available:

```bash
cd backend
php demo.php
```

## License

MIT
