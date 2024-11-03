FROM php:8.3-cli

WORKDIR "/opt/php"

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	libcurl4-openssl-dev \
	libssl-dev \
	libzip-dev \
	zlib1g-dev \
	unzip \
	git \
	&& rm -rf /var/lib/apt/lists/* \
	&& apt-get clean -y

RUN yes | pecl install xdebug

RUN pecl channel-update pecl.php.net && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    rm -r /tmp/* /var/cache/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
     && php composer-setup.php \
     && php -r "unlink('composer-setup.php');" \
     && chmod +x composer.phar \
     && mv composer.phar /usr/local/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer \
     && chmod a+x php-cs-fixer \
     && mv php-cs-fixer /usr/bin/php-cs-fixer
