<?php
// Datos quemados SOLO para probar y descartar problemas con Render
$host   = 'mysql-284dd8cd-lucianopiancatelli-1cd6.f.aivencloud.com';
$port   = '10828';
$dbname = 'defaultdb';
$user   = 'avnadmin';
$pass   = 'AVNS_wt5RgH0q_rIEGQ1Q4Lk';

try {
    $certPath = __DIR__ . '/ca.pem';
    
    // 1. Verificamos si el certificado realmente llegó al servidor de Render
    if (!file_exists($certPath)) {
        die("🛑 ERROR DETECTADO: El archivo ca.pem NO se subió a Render. Tu .gitignore lo bloqueó.");
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA => $certPath,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("❌ Error de la BD: " . $e->getMessage());
}
