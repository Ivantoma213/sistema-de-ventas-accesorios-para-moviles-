# Sistema de Inventario y Ventas

Sistema completo de gestión de inventario y ventas desarrollado en PHP con arquitectura MVC moderna.

## 🚀 Características

- **Gestión de Inventario**: CRUD completo de productos con alertas ROP
- **Punto de Venta**: Sistema POS con gestión de stock en tiempo real
- **Control de Usuarios**: Sistema de roles (Admin, Vendedor, Inventario)
- **Reportes**: Análisis de ventas, inventario y ganancias
- **Dashboard**: Métricas en tiempo real con AJAX
- **Seguridad**: Autenticación, CSRF protection, prepared statements

## 🛠️ Tecnologías

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL/MariaDB
- **Arquitectura**: MVC con Services Layer
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Autoloading**: PSR-4 con Composer

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL/MariaDB 5.7+
- Composer (opcional, autoloading manual disponible)
- Servidor web (Apache/Nginx)

## 🏗️ Instalación

### 1. Clonar el repositorio
```bash
git clone <repository-url>
cd inventory-system
```

### 2. Configurar la base de datos
```bash
# Crear base de datos
mysql -u root -p < schema.sql

# O ejecutar manualmente en MySQL
source schema.sql
```

### 3. Configurar entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Editar configuración según tu entorno
nano .env
```

### 4. Instalar dependencias (opcional)
```bash
composer install
```

### 5. Configurar servidor web
- Apuntar el document root a la carpeta `public/`
- Asegurar que `mod_rewrite` esté habilitado (para Apache)

### 6. Acceder al sistema
- URL: `http://localhost`
- Usuario por defecto: `admin@inventory.com`
- Contraseña: `admin123`

## 📁 Estructura del Proyecto

```
inventory-system/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/         # Modelos de datos
│   ├── services/       # Lógica de negocio
│   └── views/          # Plantillas de vista
├── config/
│   └── database.php    # Configuración de BD
├── public/             # Archivos públicos
│   ├── css/
│   ├── js/
│   └── index.php       # Punto de entrada
├── logs/               # Archivos de log
├── bootstrap.php       # Inicialización
├── composer.json       # Dependencias
├── schema.sql          # Esquema de BD
├── .env                # Variables de entorno
└── README.md
```

## 🔐 Roles de Usuario

- **Administrador**: Acceso completo al sistema
- **Vendedor**: Solo punto de venta y lista de ventas
- **Inventario**: Gestión de productos y entradas

## 📊 Funcionalidades

### Gestión de Inventario
- CRUD de productos con categorías y unidades
- Alertas de reorden (ROP) automáticas
- Seguimiento de movimientos de inventario
- Gestión de proveedores y compras

### Ventas
- Punto de venta intuitivo
- Gestión automática de stock
- Múltiples tipos de pago
- Cierres de caja

### Reportes
- Ventas por período
- Productos más vendidos
- Movimientos de inventario
- Análisis de ganancias

## 🔒 Seguridad

- **Prepared Statements**: Prevención de SQL Injection
- **CSRF Protection**: Tokens en formularios críticos
- **Password Hashing**: bcrypt para contraseñas
- **Input Sanitization**: Validación y sanitización de datos
- **Session Security**: Manejo seguro de sesiones
- **Role-Based Access**: Control de acceso por roles

## 🚀 Despliegue en Producción

1. Configurar variables de entorno para producción
2. Deshabilitar debug mode
3. Configurar logs apropiados
4. Usar HTTPS
5. Configurar backups automáticos

## 📝 API Endpoints

### Autenticación
- `GET /login` - Formulario de login
- `POST /auth/login` - Procesar login
- `GET /auth/logout` - Cerrar sesión

### Dashboard
- `GET /dashboard` - Dashboard principal
- `GET /dashboard/getAlertasAjax` - Alertas AJAX

### Inventario
- `GET /inventario` - Lista de productos
- `GET /inventario/create` - Formulario crear producto
- `POST /inventario/store` - Guardar producto
- `GET /inventario/edit/{id}` - Editar producto
- `POST /inventario/update/{id}` - Actualizar producto
- `GET /inventario/delete/{id}` - Eliminar producto
- `GET /inventario/alertas` - Ver alertas ROP

### Ventas
- `GET /ventas/pos` - Punto de venta
- `POST /ventas/processSale` - Procesar venta
- `GET /ventas/lista` - Lista de ventas
- `POST /ventas/closeCash` - Cerrar caja

### Usuarios (Admin)
- `GET /usuario` - Lista de usuarios
- `GET /usuario/create` - Crear usuario
- `POST /usuario/store` - Guardar usuario
- `GET /usuario/edit/{id}` - Editar usuario
- `POST /usuario/update/{id}` - Actualizar usuario

## 🤝 Contribución

1. Fork el proyecto
2. Crear rama para feature (`git checkout -b feature/AmazingFeature`)
3. Commit cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 📞 Soporte

Para soporte técnico o preguntas:
- Crear issue en GitHub
- Email: support@inventory-system.com

---

**Desarrollado con ❤️ para gestión eficiente de inventarios**