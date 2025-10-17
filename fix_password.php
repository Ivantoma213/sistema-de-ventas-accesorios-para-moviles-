<?php
// Script para corregir el hash de contraseña del admin
require_once __DIR__ . '/bootstrap.php';

echo "<h1>🔧 Corrigiendo Contraseña Admin</h1>";

try {
    // Generar hash fresco para 'admin123'
    $correctHash = password_hash('admin123', PASSWORD_DEFAULT);

    // Actualizar contraseña
    $stmt = DB::ejecutar("UPDATE usuarios SET password = ? WHERE email = 'admin@inventory.com'", [$correctHash]);

    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Contraseña corregida exitosamente</p>";

        // Verificar que funciona
        $user = DB::ejecutar("SELECT password FROM usuarios WHERE email = 'admin@inventory.com'")->fetch();
        $test = password_verify('admin123', $user['password']);
        echo "<p>Verificación: " . ($test ? '✅ OK' : '❌ Aún falla') . "</p>";
    } else {
        echo "<p>⚠️ No se encontró el usuario admin o ya tenía la contraseña correcta</p>";
    }

} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='/sistema/debug_login.php'>Verificar</a> | <a href='/sistema/'>Login</a></p>";
?>