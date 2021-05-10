FROM php:7.4-apache
COPY . /var/www/html/
RUN a2enmod expires && docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql