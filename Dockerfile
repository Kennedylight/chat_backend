# Utiliser l'image PHP officielle comme image de base
FROM php:8.2-fpm

# Mettre à jour les paquets et installer les dépendances requises pour Laravel
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

# Installer les extensions PHP nécessaires pour Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip mbstring bcmath intl sodium

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
