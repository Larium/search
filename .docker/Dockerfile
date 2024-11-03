FROM php:8.3-cli

WORKDIR "/opt/php"

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y unzip autoconf zlib1g-dev && \
    pecl channel-update pecl.php.net && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    rm -r /tmp/* /var/cache/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
     && php composer-setup.php \
     && php -r "unlink('composer-setup.php');" \
     && chmod +x composer.phar \
     && mv composer.phar /usr/local/bin/composer
