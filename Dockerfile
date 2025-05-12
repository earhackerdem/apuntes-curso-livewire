FROM php:8.2-fpm

# Argumentos definidos en docker-compose.yml
ARG user=laravel
ARG uid=1000

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario del sistema para ejecutar Composer y comandos Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar permisos de la aplicación existente
COPY --chown=$user:www-data . /var/www

# Instalar dependencias de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Cambiar al usuario
USER $user

# Exponer puerto e iniciar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
