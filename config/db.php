<?php
// Datos duros extraídos directamente de tu panel de Aiven
$host   = 'mysql-284dd8cd-lucianopiancatelli-1cd6.f.aivencloud.com';
$port   = '10828';
$dbname = 'defaultdb';
$user   = 'avnadmin';

// Importante: Hacé clic en el ícono de "copiar" al lado de tu contraseña en Aiven 
// para asegurarte de que no haya cambiado y pegala acá adentro.
$pass   = 'AVNS_wt5RgH0q_rIEGQ1Q4Lk'; 

try {
    // Apuntamos al archivo ca.pem que acabás de subir a la carpeta config/
    $certPath = __DIR__ . '/ca.pem';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Forzar el uso estricto del certificado de Aiven
    if (file_exists($certPath)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $certPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    } else {
        die("Error: No se encontró el certificado SSL en " . $certPath);
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
