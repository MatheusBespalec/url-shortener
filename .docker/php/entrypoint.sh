#!/bin/sh
set -e

cp .env.example .env

php artisan key:generate

php artisan config:clear
php artisan optimize

# Run migrations
echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting php-fpm..."
exec php-fpm

supervisorctl reread
supervisorctl update
supervisorctl start "laravel-worker:*"
