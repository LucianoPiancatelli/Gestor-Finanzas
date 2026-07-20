<?php

class Goal {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAll($usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM savings_goals WHERE usuario_id = ? ORDER BY fecha_limite ASC");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM savings_goals WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($usuario_id, $nombre, $monto_objetivo, $fecha_limite) {
        $stmt = $this->db->prepare("INSERT INTO savings_goals (usuario_id, nombre, monto_objetivo, fecha_limite) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$usuario_id, $nombre, $monto_objetivo, empty($fecha_limite) ? null : $fecha_limite]);
    }

    public function addFunds($id, $usuario_id, $monto) {
        $stmt = $this->db->prepare("UPDATE savings_goals SET monto_actual = monto_actual + ? WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$monto, $id, $usuario_id]);
    }

    public function delete($id, $usuario_id) {
        $stmt = $this->db->prepare("DELETE FROM savings_goals WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuario_id]);
    }
}
