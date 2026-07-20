<?php

class Investment {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAll($usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM investments WHERE usuario_id = ? ORDER BY creado_en DESC");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($usuario_id, $nombre, $tipo, $monto_invertido, $fecha_inicio) {
        // Por defecto, valor_actual es igual al monto_invertido al inicio
        $stmt = $this->db->prepare("INSERT INTO investments (usuario_id, nombre, tipo, monto_invertido, valor_actual, fecha_inicio) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$usuario_id, $nombre, $tipo, $monto_invertido, $monto_invertido, $fecha_inicio]);
    }

    public function updateValue($id, $usuario_id, $nuevo_valor) {
        $stmt = $this->db->prepare("UPDATE investments SET valor_actual = ? WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$nuevo_valor, $id, $usuario_id]);
    }

    public function delete($id, $usuario_id) {
        $stmt = $this->db->prepare("DELETE FROM investments WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuario_id]);
    }
}
