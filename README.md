# 🧩 Tenant Backend API (Laravel 11)

> Backend desarrollado en **Laravel 11** con autenticación vía Sanctum, gestión de clientes y órdenes, y arquitectura limpia (Repository + Service + Resource).  
> Este proyecto está preparado para ejecutarse fácilmente con **Docker + MySQL + Redis**, e incluye entorno de pruebas y seeds iniciales.

---

## 🚀 Características principales

- Laravel 11 (PHP 8.2 + Composer 2)
- API RESTful con rutas `/api/*`
- Autenticación vía Sanctum (token Bearer)
- Gestión de **Clientes**, **Usuarios** y **Órdenes**
- Capas desacopladas: Repository / Service / Resource
- Control de acceso multicliente (Tenant scope)
- Manejo global de excepciones JSON (401, 403, 404, 422)
- Seeds de datos iniciales
- Entorno **Docker** listo (MySQL 8 + Redis 7 + Nginx)
- Pruebas unitarias y funcionales (`php artisan test`)

---

## 🐳 Instalación con Docker (recomendada)

### 1. Clonar el repositorio
```bash
git clone https://github.com/mosiahazuaje2019/tenant.git
cd tenant

### 2. Preparar variables de entorno
```bash
cp .env.docker .env

Puedes ajustar el nombre de la base de datos o credenciales si lo deseas:
DB_DATABASE=tenant
DB_USERNAME=tenant
DB_PASSWORD=tenant

### 3. Levantar los servicios
```bash
docker compose up -d --build

Esto iniciará los siguientes contenedores:

| Servicio    | Descripción        | Puerto       |
| ----------- | ------------------ | ------------ |
| `app`       | PHP-FPM + Laravel  | interno      |
| `web`       | Nginx + Laravel    | 8000         |
| `db`        | MySQL 8            | 33060 (host) |
| `redis`     | Redis 7            | 6379         |
| `queue`     | Worker de colas    | —            |
| `scheduler` | Cron job scheduler | —            |


### 4. Instalar dependencias y migrar la base de datos
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan optimize:clear

## 5. 👤 Usuario de prueba
| Campo        | Valor                |
| ------------ | -------------------- |
| **Email**    | `admin@system.local` |
| **Password** | `secret123`          |


## 6. 🔐 Autenticación (Bearer Token)
```bash
POST /api/auth/token
Body JSON
{
  "email": "admin@system.local",
  "password": "secret123"
}

Respuesta esperada
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "type": "Bearer"
}

## 7 🔎 Endpoints principales
| Método   | Ruta                       | Descripción                                         | Auth |
| -------- | -------------------------- | --------------------------------------------------- | ---- |
| **POST** | `/api/auth/token`          | Inicia sesión y genera un token Bearer              | ❌    |
| **POST** | `/api/auth/logout`         | Revoca el token activo (logout)                     | ✅    |
| **GET**  | `/api/orders`              | Lista todas las órdenes del usuario autenticado     | ✅    |
| **POST** | `/api/orders`              | Crea una nueva orden                                | ✅    |
| **GET**  | `/api/orders/{id}`         | Muestra los detalles de una orden específica        | ✅    |
| **GET**  | `/api/clients/{id}/orders` | Lista las órdenes asociadas a un cliente específico | ✅    |
| **POST** | `/api/clients`             | Crea un nuevo cliente                               | ✅    |
| **POST** | `/api/users`               | Crea un nuevo usuario dentro del tenant actual      | ✅    |
