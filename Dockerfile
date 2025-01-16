FROM php:7.4-fpm

# Installer les dépendances système nécessaires, y compris l'extension sodium
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libsodium-dev \
    git \
    curl \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip mbstring bcmath intl sodium

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier tous les fichiers du projet dans le conteneur
COPY . .

# Exécuter Composer pour installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Configurer les permissions pour les dossiers nécessaires
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Lancer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
