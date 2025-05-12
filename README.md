# Apuntes Curso Livewire

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Estado Actual del Proyecto

Este proyecto es una aplicación de gestión de posts desarrollada con Laravel 8 y Livewire 2.5. Actualmente cuenta con las siguientes características:

### Funcionalidades implementadas

- Sistema de autenticación mediante Jetstream
- CRUD completo de Posts (Crear, Leer, Actualizar, Eliminar)
- Carga de imágenes para los posts
- Búsqueda y filtrado de posts
- Paginación de resultados
- Ordenamiento de posts por diferentes criterios

### Componentes Livewire

- **ShowPosts**: Visualización y gestión de la lista de posts
- **CreatePost**: Formulario para la creación de nuevos posts
- **EditPost**: Formulario para la edición de posts existentes

### Tecnologías utilizadas

- **Laravel 8**
- **Livewire 2.5**
- **Jetstream**
- **MySQL**
- **Tailwind CSS**

## Ejecutar con Docker

### Requisitos previos

- Docker
- Docker Compose

### Pasos para ejecutar el proyecto

1. Clonar este repositorio
   ```bash
   git clone [url-del-repositorio]
   cd [nombre-del-repositorio]
   ```

2. Ejecutar el script de inicio (esto configurará todo el entorno Docker)
   ```bash
   ./docker-compose-up.sh
   ```

3. Acceder a la aplicación
   ```
   http://localhost:8000
   ```

### Estructura de Docker

El proyecto está configurado con los siguientes servicios:

- **app**: Servicio PHP que ejecuta la aplicación Laravel
- **db**: Servicio MySQL para la base de datos
- **nginx**: Servidor web para servir la aplicación

### Comandos útiles

- Iniciar los contenedores: `docker-compose up -d`
- Detener los contenedores: `docker-compose down`
- Ejecutar comandos en el contenedor: `docker-compose exec app [comando]`
- Ver logs: `docker-compose logs -f`

## Desarrollo local sin Docker

### Requisitos

- PHP >= 7.3
- Composer
- Node.js y NPM
- MySQL

### Configuración

1. Clonar el repositorio
2. Instalar dependencias PHP:
   ```bash
   composer install
   ```
3. Configurar el archivo `.env` con las credenciales de tu base de datos
4. Generar clave de aplicación:
   ```bash
   php artisan key:generate
   ```
5. Ejecutar migraciones:
   ```bash
   php artisan migrate
   ```
6. Instalar dependencias de frontend:
   ```bash
   npm install && npm run dev
   ```
7. Iniciar el servidor:
   ```bash
   php artisan serve
   ```

## Licencia

Este proyecto utiliza [la licencia MIT](https://opensource.org/licenses/MIT).
