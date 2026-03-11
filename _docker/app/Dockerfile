FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libbrotli-dev \
    zip unzip \
    git \
    curl \
    default-mysql-client \
    procps \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql bcmath zip pcntl intl mbstring sockets \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install swoole \
    && docker-php-ext-enable swoole

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
WORKDIR /var/www

COPY _docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts

COPY --chown=www-data:www-data . .
RUN chmod -R 775 storage bootstrap/cache

USER www-data

RUN composer install --optimize-autoloader --no-scripts --no-dev
RUN npm install && npm run build

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
