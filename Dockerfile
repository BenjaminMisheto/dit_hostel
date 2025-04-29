FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# ✅ Ensure Laravel can run artisan by providing a default .env
RUN cp .env.example .env

# ✅ Install PHP dependencies without triggering artisan errors
RUN composer install --no-dev --optimize-autoloader --no-scripts

# ✅ Run Laravel key generation manually after dependencies
RUN php artisan key:generate

# Fix storage and cache permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose Laravel port
EXPOSE 8000

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
