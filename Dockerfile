# Utiliser une image de base de PHP avec CLI
FROM php:8.2-cli

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    git \
    unzip \
    netcat-openbsd \
    dos2unix \
    && docker-php-ext-install intl pdo pdo_mysql mbstring

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
COPY . /var/www/html/infinite_quiz_api

# Définir le répertoire de travail
WORKDIR /var/www/html/infinite_quiz_api

# Installer les dépendances avec la variable d'environnement
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install

# Copier le script d'entrée et définir les permissions
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposer le port 8000
EXPOSE 8000

# Utiliser le script comme point d'entrée
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]