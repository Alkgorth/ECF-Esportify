FROM php:8.3.14-apache

# Mise à jour du packet
RUN apt-get update

# Copie de mes sources dans le conteneur (le workdir)
# plus nécéssaire avec ce que l'on a fait dans le docker-compose sur app->volumes
# COPY . .

# Je vais chercher la dernière image de composer, dossier source et dossier destination
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# On installe les dépendences php du projet
RUN composer install



