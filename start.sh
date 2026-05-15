#!/bin/bash
set -e

# ── APP_KEY ────────────────────────────────────────────────────────────────
if [ -z "${APP_KEY}" ]; then
    echo "⚠  APP_KEY manquant — génération automatique..."
    php artisan key:generate --force
fi

# ── WAIT FOR POSTGRES ──────────────────────────────────────────────────────
echo "▶ Attente de PostgreSQL..."
until php -r "new PDO('pgsql:host=${DB_HOST};port=${DB_PORT:-5432};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "  PostgreSQL pas encore prêt, retry dans 2s..."
    sleep 2
done
echo "✓ PostgreSQL prêt"

# ── MIGRATIONS ─────────────────────────────────────────────────────────────
echo "▶ Migrations..."
php artisan migrate --force

# ── SEED ───────────────────────────────────────────────────────────────────
echo "▶ Seed..."
php artisan db:seed --force 2>&1 || echo "  Seed ignoré (déjà effectué)"

# ── CACHE ──────────────────────────────────────────────────────────────────
echo "▶ Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── START ──────────────────────────────────────────────────────────────────
echo "✓ Démarrage sur 0.0.0.0:${PORT:-8080}"
exec php -S "0.0.0.0:${PORT:-8080}" -t public
