#!/bin/bash

echo "Iniciando el entorno Docker para Laravel con Livewire..."

# Verificar si .env existe, si no, copiarlo desde docker.env
if [ ! -f .env ]; then
    echo "Archivo .env no encontrado, copiando desde docker.env..."
    cp docker.env .env
fi

# Construir los contenedores
docker-compose build

# Iniciar los contenedores en segundo plano
docker-compose up -d

# Instalar dependencias de Composer
docker-compose exec app composer install

# Generar clave de aplicación
docker-compose exec app php artisan key:generate

# Crear enlace simbólico para el almacenamiento
echo "Creando enlace simbólico para storage..."
docker-compose exec app php artisan storage:link

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Instalar dependencias de npm e iniciar compilación
docker-compose exec app npm install
docker-compose exec app npm run dev

echo "El entorno Docker ha sido iniciado correctamente."
echo "Puedes acceder a la aplicación en http://localhost:8000"
