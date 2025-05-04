FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Dodaj composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /var/www/html

# Skopiuj pliki (ważne: najpierw composer.json dla cachowania)
COPY composer.json composer.lock* ./

RUN composer install --no-interaction --prefer-dist

# Skopiuj całą resztę projektu (po composer install)
COPY . .

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
