FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT" > $PHP_INI_DIR/conf.d/error_reporting.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /cdb