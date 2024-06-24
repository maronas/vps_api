FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql zip mysqli

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY . /var/www/html
