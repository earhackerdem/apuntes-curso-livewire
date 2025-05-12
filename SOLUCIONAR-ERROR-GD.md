# Solución al Error de GD en Docker

## Problema

Al ejecutar las pruebas con `php artisan test` en el contenedor Docker, aparece el siguiente error:

```
Tests\Feature\PostManagementTest > can create post
   TypeError 

  call_user_func(): Argument #1 ($callback) must be a valid callback, function "imagejpeg" not found or invalid function name

  at vendor/laravel/framework/src/Illuminate/Http/Testing/FileFactory.php:79
     75▕                 : 'jpeg';
     76▕ 
     77▕             $image = imagecreatetruecolor($width, $height);
     78▕ 
  ➜  79▕             call_user_func("image{$extension}", $image);
     80▕ 
     81▕             fwrite($temp, ob_get_clean());
     82▕         });
     83▕     }
```

Este error ocurre porque la extensión GD de PHP no está correctamente instalada o configurada en el contenedor Docker. La extensión GD es necesaria para manipular imágenes y es usada por Laravel para crear imágenes falsas durante las pruebas.

## Solución a Corto Plazo

### Usando el Script de Arreglo Rápido

Ejecuta el siguiente script para instalar correctamente la extensión GD en el contenedor Docker en ejecución:

```bash
bash scripts/fix-gd-extension.sh
```

Este script instalará todas las dependencias necesarias y configurará GD con soporte para varios formatos de imagen.

### Modificación Manual

Si prefieres hacer los cambios manualmente:

1. Accede al contenedor Docker:
```bash
docker-compose exec app bash
```

2. Instala las dependencias necesarias:
```bash
apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev
```

3. Configura y compila la extensión GD:
```bash
docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
docker-php-ext-install -j$(nproc) gd
```

4. Verifica la instalación:
```bash
php -r 'var_dump(gd_info());'
```

## Solución Permanente

Para solucionar este problema de forma permanente, hemos actualizado el `Dockerfile` en el script de actualización de Docker (`scripts/update-docker.sh`). El Dockerfile actualizado incluye:

```dockerfile
# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip
```

Para aplicar estos cambios, ejecuta:

```bash
bash scripts/update-docker.sh
docker-compose down
docker-compose up -d --build
```

## Tests Alternativos

También hemos creado una versión alternativa de las pruebas que no depende de la creación de imágenes con GD:

```php
// tests/Feature/ModifyPostTest.php
// ...
public function it_can_create_a_post()
{
    // ...
    // Usar un archivo simple en lugar de una imagen
    $file = UploadedFile::fake()->create('post.jpg', 100, 'image/jpeg');
    // ...
}
```

Para ejecutar solo estas pruebas alternativas:

```bash
docker-compose exec app php artisan test --filter=ModifyPostTest
```

## Notas para la Actualización

Cuando actualices Laravel siguiendo nuestro plan, no olvides asegurarte de que la extensión GD esté correctamente configurada en tu entorno Docker para evitar problemas con las pruebas de carga de imágenes. 
