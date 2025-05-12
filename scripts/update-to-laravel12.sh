#!/bin/bash

# Script para actualizar de Laravel 11 a Laravel 12
echo "🔄 Iniciando actualización a Laravel 12..."

# 1. Respaldo del estado actual
echo "📦 Creando respaldo del estado actual..."
cp composer.json composer.json.bak
cp composer.lock composer.lock.bak

# 2. Verificar requisitos de PHP
php_version=$(php -r "echo PHP_VERSION;")
echo "🔍 Verificando versión de PHP: $php_version"
php -r "if (version_compare(PHP_VERSION, '8.2.0', '<')) { echo '❌ Laravel 12 requiere PHP 8.2.0 o superior. Por favor, actualice PHP antes de continuar.'; exit(1); }"

# 3. Actualizar dependencias en composer.json
echo "📝 Actualizando dependencias en composer.json..."
# Actualizamos Laravel
sed -i '' 's/"laravel\/framework": "\^11.0"/"laravel\/framework": "^12.0"/g' composer.json

# Actualizamos PHPUnit a la versión 11
if grep -q "phpunit\/phpunit" composer.json; then
    echo "🔄 Actualizando PHPUnit..."
    sed -i '' 's/"phpunit\/phpunit": "\^[0-9]\+\.[0-9]\+"/"phpunit\/phpunit": "^11.0"/g' composer.json
fi

# Actualizamos PEST si está presente
if grep -q "pestphp\/pest" composer.json; then
    echo "🔄 Actualizando PEST..."
    sed -i '' 's/"pestphp\/pest": "\^[0-9]\+\.[0-9]\+"/"pestphp\/pest": "^3.0"/g' composer.json
fi

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
sed -i '' 's/"phpunit\/phpunit": "\^[0-9]\+\.[0-9]\+"/"phpunit\/phpunit": "^11.0"/g' composer.json
sed -i '' 's/"spatie\/laravel-ignition": "\^[0-9]\+\.[0-9]\+"/"spatie\/laravel-ignition": "^3.0"/g' composer.json

# 5. Ejecutar actualización
echo "⬆️ Ejecutando actualización de dependencias..."
composer update --prefer-dist --no-interaction

# 6. Publicar y actualizar archivos de configuración
echo "🛠️ Actualizando configuraciones..."
php artisan vendor:publish --tag=laravel-config --force

# 7. Actualizar archivos de bootstrap
echo "🔄 Actualizando archivos de bootstrap..."
if [ -f bootstrap/app.php ]; then
    echo "  Actualizando bootstrap/app.php..."
    curl -s https://raw.githubusercontent.com/laravel/laravel/12.x/bootstrap/app.php -o bootstrap/app.new.php

    # Comparar y actualizar solo si hay cambios significativos
    if ! diff -q bootstrap/app.php bootstrap/app.new.php >/dev/null; then
        mv bootstrap/app.new.php bootstrap/app.php
    else
        rm bootstrap/app.new.php
    fi
fi

# 8. Actualizar estructura de rutas
echo "🛣️ Actualizando estructura de rutas..."
if [ -f routes/web.php ] && ! grep -q "defineWebRoutes" routes/web.php; then
    echo "  Actualizando routes/web.php para usar el nuevo patrón de definición de rutas..."
    cp routes/web.php routes/web.php.bak
    cat > routes/web.php << 'EOF'
<?php

use Illuminate\Support\Facades\Route;

Route::defineWebRoutes(function () {
    // Contenido previo de web.php
EOF

    # Agregamos el contenido previo sin la primera línea (uso de Route)
    tail -n +3 routes/web.php.bak >> routes/web.php
    # Cerramos la función
    echo "});" >> routes/web.php
fi

if [ -f routes/api.php ] && ! grep -q "defineApiRoutes" routes/api.php; then
    echo "  Actualizando routes/api.php para usar el nuevo patrón de definición de rutas..."
    cp routes/api.php routes/api.php.bak
    cat > routes/api.php << 'EOF'
<?php

use Illuminate\Support\Facades\Route;

Route::defineApiRoutes(function () {
    // Contenido previo de api.php
EOF

    # Agregamos el contenido previo sin la primera línea (uso de Route)
    tail -n +3 routes/api.php.bak >> routes/api.php
    # Cerramos la función
    echo "});" >> routes/api.php
fi

# 9. Verificar y actualizar modelos que utilizan UUIDs
echo "🆔 Verificando modelos que utilizan UUIDs..."
model_files=$(find app/Models -type f -name "*.php" 2>/dev/null)
if [ -n "$model_files" ]; then
    for file in $model_files; do
        # Verificar si el modelo usa HasUuids y HasVersion7Uuids
        if grep -q "HasVersion7Uuids" "$file"; then
            echo "⚠️ El modelo $file utiliza HasVersion7Uuids, que ha sido eliminado en Laravel 12."
            echo "   Actualizando a HasUuids, que ahora proporciona el mismo comportamiento..."
            sed -i '' 's/use Illuminate\\Database\\Eloquent\\Concerns\\HasVersion7Uuids/use Illuminate\\Database\\Eloquent\\Concerns\\HasUuids/g' "$file"
        fi

        # Informar si usa HasUuids (ahora es UUIDv7 por defecto)
        if grep -q "HasUuids" "$file" && ! grep -q "HasVersion4Uuids" "$file"; then
            echo "⚠️ El modelo $file utiliza HasUuids, que ahora genera UUIDv7 por defecto."
            echo "   Si necesita seguir usando UUIDv4, cambie a HasVersion4Uuids."
        fi
    done
fi

# 10. Publicar migraciones para paquetes que lo requieran
echo "📋 Publicando migraciones de paquetes..."

if grep -q "laravel\/sanctum" composer.json; then
    echo "📋 Publicando migraciones de Sanctum..."
    php artisan vendor:publish --tag=sanctum-migrations --force
fi

if grep -q "laravel\/passport" composer.json; then
    echo "📋 Publicando migraciones de Passport..."
    php artisan vendor:publish --tag=passport-migrations --force
fi

# 11. Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 12. Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
php artisan test

echo "✅ Actualización a Laravel 12 completada."
echo ""
echo "⚠️ NOTAS IMPORTANTES:"
echo "  - Revisa la guía oficial de actualización para más detalles: https://laravel.com/docs/12.x/upgrade"
echo "  - Los modelos que utilizan HasUuids ahora generan UUIDv7 por defecto (cambia a HasVersion4Uuids si necesitas UUIDv4)"
echo "  - Las validaciones de imágenes ya no permiten SVG por defecto (usa image:allow_svg para permitirlos)"
echo "  - Carbon 3.x es ahora requerido (Carbon 2.x ya no es compatible)"
echo "  - Verifica los cambios en la estructura de rutas (defineWebRoutes y defineApiRoutes)"
echo "  - El contenedor de inyección de dependencias ahora respeta los valores predeterminados de las propiedades de clase"
echo "  - El método \$request->mergeIfMissing() ahora permite fusionar datos de matriz anidados con notación de puntos"
