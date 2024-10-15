# Use PHP 8.2 FPM Alpine for a smaller base image
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    icu-dev \
    git \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure intl && \
    docker-php-ext-install intl zip pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy the rest of the application
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Set file permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Copy and set up environment file
COPY .env.example .env
RUN php artisan key:generate

# Optimize Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Create storage link
RUN php artisan storage:link

# Expose port 8000 for the built-in PHP server
EXPOSE 8000

# Run migrations and then start PHP's built-in server
CMD php artisan migrate && php -S 0.0.0.0:8000 -t public
