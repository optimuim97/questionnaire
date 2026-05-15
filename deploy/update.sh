#!/bin/bash
# ============================================================
#  update.sh — Mettre à jour l'app après un changement de code
#  Usage : bash deploy/update.sh
# ============================================================
set -e

APP_DIR="/var/www/questionnaire"
cd "${APP_DIR}"

echo "🔄 Mise en maintenance..."
php artisan down --render="errors::503" --retry=10

echo "📦 Dépendances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction -q

echo "🎨 Build des assets..."
npm ci --silent
npm run build

echo "🗄️  Migrations..."
php artisan migrate --force

echo "⚡ Optimisation..."
php artisan optimize

echo "🔒 Permissions..."
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Remise en ligne..."
php artisan up

echo ""
echo "✓ Mise à jour terminée !"
