#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

set_env() {
    key="$1"
    value="$2"

    # if key exists -> replace, else append
    if grep -q "^${key}=" .env; then
        # POSIX-friendly: create backup then remove
        sed -i.bak "s|^${key}=.*|${key}=${value}|" .env
        rm -f .env.bak
    else
        printf "\n%s=%s\n" "$key" "$value" >> .env
    fi
}

# Ensure Docker env wins over a stale .env (e.g. DB_HOST=127.0.0.1)
if [ -n "${APP_URL}" ]; then set_env "APP_URL" "${APP_URL}"; fi
if [ -n "${DB_CONNECTION}" ]; then set_env "DB_CONNECTION" "${DB_CONNECTION}"; fi
if [ -n "${DB_HOST}" ]; then set_env "DB_HOST" "${DB_HOST}"; fi
if [ -n "${DB_PORT}" ]; then set_env "DB_PORT" "${DB_PORT}"; fi
if [ -n "${DB_DATABASE}" ]; then set_env "DB_DATABASE" "${DB_DATABASE}"; fi
if [ -n "${DB_USERNAME}" ]; then set_env "DB_USERNAME" "${DB_USERNAME}"; fi
if [ -n "${DB_PASSWORD}" ]; then set_env "DB_PASSWORD" "${DB_PASSWORD}"; fi
if [ -n "${SESSION_DRIVER}" ]; then set_env "SESSION_DRIVER" "${SESSION_DRIVER}"; fi
if [ -n "${CACHE_STORE}" ]; then set_env "CACHE_STORE" "${CACHE_STORE}"; fi
if [ -n "${QUEUE_CONNECTION}" ]; then set_env "QUEUE_CONNECTION" "${QUEUE_CONNECTION}"; fi
if [ -n "${SANCTUM_STATEFUL_DOMAINS}" ]; then set_env "SANCTUM_STATEFUL_DOMAINS" "${SANCTUM_STATEFUL_DOMAINS}"; fi
if [ -n "${CORS_ALLOWED_ORIGINS}" ]; then set_env "CORS_ALLOWED_ORIGINS" "${CORS_ALLOWED_ORIGINS}"; fi

if [ ! -d vendor ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

echo "Waiting for MySQL..."
until php -r "
    \$host = getenv('DB_HOST') ?: 'mysql';
    \$port = getenv('DB_PORT') ?: '3306';
    \$database = getenv('DB_DATABASE') ?: 'vending_machine';
    \$username = getenv('DB_USERNAME') ?: 'vending_machine';
    \$password = getenv('DB_PASSWORD') ?: 'vending_machine_password';
    new PDO(
        \"mysql:host={\$host};port={\$port};dbname={\$database}\",
        \$username,
        \$password
    );
" 2>/dev/null; do
    sleep 2
done

php artisan config:clear || true

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

echo "Starting Laravel development server..."
exec php artisan serve --host=0.0.0.0 --port=8000
