#!/bin/sh
set -e

cd /app

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d node_modules ] || [ -z "$(ls -A node_modules 2>/dev/null)" ]; then
    echo "Installing npm dependencies..."
    npm install
fi

echo "Starting Vite development server..."
exec npm run dev -- --host 0.0.0.0 --port 5173
