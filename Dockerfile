FROM php:8.3-cli-alpine

WORKDIR /app

# Dépendances système + Node.js
RUN apk add --no-cache \
    curl zip unzip git bash \
    nodejs npm \
    mysql-client \
    oniguruma-dev
# Extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath


# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dépendances PHP (séparé pour profiter du cache Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction

# Dépendances Node (séparé pour cache)
COPY package.json package-lock.json vite.config.js ./
COPY resources/css resources/css
COPY resources/js  resources/js
RUN npm ci && npm run build

# Code source complet
COPY . .

# Finaliser Composer + permissions
RUN composer dump-autoload --optimize \
 && chmod -R 775 storage bootstrap/cache \
 && mkdir -p storage/logs storage/framework/{sessions,views,cache}

EXPOSE 8000

CMD ["bash", "start.sh"]
