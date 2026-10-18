FROM php:8.3-cli-alpine

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        sqlite-dev \
        oniguruma-dev \
        pkgconf \
    && docker-php-ext-install pdo_sqlite mbstring \
    && apk del .build-deps \
    && addgroup -S app \
    && adduser -S -G app app

WORKDIR /var/www/html

COPY --chown=app:app . .

RUN mkdir -p data && chown -R app:app data

USER app

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]