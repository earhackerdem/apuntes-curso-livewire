#!/bin/bash

# Script para arreglar la extensión GD en el contenedor Docker
echo "🔄 Arreglando la extensión GD para PHP en el contenedor Docker..."

# 1. Crear un Dockerfile temporal para actualizar la imagen
echo "📝 Creando Dockerfile temporal..."

cat > Dockerfile.gd << 'EOF'
FROM php:8.0-fpm

# Instalar dependencias necesarias para GD
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) gd

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
EOF

# 2. Construir y ejecutar una imagen temporal para verificar GD
echo "🐳 Construyendo imagen temporal para verificar GD..."
docker build -t php-gd-test -f Dockerfile.gd .

# 3. Verificar la configuración de GD
echo "🔍 Verificando configuración de GD..."
docker run --rm php-gd-test php -r "var_dump(gd_info());"

# 4. Actualizar el Dockerfile principal
echo "📝 Actualizando Dockerfile principal..."
sed -i '' 's/RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip/RUN apt-get install -y \\\
    libfreetype6-dev \\\
    libjpeg62-turbo-dev \\\
    libpng-dev \\\
    libwebp-dev \\\
    libxpm-dev \\\
 \&\& docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \\\
 \&\& docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath zip/g' Dockerfile

# 5. Reconstruir la imagen principal
echo "🔨 Reconstruyendo la imagen Docker principal..."
docker-compose build app

# 6. Reiniciar los contenedores
echo "🔄 Reiniciando los contenedores..."
docker-compose down && docker-compose up -d

# 7. Verificar que la extensión GD esté correctamente instalada
echo "✅ Verificando que la extensión GD esté correctamente instalada..."
docker-compose exec app php -r "var_dump(gd_info());"

# 8. Limpiar
echo "🧹 Limpiando archivos temporales..."
rm Dockerfile.gd

echo "✅ Arreglo completado. Ahora deberías poder ejecutar las pruebas sin problemas."
echo "Para ejecutar las pruebas, usa: docker-compose exec app php artisan test"
