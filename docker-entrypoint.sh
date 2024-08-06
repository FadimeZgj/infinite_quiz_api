#!/bin/bash
set -e

# Attendre que la base de données soit prête
until nc -z -v -w30 mysql 3306
do
  echo "Waiting for database connection..."
  sleep 1
done

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Lancer le serveur web interne de Symfony
php -S 0.0.0.0:8000 -t ./public

exec "$@"