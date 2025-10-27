#!/bin/sh
set -e

cp .env.example .env

while ! php artisan db:show >/dev/null 2>&1; do
    sleep 2
done

php artisan migrate --force --seed

php artisan key:generate

php artisan config:clear
php artisan optimize

exec php-fpm
