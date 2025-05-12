# Apuntes Curso Livewire

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Estado Actual del Proyecto

Este proyecto es una aplicación de gestión de posts desarrollada con Laravel y Livewire. Actualmente cuenta con las siguientes características:

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

- **Laravel**
- **Livewire**
- **Jetstream**
- **MySQL**
- **Tailwind CSS**

## Estado de Migración (Laravel 8 → Laravel 12)

### ✅ Actualización a Laravel 10 (Completada)

Se ha completado exitosamente la migración a Laravel 10 con Jetstream funcionando correctamente. Los cambios realizados incluyen:

#### Componentes Blade de Jetstream
Se crearon todos los componentes Blade necesarios para Jetstream:
- Componentes de formulario: `jet-input`, `jet-input-error`, `jet-label`, `jet-checkbox`, `jet-validation-errors`
- Componentes de sección: `jet-action-section`, `jet-section-title`, `jet-section-border`
- Componentes de botones: `jet-button`, `jet-secondary-button`, `jet-danger-button`, `jet-action-message`
- Componentes modales: `jet-dialog-modal`, `jet-modal`
- Componentes de autenticación: `jet-authentication-card`, `jet-authentication-card-logo`

#### Implementación de funcionalidad de equipos (Teams)
Para habilitar la característica de equipos en Jetstream:
- Modelos: `Team`, `Membership`, `TeamInvitation`
- Migraciones: tablas `teams`, `team_user`, `team_invitations`
- Controladores: `CurrentTeamController`, `TeamController`
- Vistas: creación y visualización de equipos
- Rutas: gestión completa de equipos
- Configuración: habilitada la característica de equipos en `config/jetstream.php`

#### Tests
Se resolvieron todos los problemas en las pruebas automatizadas:
- 39 pruebas pasando correctamente
- 7 omitidas (para características no habilitadas)
- 1 test marcado como "risky"

### 🔄 Próximos pasos
- Actualización a Laravel 11
- Actualización a Laravel 12
- Actualización de la configuración Docker

## Plan de Actualización de Laravel 8 a Laravel 12

Este proyecto incluye un plan completo para actualizar de Laravel 8 a Laravel 12, siguiendo estos pasos:

1. ✅ Actualización de Laravel 8 a Laravel 9
2. ✅ Actualización de Laravel 9 a Laravel 10
3. 🔄 Actualización de Laravel 10 a Laravel 11
4. 🔄 Actualización de Laravel 11 a Laravel 12
5. 🔄 Actualización de la configuración Docker

Cada paso de actualización incluye scripts automatizados que facilitan el proceso:

```bash
# Ejecutar las pruebas antes de actualizar
php artisan test

# Actualizar a Laravel 9
bash scripts/update-to-laravel9.sh

# Actualizar a Laravel 10
bash scripts/update-to-laravel10.sh

# Actualizar a Laravel 11
bash scripts/update-to-laravel11.sh

# Actualizar a Laravel 12
bash scripts/update-to-laravel12.sh

# Actualizar configuración Docker
bash scripts/update-docker.sh
```

Para más detalles, consulta el archivo `UPGRADE-PLAN.md`.

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

- PHP >= 8.2
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
