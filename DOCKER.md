# Docker Setup

Run the full stack (MySQL, Laravel API, React frontend) with Docker Compose.

## Services

| Service    | Container          | Host URL                | Description             |
| ---------- | ------------------ | ----------------------- | ----------------------- |
| `mysql`    | `vending-mysql`    | `localhost:3307`        | MySQL 8.4 database      |
| `backend`  | `vending-backend`  | `http://localhost:8000` | Laravel API             |
| `frontend` | `vending-frontend` | `http://localhost:5173` | React + Vite dev server |

## Requirements

- Docker Desktop or Docker Engine 24+
- Docker Compose v2

## Quick start

From the project root:

```bash
docker compose up --build
```

First run will:

1. Build backend and frontend images
2. Start MySQL and wait until it is healthy
3. Install Composer and npm dependencies inside containers
4. Generate `APP_KEY` if missing
5. Run migrations and seed the default admin user
6. Start Laravel (`php artisan serve`) and Vite dev server

Open:

- Vending UI: `http://localhost:5173`
- Admin login: `http://localhost:5173/admin/login`
- API health: `http://localhost:8000/up`

Default admin credentials:

| Field    | Value            |
| -------- | ---------------- |
| Email    | `admin@test.com` |
| Password | `admin123`       |

## Common commands

### Using the Makefile (recommended)

If you prefer shorter commands, use the root `Makefile`:

```bash
make up            # docker compose up -d
make rebuild       # docker compose up -d --build
make ps            # docker compose ps
make logs          # docker compose logs -f
make backend-logs  # docker compose logs -f backend
make frontend-logs # docker compose logs -f frontend
make db-logs       # docker compose logs -f mysql
make down          # docker compose down
make clean         # docker compose down -v
```

### Using Docker Compose directly

Start in background:

```bash
docker compose up -d --build
```

Stop containers:

```bash
docker compose down
```

Stop and remove database volume:

```bash
docker compose down -v
```

View logs:

```bash
docker compose logs -f
docker compose logs -f backend
docker compose logs -f frontend
```

Rebuild after Dockerfile changes:

```bash
docker compose build --no-cache
docker compose up -d
```

Run backend tests inside the container:

```bash
docker compose exec backend php artisan test
```

Run migrations manually:

```bash
docker compose exec backend php artisan migrate
```

Open a shell in the backend container:

```bash
docker compose exec backend sh
```

## Files created

```
vending-machine/
├── docker-compose.yml              # Orchestrates all services
├── .dockerignore                   # Excludes files from build context
├── backend/
│   ├── Dockerfile                  # PHP 8.3 image with Composer
│   ├── .dockerignore
│   └── docker/entrypoint.sh        # Installs deps, migrates, starts API
└── frontend/
    ├── Dockerfile                  # Node 22 image
    ├── .dockerignore
    └── docker/entrypoint.sh        # Installs deps, starts Vite
```

## Environment variables

Docker Compose injects the main backend settings directly. The frontend uses:

```env
VITE_API_URL=http://localhost:8000
```

Important values for local Docker:

```env
DB_HOST=mysql
SANCTUM_STATEFUL_DOMAINS=localhost:5173
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

The browser runs on your host machine, so API and frontend URLs stay as `localhost`.

## Volumes

| Volume                  | Purpose                                                    |
| ----------------------- | ---------------------------------------------------------- |
| `mysql_data`            | Persistent MySQL data                                      |
| `backend_vendor`        | Composer dependencies (avoids overwriting from bind mount) |
| `frontend_node_modules` | npm dependencies (avoids overwriting from bind mount)      |

Source code is bind-mounted from `./backend` and `./frontend`, so code changes are reflected immediately.

## Troubleshooting

**Port already in use**

Change host ports in `docker-compose.yml`, for example:

```yaml
ports:
    - "8000:8000" # backend
    - "5173:5173" # frontend
```

If you change the frontend port, also update:

- `SANCTUM_STATEFUL_DOMAINS`
- `CORS_ALLOWED_ORIGINS`
- `VITE_API_URL` (only if backend port changes)

**Backend keeps restarting**

Check logs:

```bash
docker compose logs backend
```

Common causes: MySQL not ready yet, migration error, missing PHP extension.

**Frontend cannot reach API**

Ensure `VITE_API_URL` points to `http://localhost:8000` (the host-mapped backend URL).

**Reset everything**

```bash
docker compose down -v
docker compose up --build
```
