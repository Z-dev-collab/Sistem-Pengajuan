#!/bin/bash
set -e

# If SQLite DB is on the mounted volume, symlink it into the app
if [ -f "/data/database.sqlite" ]; then
    cp /data/database.sqlite database/database.sqlite
fi

# Run migrations
php artisan migrate --force || true

# Start the server
php artisan serve --host=0.0.0.0 --port=8000
