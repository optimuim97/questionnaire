#!/bin/bash
set -e

# ── APP_KEY ────────────────────────────────────────────────────────────────
if [ -z "${APP_KEY}" ]; then
    echo "⚠  APP_KEY manquant — génération automatique..."
    php artisan key:generate --force
    echo "   ⚠  Copiez la clé générée dans les variables Railway pour la rendre permanente!"
fi

# ── WAIT FOR MYSQL ─────────────────────────────────────────────────────────
echo "▶ Attente de MySQL (${DB_HOST}:${DB_PORT:-3306})..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "  MySQL pas encore prêt, retry dans 2s..."
    sleep 2
done
echo "✓ MySQL prêt"

# ── MIGRATIONS ─────────────────────────────────────────────────────────────
echo "▶ Migrations..."
php artisan migrate --force

# ── SEED ───────────────────────────────────────────────────────────────────
echo "▶ Seed..."
php artisan db:seed --force 2>&1 || echo "  Seed ignoré (déjà effectué)"

# ── CACHE ──────────────────────────────────────────────────────────────────
echo "▶ Cache config..."
php artisan config:cache

echo "▶ Cache routes..."
php artisan route:cache

echo "▶ Cache vues..."
php artisan view:cache

# ── START ──────────────────────────────────────────────────────────────────
echo "✓ Démarrage sur 0.0.0.0:${PORT:-8000}"
exec php -S "0.0.0.0:${PORT:-8000}" -t public
