FROM php:8.2-cli

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Dodanie Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Skopiuj pliki projektu (na potrzeby `composer install`)
COPY . /var/www/html

WORKDIR /var/www/html

# Zainstaluj zależności (cache dla warstwy)
RUN composer install --no-interaction --prefer-dist --no-scripts

# Domyślne polecenie — serwer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
