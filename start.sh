#!/bin/bash
set -e

php artisan migrate --force
php artisan db:seed --force
php artisan optimize

exec php -S "0.0.0.0:${PORT:-8000}" -t public
