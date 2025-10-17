<?php
require_once __DIR__ . '/DB.php';

class BaseModel {
    protected $table = '';

    /**
     * Ejecuta una consulta preparada de forma segura.
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    protected function query($sql, $params = []) {
        return DB::ejecutar($sql, $params);
    }

    /**
     * Obtiene todos los registros de la tabla.
     */
    public function findAll() {
        $sql = "SELECT * FROM " . $this->table;
        return $this->query($sql)->fetchAll();
    }

    /**
     * Obtiene un registro por ID.
     */
    public function findById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Elimina un registro por ID.
     */
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }
}
?>