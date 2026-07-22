<?php
$host = 'mysql-284dd8cd-lucianopiancatelli-1cd6.f.aivencloud.com';
$port = 10828;
$db   = 'defaultdb';
$user = 'avnadmin';
$pass = 'AVNS_wt5RgH0q_rIEGQ1Q4Lk'; // Actualizala acá también si en el paso 1 resultó ser otra

echo "<h3>Diagnosticando conexión a Aiven...</h3>";

// 1. Prueba MySQLi (Suele forzar SSL automáticamente en la nube)
$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($mysqli, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

if (mysqli_connect_errno()) {
    echo "<p>❌ Error MySQLi: " . mysqli_connect_error() . "</p>";
} else {
    echo "<p>✅ ¡MySQLi conectó perfectamente!</p>";
    mysqli_close($mysqli);
}

// 2. Prueba PDO
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Apunta al ca.pem asumiendo que test.php está en public/ y el pem en config/
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/../config/ca.pem', 
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<p>✅ ¡PDO conectó perfectamente!</p>";
} catch (PDOException $e) {
    echo "<p>❌ Error PDO: " . $e->getMessage() . "</p>";
}
