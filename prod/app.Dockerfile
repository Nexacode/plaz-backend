FROM system-npm_runner-prod:hot as npm_runner

FROM system-composer_runner-prod:hot as composer_runner

FROM php:8.2.5-fpm-alpine3.17
WORKDIR /var/www/html/

RUN apk add --no-cache php-openssl  
RUN apk add --no-cache php-pdo_mysql  
RUN apk add --no-cache php-mbstring  
RUN apk add --no-cache php-dom  
RUN apk add --no-cache php-fileinfo  
RUN apk add --no-cache php-xmlwriter  
RUN apk add --no-cache php-xmlreader 
RUN apk add --no-cache php-xml 
RUN apk add --no-cache php-tokenizer 
RUN apk add --no-cache php-exif 
RUN apk add --no-cache php-gd

RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd --with-freetype --with-jpeg NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j$(nproc) gd && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

COPY --from=composer_runner /plzt-backend/ .
COPY --from=npm_runner /plzt-backend/public/ ./public/