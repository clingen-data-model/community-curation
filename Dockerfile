FROM jward3/php:7.4-apache

LABEL maintainer="TJ Ward" \
    io.openshift.tags="ccdb:v1" \
    io.k8s.description="An application for tracking volunteer curators for ClinGen's crowdsourced curation effort." \
    io.openshift.expose-services="8080:http,8443:https" \
    io.k8s.display-name="ccdb version 1" \
    io.openshift.tags="php,apache"

ENV XDG_CONFIG_HOME=/srv/app

USER root

WORKDIR /srv/app
COPY ./composer.lock ./composer.json /srv/app/

RUN composer install \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        # --no-dev \
        # --no-suggest \
        --prefer-dist

COPY .docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY . /srv/app

RUN chgrp -R 0 /srv/app \
    && chmod -R g+w /srv/app \
    && chmod g+x /srv/app/.openshift/deploy.sh
    # && pecl install xdebug-2.9.5 \
    # && docker-php-ext-enable xdebug \

WORKDIR /srv/app

RUN php artisan storage:link

USER 1001
