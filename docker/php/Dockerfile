FROM php:8.5-fpm

ARG UID=1000
ARG GID=1000

# System dependencies (run as root)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
        --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Change www-data UID/GID AFTER installing packages
RUN usermod -u $UID www-data && groupmod -g $GID www-data \
    && chown -R www-data:www-data /var/www

# Switch to www-data for runtime
USER www-data
