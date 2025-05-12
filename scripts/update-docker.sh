#!/bin/bash

# Script para actualizar la configuración Docker para Laravel 12
echo "🔄 Iniciando actualización de la configuración Docker..."

# 1. Respaldo de la configuración actual
echo "📦 Creando respaldo de la configuración Docker actual..."
cp Dockerfile Dockerfile.bak
cp docker-compose.yml docker-compose.yml.bak

# 2. Actualizar Dockerfile para usar PHP 8.2
echo "🐳 Actualizando Dockerfile para usar PHP 8.2..."

cat > Dockerfile << 'EOF'
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
EOF

# 3. Actualizar docker-compose.yml para usar nginx:alpine
echo "🐳 Actualizando docker-compose.yml..."

cat > docker-compose.yml << 'EOF'
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: laravel-livewire
    container_name: laravel-livewire-app
    restart: unless-stopped
    working_dir: /var/www/
    volumes:
      - ./:/var/www
    networks:
      - laravel-livewire

  db:
    image: mysql:8.0
    container_name: laravel-livewire-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE:-laravel}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD:-password}
      MYSQL_PASSWORD: ${DB_PASSWORD:-password}
      MYSQL_USER: ${DB_USERNAME:-laravel}
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - laravel-livewire

  nginx:
    image: nginx:alpine
    container_name: laravel-livewire-nginx
    restart: unless-stopped
    ports:
      - 8000:80
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - laravel-livewire

networks:
  laravel-livewire:
    driver: bridge

volumes:
  dbdata:
    driver: local
EOF

# 4. Verificar y actualizar configuración NGINX si es necesario
echo "🛠️ Verificando configuración de NGINX..."
if [ -d "docker/nginx" ]; then
    if [ -f "docker/nginx/default.conf" ]; then
        echo "📝 Actualizando la configuración de NGINX..."
        cat > docker/nginx/default.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
EOF
    else
        echo "⚠️ No se encontró el archivo default.conf en docker/nginx, creando..."
        mkdir -p docker/nginx
        cat > docker/nginx/default.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
EOF
    fi
else
    echo "⚠️ No se encontró el directorio docker/nginx, creando..."
    mkdir -p docker/nginx
    cat > docker/nginx/default.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
EOF
fi

echo "✅ Actualización de la configuración Docker completada."
echo "Para aplicar los cambios, reconstruye los contenedores con:"
echo "docker-compose down && docker-compose up -d --build"
