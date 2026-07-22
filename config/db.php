<?php
// Función robusta para cazar las variables de entorno en contenedores Docker/Apache
function getEnvVar($key, $default = '') {
    $val = getenv($key);
    if ($val === false || $val === '') {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
    return trim($val);
}

$host   = getEnvVar('DB_HOST', '127.0.0.1');
$port   = getEnvVar('DB_PORT', '3306');
$dbname = getEnvVar('DB_NAME', 'gestor_finanzas');
$user   = getEnvVar('DB_USER', 'root');
$pass   = getEnvVar('DB_PASS', '');

try {
    $certPath = __DIR__ . '/ca.pem';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Si no es un entorno local y el certificado existe, activamos SSL para Aiven
    if ($host !== '127.0.0.1' && file_exists($certPath)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $certPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
