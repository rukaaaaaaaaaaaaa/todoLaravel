FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash git curl libzip-dev oniguruma-dev \
    icu-dev libpng-dev libjpeg-turbo-dev libwebp-dev libxml2-dev \
    zlib-dev autoconf make g++ \
  && docker-php-ext-configure gd --with-jpeg --with-webp \
  && docker-php-ext-install pdo_mysql mbstring zip intl gd xml \
  && apk del autoconf make g++

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html