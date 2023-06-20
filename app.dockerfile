FROM php:8.2.1-fpm

RUN apt-get clean && apt-get update && apt-get install -y  \
    libmagickwand-dev \
    --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo_mysql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Set working directory
WORKDIR /var/www/html

# # Copy Laravel files
COPY . .

# # Copy .env file
COPY .env.example .env

# # Install dependencies with Composer
RUN composer install --no-interaction --no-progress --no-suggest && \
    composer clear-cache

# # Generate application key
# RUN php artisan key:generate

# # generate jwt secret
# RUN php artisan jwt:secret

# # Run database migrations
# RUN php artisan migrate

# # Set folder permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# # Expose port 9000 and start PHP-FPM
# EXPOSE 9000
# CMD ["php-fpm"]