<?php
// Script de configuración inicial
echo "<h1>🚀 Configuración Inicial - Sistema de Inventario</h1>";

// Verificar PHP
echo "<h2>✅ PHP Version: " . PHP_VERSION . "</h2>";

// Verificar extensiones requeridas
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring'];
echo "<h2>📦 Extensiones PHP:</h2>";
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

// Verificar archivos de configuración
echo "<h2>⚙️ Archivos de Configuración:</h2>";
$files = ['.env', 'config/database.php', 'bootstrap.php', 'public/index.php'];
foreach ($files as $file) {
    $status = file_exists($file) ? '✅' : '❌';
    echo "<p>$status $file</p>";
}

// Verificar permisos de escritura
echo "<h2>📝 Permisos de Escritura:</h2>";
$writable_dirs = ['logs'];
foreach ($writable_dirs as $dir) {
    $status = is_writable($dir) ? '✅' : '❌';
    echo "<p>$status $dir</p>";
}

// Intentar cargar configuración
echo "<h2>🔧 Probando Configuración:</h2>";
try {
    require_once 'config/database.php';
    echo "<p>✅ Configuración de BD cargada</p>";
    echo "<p>📊 Host: " . DB_HOST . "</p>";
    echo "<p>🗄️ Base de datos: " . DB_NAME . "</p>";
    echo "<p>👤 Usuario: " . DB_USER . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Error en configuración: " . $e->getMessage() . "</p>";
}

// Instrucciones finales
echo "<h2>📋 Próximos Pasos:</h2>";
echo "<ol>";
echo "<li>Asegúrate que todas las verificaciones estén en ✅</li>";
echo "<li>Crea la base de datos ejecutando <code>schema.sql</code> en MySQL</li>";
echo "<li>Configura el DocumentRoot de Apache apuntando a la carpeta <code>public/</code></li>";
echo "<li>Accede a <code>http://localhost/test_db.php</code> para probar la conexión</li>";
echo "<li>Una vez funcionando, ve a <code>http://localhost/</code> para usar el sistema</li>";
echo "</ol>";

echo "<p><strong>Usuario por defecto:</strong> admin@inventory.com</p>";
echo "<p><strong>Contraseña:</strong> admin123</p>";
?>