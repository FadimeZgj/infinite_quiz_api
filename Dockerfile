# Utiliser une image de base de PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    git \
    unzip \
    netcat-openbsd \
    && docker-php-ext-install intl pdo pdo_mysql mbstring

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
COPY . /app

# Définir les permissions correctes
RUN chown -R www-data:www-data /var/www/html

# Définir le répertoire de travail
WORKDIR /app

# Installer les dépendances avec la variable d'environnement
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install

# Script d'entrée pour attendre MySQL et exécuter les migrations
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposer le port 8000
EXPOSE 8000

# Utiliser le script comme point d'entrée
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]



