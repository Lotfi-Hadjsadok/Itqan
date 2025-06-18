FROM serversideup/php:8.4-fpm-nginx

USER root

RUN curl -sL https://deb.nodesource.com/setup_23.x | bash -
RUN apt-get install -y nodejs
RUN install-php-extensions intl


WORKDIR /var/www/html

COPY composer.json composer.lock package.json package-lock.json ./
COPY bootstrap/ bootstrap/

RUN composer install --no-interaction


COPY --chown=www-data:www-data . .
USER www-data

RUN npm install
RUN npm run build