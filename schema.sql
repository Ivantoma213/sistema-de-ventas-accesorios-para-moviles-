-- Esquema de Base de Datos para Sistema de Inventario y Ventas
-- Ejecutar en MySQL/MariaDB

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS db_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_inventory;

-- Tabla de roles
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de estados de usuario
CREATE TABLE estados_usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    id_estado INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES roles(id),
    FOREIGN KEY (id_estado) REFERENCES estados_usuario(id)
);

-- Tabla de categorías
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de unidades de medida
CREATE TABLE unidades_medida (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    simbolo VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de estados de producto
CREATE TABLE estados_producto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT DEFAULT 0,
    tiempo_entrega INT DEFAULT 7, -- días
    id_categoria INT,
    id_unidad_medida INT,
    id_estado INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    FOREIGN KEY (id_unidad_medida) REFERENCES unidades_medida(id),
    FOREIGN KEY (id_estado) REFERENCES estados_producto(id)
);

-- Tabla de proveedores
CREATE TABLE proveedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de compras (entradas de inventario)
CREATE TABLE compras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    id_proveedor INT,
    id_usuario INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Tabla de detalle de compras
CREATE TABLE detalle_compra (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_compra INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_compra) REFERENCES compras(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Tabla de tipos de pago
CREATE TABLE tipos_pago (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de cajas
CREATE TABLE cajas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de ventas
CREATE TABLE ventas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    id_caja INT,
    id_tipo_pago INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_caja) REFERENCES cajas(id),
    FOREIGN KEY (id_tipo_pago) REFERENCES tipos_pago(id)
);

-- Tabla de detalle de ventas
CREATE TABLE detalle_venta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Tabla de movimientos de inventario
CREATE TABLE movimientos_inventario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha DATE NOT NULL,
    id_usuario INT NOT NULL,
    motivo VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Tabla de promociones
CREATE TABLE promociones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    descuento_porcentaje DECIMAL(5,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Tabla de cierres de caja
CREATE TABLE cierres_caja (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_caja INT NOT NULL,
    fecha DATE NOT NULL,
    total_esperado DECIMAL(10,2) NOT NULL,
    total_real DECIMAL(10,2) NOT NULL,
    diferencia DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_caja) REFERENCES cajas(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Índices para optimización
CREATE INDEX idx_productos_categoria ON productos(id_categoria);
CREATE INDEX idx_productos_estado ON productos(id_estado);
CREATE INDEX idx_ventas_fecha ON ventas(fecha);
CREATE INDEX idx_ventas_usuario ON ventas(id_usuario);
CREATE INDEX idx_movimientos_fecha ON movimientos_inventario(fecha);
CREATE INDEX idx_movimientos_producto ON movimientos_inventario(id_producto);

-- Datos iniciales
INSERT INTO roles (nombre, descripcion) VALUES
('admin', 'Administrador del sistema'),
('vendedor', 'Usuario de ventas'),
('inventario', 'Usuario de inventario');

INSERT INTO estados_usuario (nombre, descripcion) VALUES
('activo', 'Usuario activo'),
('inactivo', 'Usuario inactivo'),
('suspendido', 'Usuario suspendido');

INSERT INTO tipos_pago (nombre, descripcion) VALUES
('efectivo', 'Pago en efectivo'),
('tarjeta', 'Pago con tarjeta'),
('transferencia', 'Transferencia bancaria');

INSERT INTO cajas (nombre, descripcion) VALUES
('Caja 1', 'Caja principal'),
('Caja 2', 'Caja secundaria');

INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónicos', 'Productos electrónicos'),
('Ropa', 'Prendas de vestir'),
('Alimentos', 'Productos alimenticios');

INSERT INTO unidades_medida (nombre, simbolo) VALUES
('Unidad', 'u'),
('Kilogramo', 'kg'),
('Litro', 'l');

INSERT INTO estados_producto (nombre, descripcion) VALUES
('activo', 'Producto disponible'),
('inactivo', 'Producto no disponible'),
('discontinuado', 'Producto discontinuado');

-- Usuario administrador por defecto (contraseña: admin123)
INSERT INTO usuarios (nombre, email, password, id_rol, id_estado) VALUES
('Administrador', 'admin@inventory.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1);