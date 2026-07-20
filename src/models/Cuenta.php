<?php

class Cuenta {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAllByUser($usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM cuentas WHERE usuario_id = :usuario_id ORDER BY nombre ASC");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id, $usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM cuentas WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($usuario_id, $nombre, $tipo, $saldo_inicial = 0) {
        $stmt = $this->db->prepare("INSERT INTO cuentas (usuario_id, nombre, tipo, saldo) VALUES (:usuario_id, :nombre, :tipo, :saldo)");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':saldo', $saldo_inicial);
        return $stmt->execute();
    }

    public function update($id, $usuario_id, $nombre, $tipo) {
        $stmt = $this->db->prepare("UPDATE cuentas SET nombre = :nombre, tipo = :tipo WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function delete($id, $usuario_id) {
        // Al eliminar, las transacciones se eliminan por CASCADE según la DB,
        // esto podría ser destructivo. En un sistema real se archivaría, pero
        // para este proyecto usamos CASCADE.
        $stmt = $this->db->prepare("DELETE FROM cuentas WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }
}
