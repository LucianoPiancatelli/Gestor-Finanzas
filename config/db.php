<?php
// Intenta leer las variables del servidor. Si fallan o llegan vacías, usa los datos reales de Aiven.
$host   = getenv('DB_HOST') ?: $_SERVER['DB_HOST'] ?? 'mysql-284dd8cd-lucianopiancatelli-1cd6.f.aivencloud.com';
$port   = getenv('DB_PORT') ?: $_SERVER['DB_PORT'] ?? '10828';
$dbname = getenv('DB_NAME') ?: $_SERVER['DB_NAME'] ?? 'defaultdb';
$user   = getenv('DB_USER') ?: $_SERVER['DB_USER'] ?? 'avnadmin';
$pass   = getenv('DB_PASS') ?: $_SERVER['DB_PASS'] ?? 'AVNS_wt5RgH0q_rIEGQ1Q4Lk';

try {
    $certPath = __DIR__ . '/ca.pem';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Si no estamos en el entorno local (Laragon) y el certificado existe, forzamos SSL
    if ($host !== '127.0.0.1' && file_exists($certPath)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $certPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $dsn = "mysql:host=" . trim($host) . ";port=" . trim($port) . ";dbname=" . trim($dbname) . ";charset=utf8mb4";
    $pdo = new PDO($dsn, trim($user), trim($pass), $options);
    
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
