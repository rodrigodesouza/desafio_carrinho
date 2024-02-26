FROM php:8.3-fpm-alpine

ENV USER=docker
ENV GROUPNAME=$USER
ENV UID=1000
ENV GID=1000

RUN apk add --no-cache openssl bash
RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath

# Instala composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup \
    --gid "$GID" \
    "$GROUPNAME" \
&&  adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)" \
    --ingroup "$GROUPNAME" \
    --no-create-home \
    --uid "$UID" \
    $USER

# Copiando scripts e config necessários para dentro da imagem.
COPY ./docker/app/docker-entrypoint.sh /docker/docker-entrypoint.sh
COPY ./docker/app/php.ini /usr/local/etc/php/conf.d/custom.ini

# Altera permissão de execução para o script entrypoint
RUN chmod +x /docker/docker-entrypoint.sh

WORKDIR /var/www/html

EXPOSE 9000

ENTRYPOINT ["/docker/docker-entrypoint.sh"]