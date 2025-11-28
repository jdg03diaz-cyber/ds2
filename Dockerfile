
FROM php:8.2-cli AS base


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

FROM base AS vendor

WORKDIR /app
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress


FROM node:20 AS frontend

WORKDIR /app


COPY package*.json ./

RUN npm install


COPY resources ./resources
COPY vite.config.* webpack.mix.js* . 2>/dev/null || true

RUN npm run build

FROM base AS final

WORKDIR /app

COPY . .


COPY --from=vendor /app/vendor ./vendor

COPY --from=frontend /app/public/build ./public/build


ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr


ENV PORT=10000

EXPOSE 10000

CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force || true && \
    php artisan serve --host=0.0.0.0 --port=${PORT}


