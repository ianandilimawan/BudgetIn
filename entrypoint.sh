#!/usr/bin/env bash
set -e

mkdir -p storage/{app/public,framework/{cache/data,sessions,testing,views},logs}
chown -R www-data:www-data storage
chmod -R 755 storage

php artisan storage:link | true

php-fpm -D

nginx -g "daemon off;"
