#!/bin/bash

# Script para actualizar de Laravel 11 a Laravel 12
echo "🔄 Iniciando actualización a Laravel 12..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos Laravel
sed -i '' 's/"laravel\/framework": "\^11.0"/"laravel\/framework": "^12.0"/g' composer.json
sed -i '' 's/"laravel\/jetstream": "\^4.0"/"laravel\/jetstream": "^5.0"/g' composer.json

# 3. Actualizar dependencias de desarrollo
echo "🔧 Actualizando dependencias de desarrollo..."
sed -i '' 's/"fakerphp\/faker": "\^1.9.1"/"fakerphp\/faker": "^1.23"/g' composer.json
sed -i '' 's/"nunomaduro\/collision": "\^8.0"/"nunomaduro\/collision": "^8.1"/g' composer.json
sed -i '' 's/"phpunit\/phpunit": "\^10.1"/"phpunit\/phpunit": "^10.4"/g' composer.json
sed -i '' 's/"spatie\/laravel-ignition": "\^3.0"/"spatie\/laravel-ignition": "^3.3"/g' composer.json

# 4. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction --no-progress

# 5. Verificar si es necesario actualizar Livewire a versión 3
echo "⚙️ Verificando Livewire..."
if grep -q "livewire/livewire" composer.json; then
    echo "⚠️ Este proyecto usa Livewire. Considera actualizar a Livewire 3:"
    echo "composer require livewire/livewire:^3.0"
    echo "Consulta la documentación oficial para la migración: https://livewire.laravel.com/docs/upgrading"
fi

# 6. Actualizar seeders si es necesario
echo "🌱 Verificando seeders..."
if [ -d "database/seeders" ]; then
    echo "Verificando y adaptando seeders a Laravel 12..."
    for file in database/seeders/*.php; do
        if grep -q "namespace Database\\Seeders" "$file"; then
            echo "Actualizando namespace en $file..."
            sed -i '' 's/namespace Database\\Seeders;/namespace Database\\Seeders;\\nuse Illuminate\\Database\\Seeder;/g' "$file"
        fi
    done
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

# 9. Actualizar configuración Docker
echo "🐳 Recordatorio para actualizar la configuración Docker..."
echo "⚠️ No olvides actualizar tu Dockerfile para usar PHP 8.2+ y las dependencias necesarias"

echo "✅ Actualización a Laravel 12 completada. Por favor, revise los mensajes de error (si los hay) y verifique que la aplicación funcione correctamente."
