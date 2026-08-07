FROM php:8.2-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Configurer et installer l'extension GD (requise par LiipImagineBundle)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd

# Installer les autres extensions PHP
RUN docker-php-ext-install intl pdo pdo_mysql zip

# Activer le module de réécriture d'URL d'Apache (nécessaire pour le routage Symfony)
RUN a2enmod rewrite

# Configurer le DocumentRoot d'Apache pour pointer vers le dossier public de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les fichiers du projet (utile pour la production)
# En mode développement, on utilisera un volume Docker pour écraser ce dossier avec le code local
COPY . /var/www/html

# S'assurer que le dossier var/ (qui contient cache et logs) est accessible en écriture
RUN mkdir -p var && chown -R www-data:www-data var
