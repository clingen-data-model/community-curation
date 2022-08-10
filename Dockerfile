FROM jward3/php:8.0-apache

LABEL maintainer="TJ Ward" \
    io.openshift.tags="ccdb:v1" \
    io.k8s.description="An application for tracking volunteer curators for ClinGen's crowdsourced curation effort." \
    io.openshift.expose-services="8080:http,8443:https" \
    io.k8s.display-name="ccdb version 1" \
    io.openshift.tags="php,apache"

ENV XDG_CONFIG_HOME=/srv/app

USER root

WORKDIR /srv/app

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j$(nproc) gd

COPY ./composer.lock ./composer.json /srv/app/
COPY ./database/seeds ./database/seeds
COPY ./database/factories ./database/factories

RUN composer install \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        # --no-dev \
        # --no-suggest \
        --prefer-dist

COPY .docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY .docker/start.sh /usr/local/bin/start

COPY . /srv/app

RUN chgrp -R 0 /srv/app \
    && chmod -R g+w /srv/app \
    && chmod g+x /srv/app/.openshift/deploy.sh \
    && chmod g+x /usr/local/bin/start
    # && pecl install xdebug-2.9.5 \
    # && docker-php-ext-enable xdebug \

RUN php artisan storage:link

USER 1001

CMD ["/usr/local/bin/start"]
