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

## Estado de Migración completo

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

### ✅ Actualización a Laravel 11 (Completada)
La migración a Laravel 11 se completó con éxito, implementando los siguientes cambios:

1. **Actualización de dependencias**:
   - Laravel Framework de 10.x a 11.x
   - Laravel Jetstream de 2.x a 3.x
   - Laravel Sanctum de 2.x a 3.x
   - Otras dependencias de desarrollo

2. **Correcciones en archivos de configuración**:
   - Modificación del archivo `bootstrap/app.php` para usar el método `$app->configure()`
   - Actualización de migraciones para nuevos tipos de datos

3. **Soporte para PHP 8.2**:
   - Verificación de compatibilidad con PHP 8.2

### ✅ Actualización a Laravel 12 (Completada)
La migración a Laravel 12 se completó exitosamente, implementando:

1. **Actualización de dependencias**:
   - Laravel Framework de 11.x a 12.x
   - Laravel Jetstream de 3.x a 5.x
   - Laravel Sanctum de 3.x a 4.x
   - PHPUnit de 10.x a 11.x

2. **Correcciones en archivos de configuración**:
   - Modificación de `bootstrap/app.php` para la estructura de Laravel 12
   - Actualización de archivos de rutas sin usar `defineWebRoutes` y `defineApiRoutes`
   - Actualización del formato de PHPUnit en `phpunit.xml`

3. **Correcciones en migraciones**:
   - Compatibilidad con SQLite para pruebas
   - Eliminación de migraciones duplicadas

### ✅ Actualización a Livewire 3 (Completada)
Se migró exitosamente de Livewire 2.x a 3.x, implementando:

1. **Actualización de componentes**:
   - Uso de atributos PHP 8 para validación: `#[Rule('required')]`
   - Reemplazo de `$queryString` por atributos `#[Url]`
   - Definición de listeners con `#[On('evento')]`
   - Reemplazo de emisión de eventos (`$this->emit()` → `$this->dispatch()`)

2. **Componentes actualizados**:
   - ShowPosts: Componente principal para visualizar posts
   - CreatePost: Formulario para crear posts
   - EditPost: Formulario para editar posts
   - Componentes de perfil:
     - UpdatePasswordForm
     - LogoutOtherBrowserSessionsForm
     - DeleteUserForm
     - UpdateProfileInformationForm
     - TwoFactorAuthenticationForm

3. **Sintaxis de vistas actualizada**:
   - De `@livewire('componente')` a `<livewire:componente />`
   - De `wire:model` a `wire:model.live` para actualizaciones en tiempo real

## Aspectos pendientes por resolver

### 1. Errores en pruebas automatizadas
- [ ] Actualizar pruebas para utilizar atributos PHPUnit en lugar de anotaciones de doc-comment
- [ ] Corregir errores de propiedad `$id` en los componentes de perfil de Jetstream
- [ ] Actualizar vistas de componentes de Jetstream para ser compatibles con Livewire 3

### 2. Mejoras de rendimiento
- [ ] Implementar caché de vistas para mejorar el rendimiento
- [ ] Optimizar consultas a base de datos en componentes ShowPosts

### 3. Mejoras visuales y UX
- [ ] Actualizar los componentes visuales a Tailwind CSS v3
- [ ] Mejorar la experiencia móvil

### 4. Actualizaciones de Docker
- [ ] Optimizar configuración de Docker para desarrollo local
- [ ] Eliminar atributos obsoletos en docker-compose.yml

## Instrucciones para completar la migración

1. **Corregir errores en pruebas automatizadas**:
   ```bash
   # Actualizar pruebas para usar atributos PHPUnit
   php artisan test:migrate-attributes
   
   # Ejecutar pruebas para verificar
   php artisan test
   ```

2. **Revisar componentes de Livewire**:
   - Verificar compatibilidad de todos los componentes con Livewire 3
   - Asegurar que todos los eventos están correctamente implementados

3. **Validar soporte para PHP 8.2+**:
   - Pruebas en diferentes versiones de PHP
   - Asegurar compatibilidad con tipado estricto

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

# Apuntes Curso Livewire - Migración a Laravel 12

## Migración del proyecto a Laravel 12

### Proceso de migración

Hemos completado la migración del proyecto a Laravel 12, que incluyó estos pasos:

1. **Actualización de dependencias**:
   - Laravel Framework de 11.x a 12.x
   - Laravel Jetstream de 3.x a 5.x
   - Laravel Sanctum de 3.x a 4.x
   - Livewire de 2.x a 3.x
   - PHPUnit de 10.x a 11.x
   - Otras dependencias de desarrollo

2. **Correcciones en archivos de configuración**:
   - Modificación del archivo `bootstrap/app.php` para usar la estructura de Laravel 12
   - Actualización de archivos de rutas (`routes/web.php` y `routes/api.php`) para eliminar el uso de `defineWebRoutes` y `defineApiRoutes`

3. **Cuestiones pendientes**:
   - Migración de componentes Livewire de la versión 2 a la versión 3
   - Corrección de pruebas que fallan debido a los cambios en Livewire y Jetstream

### Cambios principales en Laravel 12

- **Requisitos mínimos**: PHP 8.2+
- **UUIDs**: Ahora usa UUIDv7 como estándar para IDs generados
- **Carbon 3**: Eliminación del soporte para Carbon 2.x
- **Validación de imágenes**: Las validaciones de imágenes ya no permiten SVG por defecto
- **Container**: El contenedor ahora respeta los valores predeterminados de las propiedades de clase
- **Esquemas de base de datos**: Mejoras en inspección y operaciones multi-esquema

Para más detalles sobre los cambios en Laravel 12, consulta la [guía oficial de actualización](https://laravel.com/docs/12.x/upgrade).

## Migración a Livewire 3

Hemos completado la migración de componentes Livewire a la versión 3. Los cambios principales incluyen:

### Cambios en los componentes

1. **Actualización de propiedades y validación**:
   - Uso de atributos en PHP 8 para definir reglas de validación con `#[Rule('required')]`
   - Reemplazo de `$queryString` por atributos `#[Url]`
   - Definición de listeners con atributo `#[On('evento')]`

2. **Actualización de eventos**:
   - Reemplazo de `$this->emit()` por `$this->dispatch()`
   - Reemplazo de `$this->emitTo()` por `$this->dispatch()->to()`
   - Cambio en la estructura de los listeners de eventos

3. **Actualización en plantillas Blade**:
   - Cambio de `@livewire('componente')` a `<livewire:componente />`
   - Actualización de `wire:model` a `wire:model.live` para actualización en tiempo real
   - Cambio de `wire:model.defer` a `wire:model`
   - Actualización de `$emit` a `$dispatch` en JavaScript

### Componentes actualizados

- **ShowPosts**: Componente principal para visualizar y gestionar posts
- **CreatePost**: Formulario para crear nuevos posts
- **EditPost**: Formulario para editar posts existentes

### Scripts JavaScript

También se actualizaron los scripts JavaScript para usar el nuevo sistema de eventos de Livewire 3:

```javascript
document.addEventListener('livewire:initialized', () => {
    Livewire.on('alert', (event) => {
        // Código del evento
    });
});
```

Para más información sobre Livewire 3, consulta la [documentación oficial](https://livewire.laravel.com/docs/3.x/).

## Próximos pasos

1. ✅ Completar la migración de componentes Livewire a la versión 3
2. Optimizar el rendimiento de la aplicación
3. Implementar pruebas automatizadas para los componentes Livewire 3
4. Añadir nuevas funcionalidades como comentarios y categorías para los posts
