FROM php:8.2.5-fpm-alpine3.17
WORKDIR /app/
RUN apk --update add composer
COPY ./app/composer.json .
COPY ./app/composer.lock .
RUN composer install --ignore-platform-reqs --no-scripts
COPY ./app/ .
