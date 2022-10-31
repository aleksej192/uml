FROM php:8.1-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y libxml2-dev libpng-dev libzip-dev libonig-dev git unzip libpq-dev libjpeg-dev libfreetype6-dev

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

RUN docker-php-ext-install soap gd exif zip pdo pdo_pgsql pgsql mbstring pcntl

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data && usermod --shell /bin/bash www-data && chown www-data:www-data -R /var/www/

WORKDIR /app
