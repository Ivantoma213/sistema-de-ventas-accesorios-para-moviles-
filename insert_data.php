<?php
// Script para insertar datos iniciales si faltan
require_once __DIR__ . '/bootstrap.php';

echo "<h1>📝 Insertando Datos Iniciales</h1>";

// Verificar si ya existen datos
$roles = DB::ejecutar("SELECT COUNT(*) as total FROM roles")->fetch()['total'];
if ($roles > 0) {
    echo "<p>⚠️ Los datos ya existen. No se insertarán duplicados.</p>";
    echo "<p><a href='/sistema/'>Ir al login</a></p>";
    exit;
}

try {
    // Insertar roles
    DB::ejecutar("INSERT INTO roles (nombre, descripcion) VALUES
        ('admin', 'Administrador del sistema'),
        ('vendedor', 'Usuario de ventas'),
        ('inventario', 'Usuario de inventario')");

    // Estados de usuario
    DB::ejecutar("INSERT INTO estados_usuario (nombre, descripcion) VALUES
        ('activo', 'Usuario activo'),
        ('inactivo', 'Usuario inactivo'),
        ('suspendido', 'Usuario suspendido')");

    // Tipos de pago
    DB::ejecutar("INSERT INTO tipos_pago (nombre, descripcion) VALUES
        ('efectivo', 'Pago en efectivo'),
        ('tarjeta', 'Pago con tarjeta'),
        ('transferencia', 'Transferencia bancaria')");

    // Cajas
    DB::ejecutar("INSERT INTO cajas (nombre, descripcion) VALUES
        ('Caja 1', 'Caja principal'),
        ('Caja 2', 'Caja secundaria')");

    // Categorías
    DB::ejecutar("INSERT INTO categorias (nombre, descripcion) VALUES
        ('Electrónicos', 'Productos electrónicos'),
        ('Ropa', 'Prendas de vestir'),
        ('Alimentos', 'Productos alimenticios')");

    // Unidades de medida
    DB::ejecutar("INSERT INTO unidades_medida (nombre, simbolo) VALUES
        ('Unidad', 'u'),
        ('Kilogramo', 'kg'),
        ('Litro', 'l')");

    // Estados de producto
    DB::ejecutar("INSERT INTO estados_producto (nombre, descripcion) VALUES
        ('activo', 'Producto disponible'),
        ('inactivo', 'Producto no disponible'),
        ('discontinuado', 'Producto discontinuado')");

    // Usuario admin (password: admin123)
    DB::ejecutar("INSERT INTO usuarios (nombre, email, password, id_rol, id_estado) VALUES
        ('Administrador', 'admin@inventory.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1)");

    echo "<p>✅ Datos iniciales insertados correctamente</p>";
    echo "<p>👤 Usuario admin creado: admin@inventory.com / admin123</p>";

} catch (Exception $e) {
    echo "<p>❌ Error al insertar datos: " . $e->getMessage() . "</p>";
}

echo "<p><a href='/sistema/debug_login.php'>Verificar datos</a> | <a href='/sistema/'>Ir al login</a></p>";
?>