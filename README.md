# Sistema de Ventas con Microservicios

Taller de Software II — Arquitectura basada en microservicios usando Laravel, Flask y Express.

## Requisitos previos

- PHP 8.5+ y Composer
- Python 3.12+ 
- Node.js 22+
- MySQL (Laragon)

## Estructura del proyecto
```
SistemaVentas/
├── ApiGateway/              ← Laravel (puerto 8000)
├── MicroservicioProductos/  ← Flask + Firebase (puerto 5000)
└── MicroservicioVentas/     ← Express + MongoDB (puerto 3000)
```

## Instalación

### 1. API Gateway
```bash
cd ApiGateway
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve --port=8000
```

### 2. Microservicio Productos
```bash
cd MicroservicioProductos
python -m venv venv
source venv/Scripts/activate  # Windows
pip install -r requirements.txt
python app.py
```

### 3. Microservicio Ventas
```bash
cd MicroservicioVentas
npm install
node index.js
```

## Variables de entorno

Cada servicio necesita su archivo `.env`. Revisar los `.env.example` de cada carpeta.

## Endpoints principales

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | /api/login | Iniciar sesión |
| POST | /api/ventas | Registrar venta |
| GET | /api/ventas | Consultar ventas |
| GET | /productos/ | Listar productos |
