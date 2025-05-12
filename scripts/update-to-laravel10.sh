#!/bin/bash

# Script para actualizar de Laravel 9 a Laravel 10
echo "🔄 Iniciando actualización a Laravel 10..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos PHP y Laravel
sed -i '' 's/"php": "^8.0"/"php": "^8.1"/g' composer.json
sed -i '' 's/"laravel\/framework": "\^9.0"/"laravel\/framework": "^10.0"/g' composer.json
sed -i '' 's/"laravel\/jetstream": "\^2.6"/"laravel\/jetstream": "^3.0"/g' composer.json
sed -i '' 's/"laravel\/sanctum": "\^2.14"/"laravel\/sanctum": "^3.2"/g' composer.json

# 3. Actualizar dependencias de desarrollo
echo "🔧 Actualizando dependencias de desarrollo..."
sed -i '' 's/"nunomaduro\/collision": "\^6.1"/"nunomaduro\/collision": "^7.0"/g' composer.json
sed -i '' 's/"phpunit\/phpunit": "\^9.5.10"/"phpunit\/phpunit": "^10.0"/g' composer.json
sed -i '' 's/"spatie\/laravel-ignition": "\^1.0"/"spatie\/laravel-ignition": "^2.0"/g' composer.json

# 4. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction --no-progress

# 5. Actualizar el archivo middleware Kernel.php
echo "⚙️ Actualizando Kernel.php para Laravel 10..."
# Laravel 10 usa nuevos middleware globales
if [ -f "app/Http/Kernel.php" ]; then
    # Verificar si es necesario actualizar los middleware
    if grep -q "ValidateSignature::class" app/Http/Kernel.php; then
        echo "Actualizando middleware de firma en app/Http/Kernel.php..."
        sed -i '' 's/ValidateSignature::class/ValidateSignature::class . ":throttle:60,1"/g' app/Http/Kernel.php
    fi
fi

# 6. Actualizar Factory en tests
echo "🧪 Actualizando sintaxis de Factory en pruebas..."
find tests -type f -name "*.php" -exec sed -i '' 's/User::factory()->create()/User::factory()->create()/g' {} \;

# 7. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 8. Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
php artisan test

echo "✅ Actualización a Laravel 10 completada. Por favor, revise los mensajes de error (si los hay) y verifique que la aplicación funcione correctamente."
