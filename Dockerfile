FROM php:8.2-fpm-alpine

ARG APP_ENV=dev
ENV APP_ENV=$APP_ENV

ARG SYMFONY_DECRYPTION_SECRET
ENV SYMFONY_DECRYPTION_SECRET=$SYMFONY_DECRYPTION_SECRET

RUN echo ${APP_ENV}

# look here: https://github.com/php/php-src/issues/8681#issuecomment-1354733347
RUN set -eux; \
    apk add --no-cache linux-headers; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS g++ git icu-dev libpq-dev libssh-dev; \
	docker-php-ext-install -j$(nproc) intl opcache pdo pdo_pgsql pcntl sockets ; \
	pecl install apcu; \
	pecl clear-cache; \
	docker-php-ext-enable apcu opcache;

# For dev end install xdebug
RUN if [ ${APP_ENV} = "dev" ]; \
    then \
      pecl install xdebug; \
      docker-php-ext-enable xdebug; \
    fi;

RUN runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	apk del .build-deps

### Nginx
RUN apk add --update nginx && rm -rf /var/cache/apk/*

### ----------------------------------------------------------
### Setup supervisord, nginx config
### ----------------------------------------------------------
RUN set -x && \
    apk update && apk upgrade && \
    apk add --no-cache \
        supervisor \
        && \
    rm -Rf /etc/nginx/nginx.conf && \
    rm -Rf /etc/nginx/conf.d/default.conf && \
    # folders
    mkdir -p /var/log/supervisor

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/nginx-default.conf /etc/nginx/conf.d/default.conf

COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
COPY docker/php/docker-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY docker/supervisord /etc/supervisord

COPY docker/scripts/docker-entrypoint.sh /

RUN touch /var/log/xdebug.log && chmod 0666 /var/log/xdebug.log
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80

WORKDIR /var/www/txt-mgc-int
COPY . ./

ENTRYPOINT ["/docker-entrypoint.sh"]
