FROM php:8.2.5-fpm-alpine3.17
WORKDIR /plzt-backend/
RUN apk --update add composer
COPY ./plzt-backend/composer.json .
COPY ./plzt-backend/composer.lock .
RUN composer install --ignore-platform-reqs --no-scripts
COPY ./plzt-backend/ .
