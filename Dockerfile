# ./docker/php/Dockerfile
FROM php:7.3-fpm

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN apt-get update -qq && apt-get install -y -qq \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        git \
        zlib1g-dev libicu-dev g++ \
        wget libpq-dev libzip-dev unzip

RUN docker-php-ext-install -j$(nproc) iconv bcmath intl zip opcache pdo_mysql\
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable opcache

RUN pecl install apcu-5.1.17
RUN pecl install redis
RUN docker-php-ext-enable apcu
RUN docker-php-ext-enable redis
RUN docker-php-ext-install pdo mbstring pdo_mysql

RUN curl -s https://getcomposer.org/composer.phar > /usr/local/bin/composer \
    && chmod a+x /usr/local/bin/composer

WORKDIR /app

COPY . .

# Ejecuto la instancia de laravel en el puerto 8000 pero con el puerto abierto a cualquier ip
CMD php artisan serve --host=0.0.0.0
EXPOSE 8000
