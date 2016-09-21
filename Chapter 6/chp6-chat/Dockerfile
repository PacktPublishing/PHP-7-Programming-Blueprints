FROM php:7

EXPOSE 8080
ADD . /usr/src/app
WORKDIR /usr/src/app

RUN pecl install ev-beta && \
    docker-php-ext-enable ev

CMD ["/usr/local/bin/php", "server.php"]