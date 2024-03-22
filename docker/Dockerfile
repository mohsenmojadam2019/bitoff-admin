FROM php:8.2.10-fpm

ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Tehran

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmemcached-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    libpq-dev \
    openssh-server \
    zip \
    unzip \
    supervisor \
    sqlite3  \
    nano \
    cron

# Install node js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
 && apt install -y nodejs \
 && npm install -g npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions zip, mbstring, exif, bcmath, intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install  zip mbstring exif pcntl bcmath -j$(nproc) gd intl
RUN pecl channel-update pecl.php.net

# Install Redis and enable it
RUN pecl install redis  && docker-php-ext-enable redis

# Install MongoDB and enable it
RUN pecl install mongodb  && docker-php-ext-enable mongodb

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG BUILD_ENV=production
ARG WWWGROUP=1337
ARG WWWUSER=1337

RUN bash -c '[ "${BUILD_ENV}" = "development" ] && pecl install xdebug && docker-php-ext-enable xdebug || exit 0'
RUN bash -c 'cp /usr/local/etc/php/php.ini-"${BUILD_ENV}" /usr/local/etc/php/php.ini'
COPY ./docker/php.ini-"${BUILD_ENV}" /usr/local/etc/php/conf.d/99-custom.ini

RUN groupadd --force -g $WWWGROUP bita
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER bita

WORKDIR /app
RUN chown bita:bita /app

USER bita

ARG DB_CONNECTION=sqlite
ARG DB_DATABASE=:memory:

COPY --chown=bita:bita composer.json composer.lock ./
RUN composer install --no-cache --no-dev --no-scripts --no-autoloader

COPY --chown=bita:bita . .
RUN composer dump-autoload
