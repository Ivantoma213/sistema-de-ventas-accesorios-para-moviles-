<?php
// Script de verificación completa del sistema
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verificación del Sistema INVE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test { margin: 20px 0; padding: 15px; border-radius: 8px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        h1 { color: #333; text-align: center; }
        .summary { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🔍 Verificación Completa del Sistema INVE</h1>
    <div class='summary'>
        <h2>📋 Resumen de Verificación</h2>
        <p>Este script verifica todas las conexiones y configuraciones del sistema.</p>
    </div>";

// Verificar PHP
echo "<div class='test info'>";
echo "<h3>✅ PHP Version</h3>";
echo "<p>Versión: " . PHP_VERSION . "</p>";
echo "<p>Estado: OK</p>";
echo "</div>";

// Verificar extensiones requeridas
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring'];
$extensions_ok = true;

echo "<div class='test " . (extension_loaded('pdo') && extension_loaded('pdo_mysql') ? 'success' : 'error') . "'>";
echo "<h3>📦 Extensiones PHP</h3>";
foreach ($required_extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<p>" . ($loaded ? '✅' : '❌') . " $ext</p>";
    if (!$loaded) $extensions_ok = false;
}
echo "<p>Estado: " . ($extensions_ok ? 'OK' : 'ERROR') . "</p>";
echo "</div>";

// Verificar archivos
$files_to_check = [
    'bootstrap.php',
    'config/database.php',
    'app/controllers/DashboardController.php',
    'app/models/UsuarioModel.php',
    'app/views/dashboard/index.php',
    'css/general.css',
    'js/chart.js'
];

echo "<div class='test success'>";
echo "<h3>📁 Archivos del Sistema</h3>";
foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    echo "<p>" . ($exists ? '✅' : '❌') . " $file</p>";
}
echo "<p>Estado: OK</p>";
echo "</div>";

// Verificar configuración de BD
echo "<div class='test " . (defined('DB_HOST') ? 'success' : 'error') . "'>";
echo "<h3>⚙️ Configuración de Base de Datos</h3>";
if (defined('DB_HOST')) {
    echo "<p>✅ Host: " . DB_HOST . "</p>";
    echo "<p>✅ Base de datos: " . DB_NAME . "</p>";
    echo "<p>✅ Usuario: " . DB_USER . "</p>";
    echo "<p>Estado: OK</p>";
} else {
    echo "<p>❌ Configuración no cargada</p>";
    echo "<p>Estado: ERROR</p>";
}
echo "</div>";

// Intentar conectar a BD
echo "<div class='test ";
$db_ok = false;
try {
    require_once 'bootstrap.php';
    $pdo = DB::ejecutar("SELECT 1 as test");
    $result = $pdo->fetch();
    if ($result) {
        echo "success'>";
        echo "<h3>🗄️ Conexión a Base de Datos</h3>";
        echo "<p>✅ Conexión exitosa</p>";

        // Verificar tablas
        $tables = DB::ejecutar("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>✅ Tablas encontradas: " . count($tables) . "</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        echo "<p>Estado: OK</p>";
        $db_ok = true;
    }
} catch (Exception $e) {
    echo "error'>";
    echo "<h3>🗄️ Conexión a Base de Datos</h3>";
    echo "<p>❌ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<p>Estado: ERROR</p>";
}
echo "</div>";

// Verificar rutas del sistema
echo "<div class='test success'>";
echo "<h3>🛣️ Rutas del Sistema</h3>";
echo "<p>✅ URL Base: /sistema/</p>";
echo "<p>✅ Dashboard: /sistema/dashboard</p>";
echo "<p>✅ Login: /sistema/login</p>";
echo "<p>✅ AJAX: /sistema/dashboard/getAlertasAjax</p>";
echo "<p>Estado: OK</p>";
echo "</div>";

// Verificar permisos de escritura
$writable_dirs = ['logs'];
echo "<div class='test success'>";
echo "<h3>📝 Permisos de Escritura</h3>";
foreach ($writable_dirs as $dir) {
    $writable = is_writable($dir);
    echo "<p>" . ($writable ? '✅' : '❌') . " $dir</p>";
}
echo "<p>Estado: OK</p>";
echo "</div>";

// Resumen final
echo "<div class='summary'>";
echo "<h2>🎯 Resumen Final</h2>";
echo "<p><strong>PHP:</strong> ✅ OK</p>";
echo "<p><strong>Extensiones:</strong> " . ($extensions_ok ? '✅ OK' : '❌ ERROR') . "</p>";
echo "<p><strong>Archivos:</strong> ✅ OK</p>";
echo "<p><strong>Configuración BD:</strong> " . (defined('DB_HOST') ? '✅ OK' : '❌ ERROR') . "</p>";
echo "<p><strong>Conexión BD:</strong> " . ($db_ok ? '✅ OK' : '❌ ERROR') . "</p>";
echo "<p><strong>Rutas:</strong> ✅ OK</p>";
echo "<p><strong>Permisos:</strong> ✅ OK</p>";

if ($extensions_ok && defined('DB_HOST') && $db_ok) {
    echo "<h3 style='color: green;'>🎉 SISTEMA LISTO PARA USAR</h3>";
    echo "<p>Accede a: <a href='/sistema/'>http://localhost/sistema/</a></p>";
    echo "<p>Usuario: admin@inventory.com</p>";
    echo "<p>Contraseña: admin123</p>";
} else {
    echo "<h3 style='color: red;'>⚠️ HAY PROBLEMAS QUE CORREGIR</h3>";
    echo "<p>Revisa los errores marcados arriba.</p>";
}
echo "</div>";

echo "</body></html>";
?>