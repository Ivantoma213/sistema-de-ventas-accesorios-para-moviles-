<?php
require_once __DIR__ . '/../../config/database.php';

class DB {
    private static $connection = null;

    public static function conectar() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                self::$connection = new PDO($dsn, DB_USER, DB_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                // Registrar el error y mostrar mensaje genérico
                error_log("Error de conexión a la base de datos: " . $e->getMessage());
                die("Error crítico del sistema. Contacte al administrador.");
            }
        }
        return self::$connection;
    }

    /**
     * Ejecuta una consulta preparada de forma segura.
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public static function ejecutar($sql, $params = []) {
        $stmt = self::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Inicia una transacción.
     */
    public static function beginTransaction() {
        return self::conectar()->beginTransaction();
    }

    /**
     * Confirma todos los cambios en la transacción.
     */
    public static function commit() {
        return self::conectar()->commit();
    }

    /**
     * Revierte los cambios en caso de error.
     */
    public static function rollBack() {
        return self::conectar()->rollBack();
    }

    /**
     * Obtiene el último ID insertado.
     */
    public static function lastInsertId() {
        return self::conectar()->lastInsertId();
    }
}
?>