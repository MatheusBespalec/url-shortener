#!/bin/sh
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting php-fpm..."
exec php-fpm
