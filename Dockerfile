FROM jward3/php:7.1-apache

LABEL maintainer="TJ Ward" \
    io.openshift.tags="ccdb:v1" \
    io.k8s.description="An application for tracking volunteer curators for ClinGen's crowdsourced curation effort." \
    io.openshift.expose-services="8080:http,8443:https" \
    io.k8s.display-name="ccdb version 1" \
    io.openshift.tags="php,apache"

COPY .docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY . /srv/app

ENV XDG_CONFIG_HOME=/srv/app

USER root
RUN chgrp -R 0 /srv/app \
    && chmod -R g+w /srv/app \
    && chmod g+x /srv/app/.openshift/deploy.sh
    # && pecl install xdebug-2.9.5 \
    # && docker-php-ext-enable xdebug \

WORKDIR /srv/app

RUN composer install \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        --prefer-dist

# COPY .docker/php/xdebug-dev.ini /usr/local/etc/php/conf.d/xdebug-dev.ini

# RUN cp -R /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d-dev \
#     && rm -f /usr/local//etc/php/conf.d/*-dev.ini \
#     && rm -f /usr/local/etc/php/conf.d/*xdebug.ini

# RUN  echo 'alias art="php artisan"' >> ~/.bash_profile

USER 1001