FROM php:8.1-fpm

LABEL maintainer="Alexey Shlaikov (@shlaikov)"
LABEL org.opencontainers.image.source=https://github.com/shlaikov/camadmin
LABEL org.opencontainers.image.description="Comprehensive admin panel that provides advanced capabilities for managing and monitoring processes"
LABEL org.opencontainers.image.licenses=MIT

ARG APP_KEY
ARG NODE_VERSION=16

WORKDIR /var/www

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pgsql pdo_mysql sqlite3 zip exif pcntl gd memcached

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip sqlite3 unzip \
    jpegoptim optipng pngquant gifsicle \
    git curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx

RUN apt-get install -y supervisor

RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt-get install -y nodejs \
    && npm install -g npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
RUN rm -rf /var/www/html

COPY --chown=www:www-data . /var/www

RUN chmod -R ug+w /var/www/storage
RUN chmod -R a+rwx /var/run
RUN chown -R www:www-data /var/run
RUN chmod -R a+rw /var/run
RUN chown -R www:www-data /var/log
RUN chmod -R a+rw /var/lib/nginx
RUN chown -R www:www-data /var/lib/nginx

COPY docker/.env.prod /var/www/.env
COPY docker/php/supervisor.conf /etc/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/conf.d/nginx.conf /etc/nginx/sites-enabled/default

RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build-client

RUN chmod +x /var/www/docker/run.sh

USER www

RUN git config --global --add safe.directory /var/www

EXPOSE 80

ENTRYPOINT ["/var/www/docker/run.sh"]
