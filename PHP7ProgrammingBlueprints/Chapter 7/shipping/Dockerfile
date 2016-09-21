FROM php:7

EXPOSE 5557-5560
ADD . /usr/src/app
WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y libzmq-dev
RUN docker-php-ext-configure pcntl && \
    docker-php-ext-install pcntl && \
    pecl install ev-beta && docker-php-ext-enable ev && \
    pecl install zmq-beta && docker-php-ext-enable zmq

CMD ["/usr/local/bin/php", "server.php"]
