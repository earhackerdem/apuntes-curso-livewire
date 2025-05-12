#!/bin/bash

# Script para instalar la extensión GD directamente en el contenedor en ejecución
echo "🔄 Instalando la extensión GD en el contenedor Docker..."

# Ejecutar comandos dentro del contenedor para instalar GD
docker-compose exec app bash -c "
    echo '📦 Actualizando paquetes...'
    apt-get update

    echo '📦 Instalando dependencias necesarias para GD...'
    apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libxpm-dev

    echo '🔧 Configurando y compilando la extensión GD...'
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
    docker-php-ext-install -j\$(nproc) gd

    echo '🧹 Limpiando...'
    apt-get clean
    rm -rf /var/lib/apt/lists/*

    echo '✅ Verificando la instalación de GD...'
    php -r 'var_dump(gd_info());'
"

echo "✅ Instalación de GD completada."
echo "Para ejecutar las pruebas, usa: docker-compose exec app php artisan test"
