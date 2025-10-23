FROM php:8.3-fpm

# Extensiones necesarias de Laravel + MySQL + Redis
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Redis (phpredis)
RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_CACHE_DIR=/tmp/composer-cache

WORKDIR /var/www/html

# Opcional: acelerar autoload
ENV COMPOSER_ALLOW_SUPERUSER=1

# Entrypoint ligero: instalar deps si faltan y fijar permisos
CMD bash -lc '\
  if [ ! -d vendor ]; then composer install --no-interaction; fi && \
  php artisan key:generate --force || true && \
  chown -R www-data:www-data storage bootstrap/cache && \
  php-fpm -F \
'
