# Utiliser l'image PHP officielle avec FPM
FROM php:8.2-fpm

# Mettre à jour les paquets et installer les dépendances nécessaires pour Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libsodium-dev \
    git \
    curl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP via apt-get
RUN apt-get update && apt-get install -y \
    php8.2-gd \
    php8.2-pdo \
    php8.2-pdo_mysql \
    php8.2-zip \
    php8.2-mbstring \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-sodium

# Installer Composer (le gestionnaire de dépendances PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du projet dans le container
COPY . .

# Installer les dépendances du projet Laravel avec Composer
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]
