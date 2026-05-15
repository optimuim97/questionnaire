#!/bin/bash
# ============================================================
#  setup-server.sh — Configuration initiale du VPS Ubuntu 22/24
#  Exécuter en tant que ROOT : bash setup-server.sh
# ============================================================
set -e

echo ""
echo "=================================================="
echo "  Installation serveur — Questionnaire App"
echo "=================================================="
echo ""

# ── 1. Mise à jour système ───────────────────────────────────
echo "[1/8] Mise à jour du système..."
apt-get update -qq && apt-get upgrade -y -qq

# ── 2. Dépendances de base ───────────────────────────────────
echo "[2/8] Installation des dépendances de base..."
apt-get install -y -qq \
    curl wget git unzip zip \
    software-properties-common \
    ufw fail2ban

# ── 3. PHP 8.3 + extensions Laravel ─────────────────────────
echo "[3/8] Installation de PHP 8.3..."
add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1
apt-get update -qq
apt-get install -y -qq \
    php8.3 php8.3-fpm php8.3-cli \
    php8.3-mysql php8.3-sqlite3 \
    php8.3-mbstring php8.3-xml php8.3-bcmath \
    php8.3-curl php8.3-zip php8.3-intl \
    php8.3-tokenizer php8.3-fileinfo

# ── 4. MySQL ─────────────────────────────────────────────────
echo "[4/8] Installation de MySQL..."
apt-get install -y -qq mysql-server

# Sécuriser MySQL et créer la BDD
DB_PASS=$(openssl rand -base64 20 | tr -dc 'A-Za-z0-9' | head -c16)
mysql -e "CREATE DATABASE IF NOT EXISTS questionnaire CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'questionnaire'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON questionnaire.* TO 'questionnaire'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo "DB_PASS_GENERATED=${DB_PASS}" >> /root/.deploy_vars

# ── 5. Composer ──────────────────────────────────────────────
echo "[5/8] Installation de Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer > /dev/null 2>&1

# ── 6. Node.js 22 ────────────────────────────────────────────
echo "[6/8] Installation de Node.js 22..."
curl -fsSL https://deb.nodesource.com/setup_22.x | bash - > /dev/null 2>&1
apt-get install -y -qq nodejs

# ── 7. Nginx ─────────────────────────────────────────────────
echo "[7/8] Installation de Nginx..."
apt-get install -y -qq nginx

# ── 8. Firewall ──────────────────────────────────────────────
echo "[8/8] Configuration du firewall..."
ufw --force enable
ufw allow OpenSSH
ufw allow 'Nginx Full'

echo ""
echo "=================================================="
echo "  ✓ Serveur prêt !"
echo "  Mot de passe MySQL généré : ${DB_PASS}"
echo "  (Sauvegardé dans /root/.deploy_vars)"
echo "=================================================="
