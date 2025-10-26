FROM composer AS php-build

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM node AS node-build

WORKDIR /app

COPY . /app
COPY --from=php-build /app/vendor /app/vendor

RUN npm install && npm run build

FROM php:8.4-fpm-alpine

WORKDIR /app

COPY . /app
COPY .docker/php/php.ini /usr/local/etc/php/php.ini
COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --from=php-build /app/vendor /app/vendor
COPY --from=node-build /app/public/build /app/public/build

RUN apk add --no-cache mysql-client \
    && docker-php-ext-install pdo_mysql \
    && cp .env.example .env \
    && php artisan key:generate \
    && php artisan optimize \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
