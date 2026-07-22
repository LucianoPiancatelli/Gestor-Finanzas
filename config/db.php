<?php
$host   = $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
$port   = $_SERVER['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
$dbname = $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: 'gestor_finanzas';
$user   = $_SERVER['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$pass   = $_SERVER['DB_PASS'] ?? getenv('DB_PASS') ?: '';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Si estamos en producción (no es localhost), forzamos SSL usando el archivo descargado
    if ($host !== '127.0.0.1') {
        // __DIR__ apunta a la carpeta "config/", donde guardamos ca.pem
        $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . '/ca.pem';
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $dsn = "mysql:host=" . trim($host) . ";port=" . trim($port) . ";dbname=" . trim($dbname) . ";charset=utf8mb4";
    $pdo = new PDO($dsn, trim($user), trim($pass), $options);
    
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
