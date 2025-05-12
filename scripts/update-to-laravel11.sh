#!/bin/bash

# Script para actualizar de Laravel 10 a Laravel 11
echo "🔄 Iniciando actualización a Laravel 11..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos PHP y Laravel
sed -i '' 's/"php": "^8.1"/"php": "^8.2"/g' composer.json
sed -i '' 's/"laravel\/framework": "\^10.0"/"laravel\/framework": "^11.0"/g' composer.json
sed -i '' 's/"laravel\/jetstream": "\^3.0"/"laravel\/jetstream": "^4.0"/g' composer.json
sed -i '' 's/"laravel\/sanctum": "\^3.2"/"laravel\/sanctum": "^4.0"/g' composer.json

# 3. Actualizar dependencias de desarrollo
echo "🔧 Actualizando dependencias de desarrollo..."
sed -i '' 's/"nunomaduro\/collision": "\^7.0"/"nunomaduro\/collision": "^8.0"/g' composer.json
sed -i '' 's/"phpunit\/phpunit": "\^10.0"/"phpunit\/phpunit": "^10.1"/g' composer.json
sed -i '' 's/"spatie\/laravel-ignition": "\^2.0"/"spatie\/laravel-ignition": "^3.0"/g' composer.json

# 4. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction --no-progress

# 5. Instalar Laravel Cleanup para ayudar a actualizar estructura
echo "🧹 Instalando Laravel Cleanup..."
composer require --dev laravel/cleanup

# 6. Ejecutar Cleanup
echo "🔄 Ejecutando Cleanup para adaptar estructura a Laravel 11..."
php artisan cleanup:run

# 7. Actualizar archivo bootstrap/app.php
echo "⚙️ Actualizando bootstrap/app.php para Laravel 11..."
if [ -f "bootstrap/app.php" ]; then
    # Verificar si debemos actualizar bootstrap/app.php
    if ! grep -q "bootstrap\\AppContext" bootstrap/app.php; then
        echo "⚠️ El archivo bootstrap/app.php debe actualizarse manualmente según la documentación de Laravel 11"
    fi
fi

# 8. Actualizar rutas a nuevo formato de Laravel 11
echo "🛣️ Actualizando rutas a nuevo formato de Laravel 11..."
if [ -d "routes" ]; then
    echo "⚠️ Las rutas deben actualizarse manualmente al nuevo formato de Laravel 11 (Closure-based)"
fi

# 9. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 10. Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
php artisan test

echo "✅ Actualización a Laravel 11 completada. Por favor, revise los mensajes de error (si los hay) y verifique que la aplicación funcione correctamente."
echo "⚠️ NOTA IMPORTANTE: Laravel 11 tiene cambios significativos en la estructura de directorios y la sintaxis de rutas. Es posible que necesite realizar cambios adicionales manualmente."
