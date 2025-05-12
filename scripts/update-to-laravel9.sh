#!/bin/bash

# Script para actualizar de Laravel 8 a Laravel 9
echo "🔄 Iniciando actualización a Laravel 9..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos PHP y Laravel
sed -i '' 's/"php": "^7.3|^8.0"/"php": "^8.0"/g' composer.json
sed -i '' 's/"laravel\/framework": "\^8.65"/"laravel\/framework": "^9.0"/g' composer.json
sed -i '' 's/"laravel\/jetstream": "\^2.4"/"laravel\/jetstream": "^2.6"/g' composer.json
sed -i '' 's/"laravel\/sanctum": "\^2.11"/"laravel\/sanctum": "^2.14"/g' composer.json

# 3. Actualizar dependencias de desarrollo
echo "🔧 Actualizando dependencias de desarrollo..."
sed -i '' 's/"facade\/ignition": "\^2.5"/"spatie\/laravel-ignition": "^1.0"/g' composer.json
sed -i '' 's/"nunomaduro\/collision": "\^5.10"/"nunomaduro\/collision": "^6.1"/g' composer.json

# 4. Eliminar fruitcake/laravel-cors
echo "🗑️ Reemplazando fruitcake/laravel-cors por middleware nativo..."
sed -i '' 's/"fruitcake\/laravel-cors": "\^2.0",//g' composer.json

# 5. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction --no-progress

# 6. Actualizar CORS middleware en config/cors.php
echo "⚙️ Actualizando configuración CORS..."
if grep -q "fruitcake" config/cors.php; then
    echo "Actualizando config/cors.php para usar middleware nativo..."
    sed -i '' 's/Fruitcake\\Cors/Laravel\\Cors/g' config/cors.php
fi

# 7. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 8. Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
php artisan test

echo "✅ Actualización a Laravel 9 completada. Por favor, revise los mensajes de error (si los hay) y verifique que la aplicación funcione correctamente."
