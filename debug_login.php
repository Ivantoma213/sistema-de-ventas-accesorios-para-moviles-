<?php
// Debug script para verificar login
require_once __DIR__ . '/bootstrap.php';

echo "<h1>🔍 Debug Login</h1>";

// Verificar conexión a BD
try {
    $pdo = DB::ejecutar("SELECT 1");
    echo "<p>✅ Conexión a BD: OK</p>";
} catch (Exception $e) {
    echo "<p>❌ Error BD: " . $e->getMessage() . "</p>";
    exit;
}

// Verificar usuarios en BD
$usuarios = DB::ejecutar("SELECT id, nombre, email, id_estado FROM usuarios")->fetchAll();
echo "<h2>👥 Usuarios en BD:</h2>";
if (empty($usuarios)) {
    echo "<p>❌ No hay usuarios en la base de datos</p>";
    echo "<p>💡 Ejecuta el schema.sql para insertar datos iniciales</p>";
} else {
    echo "<ul>";
    foreach ($usuarios as $user) {
        $estado = $user['id_estado'] == 1 ? 'Activo' : 'Inactivo';
        echo "<li>ID: {$user['id']}, Nombre: {$user['nombre']}, Email: {$user['email']}, Estado: $estado</li>";
    }
    echo "</ul>";
}

// Verificar hash de contraseña
$admin = DB::ejecutar("SELECT password FROM usuarios WHERE email = 'admin@inventory.com'")->fetch();
if ($admin) {
    echo "<h2>🔐 Hash de admin:</h2>";
    echo "<p>" . $admin['password'] . "</p>";
    echo "<p>Debería corresponder a 'admin123'</p>";

    // Probar verificación
    $test = password_verify('admin123', $admin['password']);
    echo "<p>Verificación 'admin123': " . ($test ? '✅ OK' : '❌ FALLÓ') . "</p>";
} else {
    echo "<p>❌ Usuario admin no encontrado</p>";
}

echo "<hr>";
echo "<p><a href='/sistema/'>Volver al login</a></p>";
?>