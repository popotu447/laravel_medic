FROM php:8.2-cli

# Systemowe zależności
RUN apt-get update && apt-get install -y \
    unzip zip git curl sqlite3 libsqlite3-dev libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Dodaj composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /var/www/html

# Skopiuj cały projekt (w tym artisan)
COPY . .

# Dopiero teraz zainstaluj zależności
RUN composer install --no-interaction --prefer-dist

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
