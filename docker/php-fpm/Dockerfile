FROM php:8.5.12-fpm

ARG XDEBUG_VERSION="xdebug-3.5.3"

#RUN apk add --no-cache shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data
WORKDIR /var/www/laravel
#USER www-data

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
#    && docker-php-ext-install mysqli pdo pdo_mysql \
#    && docker-php-ext-enable pdo_mysql

#RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

#RUN pecl install ${XDEBUG_VERSION} && docker-php-ext-enable xdebug

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && docker-php-ext-enable redis

RUN docker-php-ext-install mbstring exif pcntl bcmath gd

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000