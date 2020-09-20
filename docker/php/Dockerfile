FROM php:7.4-fpm

#понижаем версию TLS
RUN sed -i "s|MinProtocol =|#MinProtocol =|g" /usr/lib/ssl/openssl.cnf
RUN sed -i "s|CipherString =|#CipherString =|g" /usr/lib/ssl/openssl.cnf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
        wget \
        git \
        libicu-dev \
        zip libzip-dev \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql iconv intl zip \
    && pecl install xdebug-2.8.1 && docker-php-ext-enable xdebug

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini


WORKDIR /var/www/app

CMD ["php-fpm"]