FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY apache.conf /etc/apache2/conf-enabled/servername.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html
