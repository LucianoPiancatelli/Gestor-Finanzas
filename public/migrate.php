<?php
require_once __DIR__ . '/../../config/db.php';

try {
    $database = new Database();
    $db = $database->getConexion();
    $sql = file_get_contents(__DIR__ . '/../../database/migrations/02_add_new_features.sql');
    
    // Ejecutar sentencias SQL separadas por punto y coma (básico)
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $db->exec($statement);
        }
    }
    echo "Migración ejecutada con éxito.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
