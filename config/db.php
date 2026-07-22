<?php
$host   = getenv('DB_HOST') ?: $_SERVER['DB_HOST'] ?? '';
$user   = getenv('DB_USER') ?: $_SERVER['DB_USER'] ?? '';
$pass   = getenv('DB_PASS') ?: $_SERVER['DB_PASS'] ?? '';

// === MODO DIAGNÓSTICO ===
if (empty($host) || empty($user) || empty($pass)) {
    die("<h2>🚨 DIAGNÓSTICO DE VARIABLES:</h2>
         <b>Host recibido:</b> " . ($host ?: '<span style="color:red">VACÍO</span>') . "<br>
         <b>User recibido:</b> " . ($user ?: '<span style="color:red">VACÍO</span>') . "<br>
         <b>Longitud de la contraseña:</b> " . strlen((string)$pass) . " caracteres<br>
         <br><i>Si ves 'VACÍO' o '0 caracteres', significa que Render no le está pasando las variables a PHP.</i>");
}
// ========================

$port   = getenv('DB_PORT') ?: $_SERVER['DB_PORT'] ?? '10828';
$dbname = getenv('DB_NAME') ?: $_SERVER['DB_NAME'] ?? 'defaultdb';

try {
    $certPath = __DIR__ . '/ca.pem';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    if ($host !== '127.0.0.1' && file_exists($certPath)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $certPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
