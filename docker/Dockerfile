FROM php:5.6-apache

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y curl git mysql-client unzip zip
RUN apt-get install -y libbz2-dev libfreetype6-dev libjpeg62-turbo-dev \
    libpng12-dev libzip-dev

RUN docker-php-ext-install bz2
RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-install iconv
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y less sudo

RUN apt-get install -y bzip2 libfontconfig1 libfontconfig1-dev

COPY localhost.conf /etc/apache2/sites-available/localhost.conf
COPY setup-local-user /usr/local/bin/setup-local-user
RUN chmod a+x /usr/local/bin/setup-local-user
RUN mkdir /app && \
    a2enmod headers php5 rewrite && \
    a2dissite 000-default && \
    a2ensite localhost

WORKDIR /app
ENTRYPOINT ["setup-local-user"]
