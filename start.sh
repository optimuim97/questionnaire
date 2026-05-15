#!/bin/bash
set -e

echo "▶ Waiting for MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
  sleep 2
done
echo "✓ MySQL ready"

echo "▶ Migrate..."
php artisan migrate --force

echo "▶ Seed..."
php artisan db:seed --force 2>&1 || echo "Seed skipped (already done)"

echo "▶ Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✓ Starting on port ${PORT:-8000}"
exec php -S "0.0.0.0:${PORT:-8000}" -t public
