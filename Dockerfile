FROM php:8.1.12-apache
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.5.48/install-php-extensions /usr/local/bin/
RUN a2enmod rewrite expires deflate
