#!/bin/bash

# Script para actualizar de Laravel 10 a Laravel 11
echo "🔄 Iniciando actualización a Laravel 11..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Verificar requisitos de PHP
php_version=$(php -r "echo PHP_VERSION;")
echo "🔍 Verificando versión de PHP: $php_version"
php -r "if (version_compare(PHP_VERSION, '8.2.0', '<')) { echo '❌ Laravel 11 requiere PHP 8.2.0 o superior. Por favor, actualice PHP antes de continuar.'; exit(1); }"

# 3. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos PHP y Laravel
sed -i '' 's/"php": "^8.1"/"php": "^8.2"/g' composer.json
sed -i '' 's/"laravel\/framework": "\^10.0"/"laravel\/framework": "^11.0"/g' composer.json

# Jetstream (si está instalado)
if grep -q "laravel\/jetstream" composer.json; then
    echo "🔄 Actualizando Laravel Jetstream..."
    sed -i '' 's/"laravel\/jetstream": "\^[0-9]\+\.[0-9]\+"/"laravel\/jetstream": "^5.0"/g' composer.json
fi

# Sanctum (si está instalado)
if grep -q "laravel\/sanctum" composer.json; then
    echo "🔄 Actualizando Laravel Sanctum..."
    sed -i '' 's/"laravel\/sanctum": "\^[0-9]\+\.[0-9]\+"/"laravel\/sanctum": "^4.0"/g' composer.json
fi

# Otros paquetes comunes
if grep -q "laravel\/passport" composer.json; then
    echo "🔄 Actualizando Laravel Passport..."
    sed -i '' 's/"laravel\/passport": "\^[0-9]\+\.[0-9]\+"/"laravel\/passport": "^12.0"/g' composer.json
fi

if grep -q "laravel\/cashier" composer.json; then
    echo "🔄 Actualizando Laravel Cashier..."
    sed -i '' 's/"laravel\/cashier": "\^[0-9]\+\.[0-9]\+"/"laravel\/cashier": "^15.0"/g' composer.json
fi

if grep -q "laravel\/telescope" composer.json; then
    echo "🔄 Actualizando Laravel Telescope..."
    sed -i '' 's/"laravel\/telescope": "\^[0-9]\+\.[0-9]\+"/"laravel\/telescope": "^5.0"/g' composer.json
fi

if grep -q "livewire\/livewire" composer.json; then
    echo "🔄 Actualizando Livewire..."
    sed -i '' 's/"livewire\/livewire": "\^[0-9]\+\.[0-9]\+"/"livewire\/livewire": "^3.4"/g' composer.json
fi

# 4. Actualizar dependencias de desarrollo
echo "🔧 Actualizando dependencias de desarrollo..."
sed -i '' 's/"nunomaduro\/collision": "\^[0-9]\+\.[0-9]\+"/"nunomaduro\/collision": "^8.1"/g' composer.json
sed -i '' 's/"phpunit\/phpunit": "\^[0-9]\+\.[0-9]\+"/"phpunit\/phpunit": "^10.1"/g' composer.json
sed -i '' 's/"spatie\/laravel-ignition": "\^[0-9]\+\.[0-9]\+"/"spatie\/laravel-ignition": "^3.0"/g' composer.json

# 5. Remover doctrine/dbal si está presente
if grep -q "doctrine\/dbal" composer.json; then
    echo "🗑️ Eliminando doctrine/dbal (ya no es necesario en Laravel 11)..."
    composer remove doctrine/dbal --no-update
fi

# 6. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction

# 7. Publicar migraciones para paquetes que ahora lo requieren
echo "📋 Publicando migraciones de paquetes..."

if grep -q "laravel\/sanctum" composer.json; then
    echo "📋 Publicando migraciones de Sanctum..."
    php artisan vendor:publish --tag=sanctum-migrations
fi

if grep -q "laravel\/passport" composer.json; then
    echo "📋 Publicando migraciones de Passport..."
    php artisan vendor:publish --tag=passport-migrations
fi

if grep -q "laravel\/cashier" composer.json; then
    echo "📋 Publicando migraciones de Cashier..."
    php artisan vendor:publish --tag=cashier-migrations
fi

if grep -q "laravel\/telescope" composer.json; then
    echo "📋 Publicando migraciones de Telescope..."
    php artisan vendor:publish --tag=telescope-migrations
fi

# 8. Habilitando Password Grant para Passport (si aplica)
if grep -q "laravel\/passport" composer.json; then
    echo "⚙️ Ajustando configuraciones de Passport..."
    echo "⚠️ Si utilizas el Password Grant en Passport, necesitas habilitarlo manualmente en AppServiceProvider:"
    echo "   Passport::enablePasswordGrant();"
fi

# 9. Verificar cambios críticos en las migraciones
echo "⚠️ IMPORTANTE: Laravel 11 ha cambiado cómo se modifican las columnas en las migraciones."
echo "   Al modificar una columna, ahora debes incluir explícitamente TODOS los modificadores que deseas mantener."
echo "   Consulta la documentación para más detalles sobre cambios a floating-point types y modificación de columnas."

# 10. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 11. Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
php artisan test

echo "✅ Actualización a Laravel 11 completada."
echo ""
echo "⚠️ NOTAS IMPORTANTES:"
echo "  - Revisa la guía oficial de actualización para más detalles: https://laravel.com/docs/11.x/upgrade"
echo "  - Verifica cualquier problema con tus migraciones relacionadas con tipos floating-point"
echo "  - Comprueba que tus migraciones que modifican columnas incluyen explícitamente todos los modificadores"
echo "  - Si utilizas características específicas como UUIDs, Carbon o Rate Limiting, consulta la guía"
