#!/bin/bash
set -e

# If SQLite DB is on the mounted volume, copy it into the app
if [ -f "/data/database.sqlite" ]; then
    cp /data/database.sqlite database/database.sqlite
fi

# Ensure database directory and file exist
mkdir -p database
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate --force || true
php artisan db:seed --force || true

# If persistent volume exists, sync database backup
if [ -d "/data" ]; then
    cp database/database.sqlite /data/database.sqlite || true
fi

# Optimize cache
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

PORT="${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="$PORT"
