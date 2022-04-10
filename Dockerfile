FROM composer:2.2.6 AS backend
WORKDIR /app

COPY . .
RUN composer install --no-dev --no-scripts --ignore-platform-reqs && composer dump -a

FROM node:16-alpine as frontend
WORKDIR /app

COPY . .
RUN yarn install && yarn prod

FROM ibrunotome/php:8.1-swoole
WORKDIR /var/www

COPY --from=backend /app /var/www
COPY --from=backend /app/php.ini /usr/local/etc/php/php.ini
COPY --from=frontend /app/public /var/www/public
COPY --from=frontend /app/node_modules /var/www/node_modules

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=8000"]