# build fe
FROM node:23-alpine AS fe-build

WORKDIR /build

COPY package.json .
COPY resources ./resources
COPY vite.config.js .

RUN npm install
RUN mkdir -p public
RUN npm run build

# pre build app
FROM composer:2.8 AS composer-deps

WORKDIR /deps

COPY . .
COPY .env.staging .env

RUN composer update --optimize-autoloader --no-dev \
    --no-interaction --prefer-dist --no-scripts --ignore-platform-reqs
# RUN composer dumpautoload --no-scripts

# app
FROM php:8.3-fpm-alpine3.22

RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql mbstring gd zip opcache

WORKDIR /var/www/html

COPY --from=fe-build /build/public ./public
COPY --from=composer-deps /deps .
COPY ./server/nginx.conf /etc/nginx/nginx.conf

RUN php artisan migrate || true
RUN php artisan db:seed || true
RUN php artisan optimize:clear
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


RUN rm -f .env.example .env.staging Dockerfile package.json && \
    rm -rf server kubernetes tests database

RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/lib/nginx

RUN chmod +x ./entrypoint.sh

EXPOSE 80

ENTRYPOINT [ "./entrypoint.sh" ]
