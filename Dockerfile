FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY apache.conf /etc/apache2/conf-enabled/servername.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

WORKDIR /var/www/html
