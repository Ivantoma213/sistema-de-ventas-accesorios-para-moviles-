<?php
// Generar hash correcto para admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h1>🔐 Generar Hash para admin123</h1>";
echo "<p>Contraseña: $password</p>";
echo "<p>Hash generado: $hash</p>";

// Verificar que funciona
$verify = password_verify($password, $hash);
echo "<p>Verificación: " . ($verify ? '✅ OK' : '❌ FALLÓ') . "</p>";

echo "<hr>";
echo "<p>Copia este hash y actualiza la BD:</p>";
echo "<code>UPDATE usuarios SET password = '$hash' WHERE email = 'admin@inventory.com';</code>";
?>