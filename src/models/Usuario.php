<?php

class Usuario {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function registrar($nombre, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :password)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // Posiblemente el email ya existe
        }
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            return $usuario;
        }
        return false;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, creado_en FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, nombre, email FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function update2FA($id, $secret, $enabled) {
        $stmt = $this->db->prepare("UPDATE usuarios SET two_factor_secret = :secret, two_factor_enabled = :enabled WHERE id = :id");
        $stmt->bindParam(':secret', $secret);
        // Bind booleans as INT for PDO or direct value
        $enabled_val = $enabled ? 1 : 0;
        $stmt->bindParam(':enabled', $enabled_val, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
