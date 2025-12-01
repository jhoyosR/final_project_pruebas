<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Gestión de Inventario - Sistema Completo

Sistema de gestión de inventario de productos desarrollado con Laravel 10, que incluye una API REST con arquitectura por capas, interfaz web, base de datos relacional, suite completa de pruebas automatizadas y pipeline de integración continua.

## 📋 Tabla de Contenidos

- [Descripción del Proyecto](#descripción-del-proyecto)
- [Arquitectura](#arquitectura)
- [Base de Datos](#base-de-datos)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Requisitos Previos](#requisitos-previos)
- [Instalación y Configuración](#instalación-y-configuración)
- [Ejecución del Proyecto](#ejecución-del-proyecto)
- [Pruebas Automatizadas](#pruebas-automatizadas)
- [Pipeline CI/CD](#pipeline-cicd)
- [Decisiones Técnicas](#decisiones-técnicas)
- [Estructura del Proyecto](#estructura-del-proyecto)

---

## Descripción del Proyecto

Este sistema permite la gestión completa de un inventario de productos organizados por categorías. Implementa operaciones CRUD (Crear, Leer, Actualizar, Eliminar) tanto para productos como para categorías, a través de una API REST y una interfaz web construida con Blade.

### Características principales:

- **API REST** con arquitectura por capas (Controller → Service → Repository)
- **Interfaz web** con vistas Blade para gestión de categorías y productos
- **Base de datos** MySQL con relaciones entre tablas
- **Pruebas automatizadas** (unitarias, integración y E2E)
- **Análisis estático** de código con PHPStan
- **CI/CD** con GitHub Actions
- **Dockerizado** con Laravel Sail

---

## Arquitectura

El proyecto sigue una arquitectura de capas que separa responsabilidades:

```
┌─────────────────────────────────────────┐
│         Interfaz Web (Blade)            │
│              Frontend                   │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│           Controllers                   │
│   (Reciben requests HTTP y validan)    │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│            Services                     │
│      (Lógica de negocio)               │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│          Repositories                   │
│   (Acceso a datos y persistencia)      │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│        Base de Datos MySQL              │
└─────────────────────────────────────────┘
```

### Capas:

1. **Controllers**: Manejan las peticiones HTTP, validan datos de entrada y devuelven respuestas
2. **Services**: Contienen la lógica de negocio del sistema
3. **Repositories**: Abstraen el acceso a la base de datos mediante Eloquent ORM
4. **Models**: Representan las entidades del sistema (Product, Category)

---

## Base de Datos

El sistema utiliza MySQL con las siguientes tablas:

### Tabla `categories`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | BIGINT (PK) | Identificador único |
| `name` | VARCHAR(255) | Nombre de la categoría |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de actualización |

### Tabla `products`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | BIGINT (PK) | Identificador único |
| `name` | VARCHAR(255) | Nombre del producto |
| `description` | TEXT | Descripción del producto |
| `price` | BIGINT | Precio del producto |
| `stock` | BIGINT | Cantidad en inventario |
| `category_id` | BIGINT (FK) | Referencia a categoría |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de actualización |

**Relación**: Un producto pertenece a una categoría. Una categoría puede tener múltiples productos (1:N).

---

## Tecnologías Utilizadas

### Backend
- **PHP 8.3**
- **Laravel 10** - Framework PHP
- **Laravel Sail** - Entorno Docker para desarrollo
- **MySQL 8.0** - Base de datos relacional

### Frontend
- **Blade** - Motor de plantillas de Laravel
- **HTML/CSS/JavaScript** - Interfaz de usuario

### Testing
- **PHPUnit** - Pruebas unitarias e integración
- **Playwright** - Pruebas End-to-End
- **PHPStan** - Análisis estático de código

### DevOps
- **Docker & Docker Compose** - Contenedorización
- **GitHub Actions** - CI/CD pipeline

---

## Requisitos Previos

### Opción 1: Con Docker (Recomendado)
- Docker Desktop instalado
- Docker Compose
- Git

### Opción 2: Sin Docker
- PHP 8.3 o superior
- Composer
- MySQL 8.0 o superior
- Node.js 20 o superior
- NPM
- Git

---

## Instalación y Configuración

### Con Docker (Laravel Sail)

1. **Clonar el repositorio**
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DEL_PROYECTO>

2. **Instalar dependencias de PHP**
composer install

3. **Configurar variables de entorno**
cp .env.example .env

4. **Levantar los servicios con Sail**
./vendor/bin/sail up -d

5. **Generar la clave de aplicación**
./vendor/bin/sail artisan key:generate

6. **Ejecutar migraciones**
./vendor/bin/sail artisan migrate

7. **Instalar dependencias de Node.js**
./vendor/bin/sail npm install

8. **Acceder a la aplicación**
- API: `http://localhost/api`
- Interfaz Web: `http://localhost`

### Sin Docker

1. **Clonar el repositorio**
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DEL_PROYECTO>

2. **Instalar dependencias**
composer install
npm install

3. **Configurar variables de entorno**
cp .env.example .env

Editar `.env` con tus credenciales de MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

4. **Generar clave y migrar**
php artisan key:generate
php artisan migrate

5. **Iniciar el servidor**
php artisan serve

---

## Ejecución del Proyecto

### Con Docker

# Iniciar servicios
./vendor/bin/sail up -d

# Ver logs
./vendor/bin/sail logs -f

# Detener servicios
./vendor/bin/sail down

### Sin Docker

# Iniciar servidor de desarrollo
php artisan serve

# La aplicación estará disponible en http://localhost:8000

### Endpoints de la API

#### Categorías
- `GET /api/categories` - Listar todas las categorías
- `GET /api/categories/{id}` - Obtener una categoría
- `POST /api/categories` - Crear una categoría
- `PUT /api/categories/{id}` - Actualizar una categoría
- `DELETE /api/categories/{id}` - Eliminar una categoría

#### Productos
- `GET /api/products` - Listar todos los productos
- `GET /api/products/{id}` - Obtener un producto
- `POST /api/products` - Crear un producto
- `PUT /api/products/{id}` - Actualizar un producto
- `DELETE /api/products/{id}` - Eliminar un producto

### Vistas Web
- `/views/categories` - Gestión de categorías
- `/views/products` - Gestión de productos

---

## Pruebas Automatizadas

### Tipos de Pruebas Implementadas

#### 1. Pruebas Unitarias
Prueban la lógica de negocio en los **Services** de forma aislada.

# Con Docker
./vendor/bin/sail artisan test --testsuite=Unit

# Sin Docker
php artisan test --testsuite=Unit

#### 2. Pruebas de Integración
Validan el comportamiento de los **endpoints de la API** y su interacción con la base de datos.

# Con Docker
./vendor/bin/sail artisan test --testsuite=Feature

# Sin Docker
php artisan test --testsuite=Feature

#### 3. Pruebas E2E (End-to-End)
Automatizan el flujo completo del usuario usando **Playwright**:
- Crear categoría
- Ver categoría
- Crear producto
- Ver producto
- Eliminar producto
- Eliminar categoría

# Instalar Playwright
npm install
npx playwright install --with-deps

# Ejecutar pruebas E2E
npx playwright test

# Ver reporte
npx playwright show-report

#### 4. Análisis Estático de Código
Ejecuta **PHPStan** para detectar errores potenciales sin ejecutar el código.

# Con Docker
docker compose exec -u www-data laravel.test vendor/bin/phpstan analyse --memory-limit=1G

# Sin Docker
vendor/bin/phpstan analyse --memory-limit=1G

### Ejecutar Todas las Pruebas

# Con Docker
./vendor/bin/sail artisan test
./vendor/bin/sail composer phpstan
./vendor/bin/sail npx playwright test

# Sin Docker
php artisan test
vendor/bin/phpstan analyse
npx playwright test

---

## Pipeline CI/CD

El proyecto incluye un pipeline de GitHub Actions que se ejecuta automáticamente en cada push o pull request a la rama `main`.

### Etapas del Pipeline

1. **Checkout**: Clona el repositorio
2. **Instalación de dependencias**: Instala paquetes de Composer
3. **Configuración del entorno**: Crea y configura el archivo `.env`
4. **Inicio de servicios Sail**: Levanta Docker con MySQL
5. **Preparación de la base de datos**: Genera clave y ejecuta migraciones
6. **Análisis estático (PHPStan)**: Valida calidad del código
7. **Pruebas unitarias**: Ejecuta test suite Unit
8. **Pruebas de integración**: Ejecuta test suite Feature
9. **Configuración de Node.js**: Instala Node 20
10. **Instalación de dependencias frontend**: npm install
11. **Instalación de Playwright**: Instala navegadores
12. **Inicio del servidor Laravel**: Para pruebas E2E
13. **Pruebas E2E con Playwright**: Ejecuta tests de interfaz
14. **Limpieza**: Detiene servicios Sail
15. **Resultado**: Imprime "OK!" si todo pasa, "Pipeline Failed!" si algo falla

### Archivo de configuración

El pipeline está definido en `.github/workflows/ci.yml`

### Ver resultados

Los resultados del pipeline se pueden ver en la pestaña "Actions" del repositorio de GitHub.

---

## Decisiones Técnicas

### 1. Laravel Sail para Docker
Se eligió Laravel Sail por su facilidad de configuración y compatibilidad nativa con Laravel. Permite tener un entorno de desarrollo reproducible sin necesidad de instalar PHP, MySQL o Composer localmente.

### 2. Arquitectura por Capas
La separación en Controller → Service → Repository permite:
- **Mantenibilidad**: Cada capa tiene una responsabilidad clara
- **Testabilidad**: Los services se pueden probar unitariamente
- **Escalabilidad**: Facilita agregar nuevas funcionalidades
- **Reutilización**: Los services pueden ser usados desde múltiples controllers

### 3. Blade para la Interfaz Web
Se optó por Blade en lugar de un SPA (React/Vue) para:
- Simplificar la arquitectura del proyecto
- Aprovechar las capacidades nativas de Laravel
- Reducir la complejidad de configuración y despliegue
- Mantener el frontend y backend en el mismo proyecto

### 4. Playwright para E2E
Playwright fue seleccionado por:
- Soporte multi-navegador (Chromium, Firefox, WebKit)
- API moderna y fácil de usar
- Ejecución rápida y estable
- Excelente documentación

### 5. PHPStan para Análisis Estático
PHPStan ayuda a detectar errores antes de ejecutar el código:
- Type checking estricto
- Detección de código muerto
- Validación de DocBlocks
- Integración nativa con Laravel

### 6. GitHub Actions para CI/CD
Automatiza la verificación de calidad del código:
- Ejecuta todas las pruebas automáticamente
- Previene merges con código defectuoso
- Gratuito para repositorios públicos
- Fácil integración con GitHub

---

## Estructura del Proyecto

```
.
├── app/
│   ├── Http/
│   │   └── Controllers/     # Controladores de API y Web
│   ├── Models/              # Modelos Eloquent (Product, Category)
│   ├── Repositories/        # Capa de acceso a datos
│   └── Services/            # Lógica de negocio
├── database/
│   └── migrations/          # Migraciones de base de datos
├── resources/
│   └── views/               # Vistas Blade
├── routes/
│   ├── api.php              # Rutas de la API
│   └── web.php              # Rutas web
├── tests/
│   ├── Unit/                # Pruebas unitarias
│   ├── Feature/             # Pruebas de integración
│   └── playwright/          # Pruebas Playwright
├── .github/
│   └── workflows/           # Configuración GitHub Actions
├── docker-compose.yml       # Configuración Docker Sail
├── phpstan.neon             # Configuración PHPStan
├── playwright.config.js     # Configuración Playwright
└── README.md
```

---

## Restricciones y Aclaraciones

### Lenguaje y Framework
- **Lenguaje**: PHP 8.3
- **Framework**: Laravel 10
- **Justificación**: Laravel proporciona un ecosistema robusto con ORM (Eloquent), sistema de migraciones, testing integrado y arquitectura MVC que facilita el desarrollo de APIs REST.

### Base de Datos
- **Motor**: MySQL 8.0
- **Gestión**: A través de Laravel Migrations
- **Acceso**: Eloquent ORM para abstracción de datos

### Docker
- **Uso**: Implementado con Laravel Sail
- **Ventajas**: Entorno consistente, fácil configuración, incluye MySQL, Redis y otros servicios preconfigurados
- **Opcional**: El proyecto puede ejecutarse sin Docker siguiendo las instrucciones de instalación manual

### Interfaz Gráfica
- **Tecnología**: Blade Templates (Laravel)
- **Evaluación**: La funcionalidad tiene prioridad sobre el diseño visual
- **Funcionalidades implementadas**:
  - CRUD completo de categorías
  - CRUD completo de productos
  - Validación de formularios
  - Mensajes de confirmación

### Pruebas
- **Framework de testing**: PHPUnit (unitarias e integración)
- **E2E**: Playwright con JavaScript/TypeScript
- **Cobertura mínima**: Todos los Services, todos los endpoints API, flujo completo de usuario
- **Plan de pruebas**: Documentado en archivo separado

### Pipeline CI/CD
- **Plataforma**: GitHub Actions
- **Criterio de éxito**: Todas las etapas deben pasar para considerar el build exitoso
- **Salida**: "OK!" en caso de éxito, "Pipeline Failed!" en caso de fallo
- **Ejecución**: Automática en push y pull requests a main

### Desarrollo Incremental
- El proyecto se desarrolló de forma iterativa con commits regulares
- Historial completo disponible en el repositorio
- Branches para features específicas con merge a main

### Consideraciones de Seguridad
- Validación de inputs en todos los endpoints
- Uso de prepared statements a través de Eloquent (prevención SQL Injection)
- CSRF protection habilitado en formularios web
- Variables de entorno para credenciales sensibles

---

## Autor

Proyecto desarrollado como parte del curso de Ingeniería de Software - Juan Esteban Hoyos.

## Licencia

Este proyecto es de uso educativo.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
