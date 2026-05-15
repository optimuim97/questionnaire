#!/bin/bash
set -e

echo "=== Migrate ==="
php artisan migrate --force

echo "=== Seed ==="
php artisan db:seed --force

echo "=== Optimize ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Starting server on port ${PORT:-8000} ==="
exec php -S "0.0.0.0:${PORT:-8000}" -t public
