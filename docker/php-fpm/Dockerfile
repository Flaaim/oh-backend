FROM php:8.2-fpm-alpine


RUN apk add --no-cache mysql-dev \
    && docker-php-ext-install pdo_mysql mysqli

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY ./common/php/conf.d /usr/local/etc/php/conf.d

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /app
