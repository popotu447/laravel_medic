FROM php:8.2-cli

# Instalacja zależności
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