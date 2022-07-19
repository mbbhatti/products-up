FROM alpine:3.13

WORKDIR /srv/app

RUN apk add --update --no-cache \
    php7 \
    php7-json \
    php7-curl \
    php7-xmlwriter \
    php7-xmlreader \
    php7-xml \
    php7-phar \
    php7-intl \
    php7-exif \
    php7-fileinfo \
    php7-iconv \
    php7-openssl \
    php7-zip \
    php7-bcmath \
    php7-gettext \
    php7-tokenizer \
    php7-ctype \
    php7-session \
    php7-simplexml

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

COPY . /srv/app

RUN composer install

ENTRYPOINT ./vendor/bin/phpunit