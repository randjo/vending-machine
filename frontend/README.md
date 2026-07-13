# Vending Machine — Frontend

React single-page application for the vending machine project. It provides a public vending interface and a protected admin panel, both consuming the Laravel API.

## Tech stack

- **React 19** with TypeScript
- **Vite 8** — dev server and production bundler
- **React Router 7** — client-side routing
- **Tailwind CSS 4** — styling via `@tailwindcss/vite`
- **Axios** — HTTP client with credentials and CSRF support
- **TanStack React Query** — server state (where used)
- **SweetAlert2** — confirmation dialogs and notifications
- **clsx** — conditional class names

## Features

### Public routes

| Route | Page    | Description                                            |
| ----- | ------- | ------------------------------------------------------ |
| `/`   | Vending | Buy drinks, Insert coins, Collect change, View display |

### Admin routes (protected)

| Route             | Page      | Description                             |
| ----------------- | --------- | --------------------------------------- |
| `/admin/login`    | Login     | Admin authentication                    |
| `/admin`          | Dashboard | Restart the vending machine             |
| `/admin/drinks`   | Drinks    | CRUD for drinks                         |
| `/admin/coins`    | Coins     | CRUD for coin denominations             |
| `/admin/currency` | Currency  | Edit currency sign, space, and position |
| `/admin/display`  | Display   | View machine display log                |

Authentication is handled by `AuthContext` using Laravel Sanctum session cookies. Protected routes redirect unauthenticated users to `/admin/login`.

## Requirements

- Node.js 20+
- npm
- Laravel API running at `http://localhost:8000` (see root [README.md](../README.md) and [backend/README.md](../backend/README.md))

## Configuration

Create a local environment file:

```bash
cp .env.example .env
```

```env
VITE_API_URL=http://localhost:8000
```

`VITE_API_URL` must match the Laravel `APP_URL`. The API client (`src/api/api.ts`) sends requests with:

- `withCredentials: true` — includes session cookies
- `withXSRFToken: true` — sends the CSRF token for Sanctum

Ensure the backend has `SANCTUM_STATEFUL_DOMAINS=localhost:5173` and CORS allows `http://localhost:5173`.

## Getting started

```bash
npm install
npm run dev
```

Open `http://localhost:5173`.

Default admin credentials (from backend seeder):

- Email: `admin@test.com`
- Password: `admin123`

## Scripts

| Command           | Description                         |
| ----------------- | ----------------------------------- |
| `npm run dev`     | Start Vite dev server on port 5173  |
| `npm run build`   | Type-check and build for production |
| `npm run preview` | Preview the production build        |
| `npm run lint`    | Run ESLint                          |

## Project structure

```
frontend/
├── public/                  # Static assets
├── src/
│   ├── api/                 # Axios instance
│   ├── auth/                # AuthContext, route guards
│   ├── components/
│   │   ├── coins/           # Coin management forms
│   │   ├── currency/        # Currency settings form
│   │   ├── drinks/          # Drink management forms
│   │   ├── layout/          # Header, Sidebar
│   │   ├── ui/              # Button, Card, Input, Modal
│   │   └── vending/         # DrinkCard, CoinButton, MachineDisplay
│   ├── layouts/             # AdminLayout, VendingLayout
│   ├── pages/               # Route pages
│   ├── router/              # React Router configuration
│   ├── types/               # Shared TypeScript types
│   └── utils/               # API error helpers, confirm dialogs
├── .env.example
└── vite.config.ts
```

## API integration

All requests go through `src/api/api.ts`. Example endpoints used by the app:

```text
GET  /sanctum/csrf-cookie     # CSRF token (before login)
POST /api/admin/login         # Admin login
GET  /api/admin/user          # Current user
GET  /api/vending/drinks      # Public drink list
POST /api/vending/buy         # Purchase a drink
```

Admin CRUD operations use `/api/admin/drinks`, `/api/admin/coins`, and `/api/admin/currency`.

## Production build

```bash
npm run build
```

Output is written to `frontend/dist/`. Serve it with any static file server, or integrate it into your deployment pipeline. Set `VITE_API_URL` to the production API URL before building.

## Development notes

- TypeScript uses `verbatimModuleSyntax` — import types with `import type`.
- Form submit handlers should use `SubmitEvent` from React (not the DOM global).
- The dev server runs on port `5173` by default (Vite default).
