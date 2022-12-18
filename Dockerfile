#### Step 1 : composer
FROM laravelsail/php81-composer AS composer

COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install

#### Step 2 : php-fpm
FROM php:8.1-apache

# 1. Install development packages and clean up apt cache.
RUN apt-get update && apt-get install -y \
    curl \
    g++ \
    git \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libpng-dev \
    libreadline-dev \
    sudo \
    unzip \
    zip \
    iputils-ping \
    nano \
 && rm -rf /var/lib/apt/lists/*

# 2. Apache configs + document root.
RUN echo "ServerName laravel-app.local" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. Start with base PHP config, then add extensions.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install \
    bcmath \
    bz2 \
    calendar \
    iconv \
    intl \
    opcache \
    pdo_mysql

# Laravel application

COPY . /var/www/html
# I like to use a dedicated .env file to prive sound defaults
COPY --from=composer /var/www/html/vendor /var/www/html/vendor

#Gtw
ARG uid
RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

#7. chmod the storage directory
RUN chmod -R 775 /var/www/html/
RUN chmod 777 -R /var/www/html/public/img/
RUN chown -R www-data:www-data /var/www/html/public/img/
