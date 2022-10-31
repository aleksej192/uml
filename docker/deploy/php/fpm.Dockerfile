FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y libpq-dev libxml2-dev libzip-dev git

RUN docker-php-ext-install pdo pdo_pgsql pgsql soap zip

COPY docker/deploy/php/php.ini "$PHP_INI_DIR/php.ini"

RUN mkdir -p /var/www/haval

RUN chmod a+rw -R /var/www/haval

WORKDIR /var/www/haval

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev && composer dump-autoload

COPY docker/deploy/php/entrypoint.sh /root/entrypoint.sh

WORKDIR /var/www/haval/public

ENTRYPOINT ["bash", "/root/entrypoint.sh"]
CMD ["php-fpm", "-F"]
