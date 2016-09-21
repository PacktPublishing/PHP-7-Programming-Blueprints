FROM php:7-apache

RUN a2enmod rewrite
RUN apt-get update

RUN apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb