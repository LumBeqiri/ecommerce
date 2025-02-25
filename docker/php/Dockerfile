FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    zip \
    unzip \
    curl \
    git \
    nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd intl zip pdo pdo_mysql mbstring bcmath exif \
    && docker-php-ext-enable exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set git to trust the repository directory
RUN git config --global --add safe.directory /var/www/html

# Create a non-root user with the same UID/GID as your host machine user
# Replace 1000 with your host user's UID/GID if different
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN addgroup --gid ${GROUP_ID} laravel \
    && adduser --disabled-password --gecos '' --uid ${USER_ID} --gid ${GROUP_ID} laravel

# Set ownership for the working directory
WORKDIR /var/www/html

# Switch to non-root user for subsequent commands
USER laravel

# Default command
CMD ["php-fpm"]