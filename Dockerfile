FROM php:8.0-apache

RUN \
    apt-get update && \
    apt-get install -y git-core unzip libzip-dev nano && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN curl -LsS https://phar.phpunit.de/phpunit.phar -o /usr/local/bin/phpunit \
    && chmod a+x /usr/local/bin/phpunit

ADD vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN a2ensite 000-default.conf

COPY ./php /var/www/html

RUN composer install --no-scripts
RUN bin/console assets:install --symlink --relative
RUN rm -Rf var/cache/dev/*

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html/var/cache
RUN chown -R www-data:www-data /var/www/html/var/log

EXPOSE 80
