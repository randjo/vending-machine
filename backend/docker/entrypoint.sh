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
for var in \
    APP_NAME \
    APP_ENV \
    APP_DEBUG \
    APP_URL \
    DB_CONNECTION \
    DB_HOST \
    DB_PORT \
    DB_DATABASE \
    DB_USERNAME \
    DB_PASSWORD \
    SESSION_DRIVER \
    SESSION_DOMAIN \
    SESSION_SECURE_COOKIE \
    CACHE_STORE \
    QUEUE_CONNECTION \
    SANCTUM_STATEFUL_DOMAINS \
    CORS_ALLOWED_ORIGINS
do
    eval value=\$$var

    if [ -n "$value" ]; then
        set_env "$var" "$value"
    fi
done

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

echo "Database available."

php artisan config:clear || true

if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

if [ "${RUN_SEEDERS}" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

if [ "${APP_ENV}" = "production" ]; then
    echo "Optimizing Laravel..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "Starting Laravel development server..."
exec php artisan serve --host=0.0.0.0 --port=8000
