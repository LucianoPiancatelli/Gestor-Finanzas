<?php
// config/db.php

class Database {
    private ?PDO $conexion = null;

    public function getConexion(): PDO {
        if ($this->conexion !== null) {
            return $this->conexion;
        }

        $configPath = __DIR__ . '/config.ini';
        
        if (!file_exists($configPath)) {
            throw new Exception("Archivo de configuración crítico no encontrado.");
        }

        $config = parse_ini_file($configPath);

        if ($config === false) {
            throw new Exception("Error al leer el archivo de configuración.");
        }

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

        try {
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->conexion = new PDO($dsn, $config['user'], $config['password'], $opciones);
            return $this->conexion;

        } catch (PDOException $e) {
            // error_log($e->getMessage()); // En producción
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
