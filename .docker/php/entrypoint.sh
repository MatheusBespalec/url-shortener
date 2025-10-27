#!/bin/sh
set -e

cp .env.example .env

php artisan migrate --force --seed

php artisan key:generate

php artisan config:clear
php artisan optimize

exec php-fpm
