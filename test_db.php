<?php
// Test de conexión a la base de datos
require_once __DIR__ . '/bootstrap.php';

try {
    // Intentar conectar
    $pdo = DB::ejecutar("SELECT 1 as test");
    echo "<h2>✅ Conexión exitosa a la base de datos!</h2>";

    // Verificar si las tablas existen
    $tables = DB::ejecutar("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tablas encontradas (" . count($tables) . "):</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";

    // Verificar datos iniciales
    $usuarios = DB::ejecutar("SELECT COUNT(*) as total FROM usuarios")->fetch()['total'];
    $productos = DB::ejecutar("SELECT COUNT(*) as total FROM productos")->fetch()['total'];

    echo "<h3>Datos iniciales:</h3>";
    echo "<p>Usuarios: $usuarios</p>";
    echo "<p>Productos: $productos</p>";

    echo "<p><a href='index.php'>Ir al sistema</a></p>";

} catch (Exception $e) {
    echo "<h2>❌ Error de conexión:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Verifica:</p>";
    echo "<ul>";
    echo "<li>Que la base de datos 'db_inventory' existe</li>";
    echo "<li>Que MySQL esté corriendo en Laragon</li>";
    echo "<li>Que las credenciales en .env sean correctas</li>";
    echo "</ul>";
}
?>