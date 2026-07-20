<?php

class Subscription {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAll($usuario_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, c.nombre as cuenta_nombre, cat.nombre as categoria_nombre
            FROM subscriptions s
            JOIN cuentas c ON s.cuenta_id = c.id
            JOIN categorias cat ON s.categoria_id = cat.id
            WHERE s.usuario_id = ?
            ORDER BY s.proximo_cobro ASC
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($usuario_id, $cuenta_id, $categoria_id, $nombre, $monto, $frecuencia, $proximo_cobro) {
        $stmt = $this->db->prepare("INSERT INTO subscriptions (usuario_id, cuenta_id, categoria_id, nombre, monto, frecuencia, proximo_cobro) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$usuario_id, $cuenta_id, $categoria_id, $nombre, $monto, $frecuencia, $proximo_cobro]);
    }

    public function delete($id, $usuario_id) {
        $stmt = $this->db->prepare("DELETE FROM subscriptions WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuario_id]);
    }

    public function toggleActive($id, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE subscriptions SET activo = NOT activo WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuario_id]);
    }
}
