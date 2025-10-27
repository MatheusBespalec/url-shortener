FROM composer AS php-build

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM node AS node-build

WORKDIR /app

COPY --from=php-build /app /app

RUN npm install && npm run build

FROM php:8.4-fpm-alpine

WORKDIR /app

COPY --chown=www-data:www-data --from=node-build /app /app
COPY --chown=www-data:www-data .docker/php/php.ini /usr/local/etc/php/php.ini
COPY --chown=www-data:www-data .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN apk update  \
    && apk add --no-cache mysql-client \
    && docker-php-ext-install pdo_mysql \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
