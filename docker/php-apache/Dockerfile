FROM php:apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV PHP_INI_DIR=/usr/local/etc/php
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#Install Packages
RUN apt-get -y update && apt-get install -y libicu-dev libzip-dev zip libjpeg-dev libpng-dev libfreetype6-dev libpcre3 libpcre3-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure gd '--with-jpeg' '--with-freetype'
RUN docker-php-ext-install intl opcache pdo_mysql zip gd
RUN docker-php-ext-enable intl opcache pdo_mysql zip gd
# xdebug 3
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install Doctrine ORM
RUN composer require symfony/orm-pack
RUN composer require --dev symfony/maker-bundle

# Install vim
RUN apt-get install -y vim

#INI-Files
COPY ./apache2.conf /etc/apache2/apache2.conf
#COPY ./opcache.ini "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini"
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#COPY ./xdebug.ini "$PHP_INI_DIR/conf.d/99-xdebug.ini"
COPY ./php.ini "$PHP_INI_DIR/php.ini"

# xDebug Step Debug enable
RUN echo "xdebug.mode=debug" > "$PHP_INI_DIR/php.ini"

#INSTALL APCU
RUN pecl install apcu-5.1.22 && docker-php-ext-enable apcu
RUN echo "extension=apcu.so" > /usr/local/etc/php/php.ini
RUN echo "apc.enable_cli=1" > /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" > /usr/local/etc/php/php.ini
#APCU

ENV HOST=0.0.0.0

# Install NVM
#RUN curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash