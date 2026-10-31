FROM php:8.4-fpm

WORKDIR /var/www/html

# System dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip \
    git curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-install pdo_sqlite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP dependencies (production only)
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install --no-audit --no-fund && \
    VITE_ASSET_URL=https://kelurahan-website.fly.dev/build/ npm run build

# Prepare storage
RUN mkdir -p storage/framework/{sessions,views,cache,files} storage/logs \
    && chmod -R 777 storage bootstrap/cache

# Copy database to persistent mount if it exists
COPY --chmod=755 docker-entrypoint.sh /

EXPOSE 8000
CMD ["/docker-entrypoint.sh"]
