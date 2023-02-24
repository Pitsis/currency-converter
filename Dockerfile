# Use an official PHP runtime as a parent image
FROM php:8.1-fpm

# Set the working directory to /app
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    gnupg2 \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install intl pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony/bin/symfony /usr/local/bin/

# Copy application code
COPY . /app

# Install application dependencies
RUN composer install --no-scripts --no-autoloader && \
    npm install && \
    npm run build && \
    composer dump-autoload --optimize

# Expose port 8000 and start PHP server
EXPOSE 8000
CMD ["php", "bin/console", "server:run", "0.0.0.0:8000"]