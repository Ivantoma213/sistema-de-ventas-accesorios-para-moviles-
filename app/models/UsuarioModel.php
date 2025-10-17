<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/DB.php';

class UsuarioModel extends BaseModel {
    protected $table = 'usuarios';

    public function findAll() {
        $sql = "
            SELECT u.*, r.nombre as rol, e.nombre as estado
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id
            LEFT JOIN estados_usuario e ON u.id_estado = e.id
        ";
        return $this->query($sql)->fetchAll();
    }

    public function findById($id) {
        $sql = "
            SELECT u.*, r.nombre as rol, e.nombre as estado
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id
            LEFT JOIN estados_usuario e ON u.id_estado = e.id
            WHERE u.id = ?
        ";
        return $this->query($sql, [$id])->fetch();
    }

    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->query("
            INSERT INTO usuarios (nombre, email, password, id_rol, id_estado)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $data['nombre'],
            $data['email'],
            $hashedPassword,
            $data['id_rol'],
            $data['id_estado']
        ]);
        return DB::lastInsertId();
    }

    public function update($id, $data) {
        $setParts = [];
        $params = [];

        if (isset($data['nombre'])) {
            $setParts[] = "nombre = ?";
            $params[] = $data['nombre'];
        }
        if (isset($data['email'])) {
            $setParts[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $setParts[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['id_rol'])) {
            $setParts[] = "id_rol = ?";
            $params[] = $data['id_rol'];
        }
        if (isset($data['id_estado'])) {
            $setParts[] = "id_estado = ?";
            $params[] = $data['id_estado'];
        }

        if (empty($setParts)) {
            return 0; // Nothing to update
        }

        $setClause = implode(', ', $setParts);
        $params[] = $id;
        $stmt = $this->query("UPDATE usuarios SET $setClause WHERE id = ?", $params);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->query("DELETE FROM usuarios WHERE id = ?", [$id]);
        return $stmt->rowCount();
    }

    public function findUserByEmail($email) {
        $sql = "
            SELECT u.*, r.nombre as rol, e.nombre as estado
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id
            LEFT JOIN estados_usuario e ON u.id_estado = e.id
            WHERE u.email = ?
        ";
        return $this->query($sql, [$email])->fetch();
    }

    public function getRoles() {
        return $this->query("SELECT * FROM roles")->fetchAll();
    }

    public function getEstadosUsuario() {
        return $this->query("SELECT * FROM estados_usuario")->fetchAll();
    }
}
?>