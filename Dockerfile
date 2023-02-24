FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip pdo_mysql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Symfony application code into container
COPY . .

# Install Symfony dependencies
RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html