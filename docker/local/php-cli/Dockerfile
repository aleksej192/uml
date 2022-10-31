FROM php:8.1

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y libxml2-dev libpng-dev libzip-dev libonig-dev git unzip wget libpq-dev libjpeg-dev libfreetype6-dev

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

RUN docker-php-ext-install soap gd exif zip pdo pdo_pgsql pgsql mbstring pcntl

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN wget https://getcomposer.org/composer-2.phar && chmod +x composer-2.phar && mv composer-2.phar /usr/local/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data && usermod --shell /bin/bash www-data && chown www-data:www-data -R /var/www/

WORKDIR /app
