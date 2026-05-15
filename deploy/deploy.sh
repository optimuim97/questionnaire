#!/bin/bash
# ============================================================
#  deploy.sh — Déploiement Questionnaire avec sous-domaine
#  Usage : bash deploy.sh devis.monagence.com
# ============================================================
set -e

APP_DIR="/var/www/questionnaire"
APP_USER="www-data"
DOMAIN="${1:-}"

if [ -z "${DOMAIN}" ]; then
    echo "❌  Usage : bash deploy.sh devis.monagence.com"
    exit 1
fi

# Charger le mot de passe MySQL généré par setup-server.sh
[ -f /root/.deploy_vars ] && source /root/.deploy_vars
DB_PASS="${DB_PASS_GENERATED:-changeme}"

echo ""
echo "=================================================="
echo "  Déploiement Questionnaire → ${DOMAIN}"
echo "=================================================="
echo ""

# ── 1. Répertoire ────────────────────────────────────────────
echo "[1/8] Préparation du répertoire..."
mkdir -p "${APP_DIR}"
cd "${APP_DIR}"

# ── 2. Fichier .env ──────────────────────────────────────────
echo "[2/8] Configuration de l'environnement..."
if [ ! -f "${APP_DIR}/.env" ]; then
cat > "${APP_DIR}/.env" << EOF
APP_NAME="Questionnaire"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://${DOMAIN}

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=questionnaire
DB_USERNAME=questionnaire
DB_PASSWORD=${DB_PASS}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.${DOMAIN#*.}

CACHE_STORE=file

SANCTUM_STATEFUL_DOMAINS=${DOMAIN}
EOF
fi

# ── 3. Dépendances PHP ───────────────────────────────────────
echo "[3/8] Dépendances PHP (Composer)..."
composer install --no-dev --optimize-autoloader --no-interaction -q
php artisan key:generate --force

# ── 4. Assets Vue.js ─────────────────────────────────────────
echo "[4/8] Build des assets Vue.js..."
npm ci --silent
npm run build

# ── 5. Base de données ───────────────────────────────────────
echo "[5/8] Migration et seed..."
php artisan migrate --force
php artisan db:seed --force

# ── 6. Optimisation ──────────────────────────────────────────
echo "[6/8] Optimisation Laravel..."
php artisan optimize
chmod -R 775 storage bootstrap/cache
chown -R "${APP_USER}:${APP_USER}" "${APP_DIR}"

# ── 7. Nginx — HTTP (avant SSL) ──────────────────────────────
echo "[7/8] Configuration Nginx..."
cat > /etc/nginx/sites-available/questionnaire << NGINX
server {
    listen 80;
    server_name ${DOMAIN};

    root /var/www/questionnaire/public;
    index index.php;

    charset utf-8;
    client_max_body_size 20M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
NGINX

ln -sf /etc/nginx/sites-available/questionnaire /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
systemctl restart php8.3-fpm

# ── 8. SSL Let's Encrypt ─────────────────────────────────────
echo "[8/8] Certificat SSL Let's Encrypt..."
apt-get install -y -qq certbot python3-certbot-nginx

certbot --nginx \
    --non-interactive \
    --agree-tos \
    --redirect \
    --email "admin@${DOMAIN#*.}" \
    -d "${DOMAIN}"

# Renouvellement automatique (déjà géré par le timer systemd de certbot)
systemctl enable certbot.timer

# Mettre à jour APP_URL en https maintenant que SSL est actif
php artisan config:clear
php artisan optimize

echo ""
echo "=================================================="
echo "  ✓ Déploiement terminé !"
echo ""
echo "  🌐 Questionnaire client : https://${DOMAIN}/q/1"
echo "  🔐 Administration       : https://${DOMAIN}/admin"
echo "     Email    : admin@questionnaire.com"
echo "     Mot de passe : admin1234"
echo ""
echo "  🔒 SSL : actif (renouvellement auto)"
echo "  ⚠️  Changez le mot de passe admin dès que possible !"
echo "=================================================="
