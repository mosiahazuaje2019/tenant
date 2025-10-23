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
git clone https://github.com/<tu-usuario>/tenant-api.git
cd tenant-api
