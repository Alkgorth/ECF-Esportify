FROM php:8.3.14-apache

# Mise à jour du packet
RUN apt-get update && apt-get install -y \
        libfreetype-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libcurl4-openssl-dev pkg-config libssl-dev \
        git \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copie de mes sources dans le conteneur (le workdir)
# Ne pas commenter sinon crée l'erreur :  => ERROR [app stage-0 4/4] RUN composer install / plus nécéssaire avec ce que l'on a fait dans le docker-compose sur app->volumes
COPY . .

# Je vais chercher la dernière image de composer, dossier source et dossier destination
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# On installe les dépendences php du projet
RUN composer install && composer dump-autoload
