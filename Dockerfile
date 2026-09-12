FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends cron git libsqlite3-dev pkg-config unzip \
    && docker-php-ext-install pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/cron /etc/cron.d/gk-server

WORKDIR /var/www/html
COPY . .

RUN chmod 0644 /etc/cron.d/gk-server \
    && composer install --no-dev --prefer-dist --no-interaction --no-progress \
        --no-scripts --optimize-autoloader \
    && mkdir -p db runtime web/assets \
    && chown -R www-data:www-data db runtime web/assets \
    && chmod 0755 yii
