# Plan de Actualización de Laravel 8 a Laravel 12

Este documento detalla el plan para actualizar el proyecto desde Laravel 8 hasta Laravel 12, pasando por cada versión intermedia, así como las actualizaciones necesarias en la configuración de Docker.

## Fase 1: Preparación

1. **Crear pruebas exhaustivas**
   - Se han creado pruebas para la gestión de posts (CRUD) ✅
   - Se han creado pruebas para la autenticación de Jetstream ✅

2. **Ejecutar pruebas para asegurar el estado actual**
   ```bash
   php artisan test
   ```

## Fase 2: Actualización a Laravel 9

1. **Actualizar dependencias en composer.json**
   ```json
   "php": "^8.0",
   "laravel/framework": "^9.0",
   "laravel/jetstream": "^2.6",
   "laravel/sanctum": "^2.14",
   ```

2. **Actualizar otras dependencias para compatibilidad**
   ```json
   "require-dev": {
       "fakerphp/faker": "^1.9.1",
       "laravel/sail": "^1.0.1",
       "mockery/mockery": "^1.4.4",
       "nunomaduro/collision": "^6.1",
       "phpunit/phpunit": "^9.5.10",
       "spatie/laravel-ignition": "^1.0"
   }
   ```

3. **Ejecutar actualización**
   ```bash
   composer update
   ```

4. **Migrar a Flysystem 3.x**
   - Revisar y actualizar código que utilice almacenamiento

5. **Revisar Middleware de Cors**
   - Reemplazar fruitcake/laravel-cors por Laravel CORS middleware

6. **Actualizar sintaxis Blade**
   - Revisar y actualizar plantillas Blade que usan la sintaxis antigua

7. **Verificar funcionalidad**
   ```bash
   php artisan test
   ```

## Fase 3: Actualización a Laravel 10

1. **Actualizar dependencias en composer.json**
   ```json
   "php": "^8.1",
   "laravel/framework": "^10.0",
   "laravel/jetstream": "^3.0",
   "laravel/sanctum": "^3.2",
   ```

2. **Actualizar otras dependencias para compatibilidad**
   ```json
   "require-dev": {
       "fakerphp/faker": "^1.9.1",
       "laravel/sail": "^1.18",
       "mockery/mockery": "^1.4.4",
       "nunomaduro/collision": "^7.0",
       "phpunit/phpunit": "^10.0",
       "spatie/laravel-ignition": "^2.0"
   }
   ```

3. **Ejecutar actualización**
   ```bash
   composer update
   ```

4. **Migrar desde Factory a Factory::new()**
   - Actualizar llamadas a Factory en pruebas

5. **Actualizar namespace de Pruebas**
   - Laravel 10 tiene nuevas convenciones para namespaces de pruebas

6. **Verificar funcionalidad**
   ```bash
   php artisan test
   ```

## Fase 4: Actualización a Laravel 11

1. **Actualizar dependencias en composer.json**
   ```json
   "php": "^8.2",
   "laravel/framework": "^11.0",
   "laravel/jetstream": "^4.0",
   "laravel/sanctum": "^4.0",
   ```

2. **Actualizar otras dependencias para compatibilidad**
   ```json
   "require-dev": {
       "fakerphp/faker": "^1.9.1",
       "laravel/sail": "^1.25",
       "mockery/mockery": "^1.6",
       "nunomaduro/collision": "^8.0",
       "phpunit/phpunit": "^10.1",
       "spatie/laravel-ignition": "^3.0"
   }
   ```

3. **Ejecutar actualización**
   ```bash
   composer update
   ```

4. **Migrar a la nueva estructura de directorios**
   - Laravel 11 reorganiza archivos del framework

5. **Actualizar sintaxis de rutas**
   - Utilizar la nueva API para definir rutas

6. **Verificar funcionalidad**
   ```bash
   php artisan test
   ```

## Fase 5: Actualización a Laravel 12

1. **Actualizar dependencias en composer.json**
   ```json
   "php": "^8.2",
   "laravel/framework": "^12.0",
   "laravel/jetstream": "^5.0",
   "laravel/sanctum": "^4.0",
   ```

2. **Actualizar otras dependencias para compatibilidad**
   ```json
   "require-dev": {
       "fakerphp/faker": "^1.23",
       "laravel/sail": "^1.26",
       "mockery/mockery": "^1.6",
       "nunomaduro/collision": "^8.1",
       "phpunit/phpunit": "^10.4",
       "spatie/laravel-ignition": "^3.3"
   }
   ```

3. **Ejecutar actualización**
   ```bash
   composer update
   ```

4. **Actualizar sintaxis Livewire**
   - Migrar a Livewire 3 si es necesario

5. **Verificar funcionalidad**
   ```bash
   php artisan test
   ```

## Fase 6: Actualización de Docker

1. **Actualizar Dockerfile**
   ```dockerfile
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
       zip \
       unzip \
       libzip-dev

   # Limpiar cache
   RUN apt-get clean && rm -rf /var/lib/apt/lists/*

   # Instalar extensiones de PHP
   RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

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

   # Cambiar al usuario
   USER $user

   # Exponer puerto e iniciar php-fpm server
   EXPOSE 9000
   CMD ["php-fpm"]
   ```

2. **Actualizar docker-compose.yml**
   ```yaml
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
   ```

## Recomendaciones Generales

1. **Hacer copia de seguridad antes de cada actualización**
2. **Realizar cada actualización en una rama separada**
3. **Documentar todos los cambios realizados**
4. **Ejecutar pruebas después de cada fase de actualización**
5. **Revisar cambios en la documentación oficial de Laravel para cada versión** 
