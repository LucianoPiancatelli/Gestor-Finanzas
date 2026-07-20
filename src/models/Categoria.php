<?php

class Categoria {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function getByTipo($tipo) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE tipo = :tipo ORDER BY nombre ASC");
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
