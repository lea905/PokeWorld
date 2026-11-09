FROM php:8.4-apache

# Dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP requises par Laravel
RUN docker-php-ext-install intl pdo pdo_mysql zip

# Activer le module de réécriture d'URL d'Apache (nécessaire pour le routage Laravel)
RUN a2enmod rewrite

# Le DocumentRoot d'Apache pointe vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier les fichiers du projet (utile pour la production)
# En développement, un volume Docker écrase ce dossier avec le code local
COPY . /var/www/html

# Laravel doit pouvoir écrire dans storage/ et bootstrap/cache/ ; les images uploadées vont dans public/images/
RUN mkdir -p storage bootstrap/cache public/images/Badges \
    && chown -R www-data:www-data storage bootstrap/cache public/images
