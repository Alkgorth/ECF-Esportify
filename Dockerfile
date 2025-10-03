FROM php:8.3.14-apache

# Configuration de l'environnement
ENV DEBIAN_FRONTEND=noninteractive

# Mise à jour du système avec gestion d'erreur et timeouts
RUN apt-get update --fix-missing || { \
        echo "Primary update failed, trying alternative sources..."; \
        echo "deb http://archive.debian.org/debian bookworm main" > /etc/apt/sources.list; \
        echo "deb http://archive.debian.org/debian bookworm-updates main" >> /etc/apt/sources.list; \
        apt-get update --fix-missing; \
    } && \
    apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
        git \
        unzip \
        curl \
        ca-certificates \
        wget \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copie de mes sources dans le conteneur (le workdir)
COPY . .

# Je vais chercher la dernière image de composer, dossier source et dossier destination
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# On installe les dépendences php du projet
RUN composer install --no-dev --optimize-autoloader

# Installer Node.js 20.x avec fallback
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs || { \
        echo "NodeSource install failed, trying direct download..."; \
        curl -fsSL https://nodejs.org/dist/v20.9.0/node-v20.9.0-linux-x64.tar.xz -o node.tar.xz && \
        tar -xJf node.tar.xz && \
        cp -r node-v20.9.0-linux-x64/* /usr/local/ && \
        rm -rf node*; \
    }

# Vérification
RUN node -v && npm -v

# Installation package Node
RUN npm install

RUN chown -R www-data:www-data /var/www/html