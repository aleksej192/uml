#!/bin/bash

cd /var/www/haval

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

exec "$@"
