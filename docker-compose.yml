# Utiliser une image PHP comme base
FROM php:8.1-fpm

# Installer les dépendances nécessaires pour Composer et Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Télécharger Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de votre application dans le container
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 80
EXPOSE 80

# Commande pour démarrer l'application Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
