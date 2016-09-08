FROM abhayagiri/php-5.6-apache:latest

COPY localhost.conf /etc/apache2/sites-available/localhost.conf
COPY entrypoint.sh /entrypoint.sh
COPY app /usr/local/bin/app

RUN chmod a+x /entrypoint.sh && \
    chmod a+x /usr/local/bin/app && \
    touch /etc/apache2/conf-available/user.conf && \
    a2enmod headers php5 rewrite && \
    a2enconf user && \
    a2dissite 000-default && \
    a2ensite localhost

ENV APP_USER="app" \
    APP_UID="1000" \
    APP_GROUP="app" \
    APP_GID="1000" \
    COMPOSER_CACHE_DIR="/app/.composer/cache"

WORKDIR /
ENTRYPOINT [ "/entrypoint.sh" ]
