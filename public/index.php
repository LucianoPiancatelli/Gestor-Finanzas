<?php
// public/index.php
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path && is_file($path)) {
        return false;
    }
    // Simulamos mod_rewrite de Apache para el servidor built-in
    $_GET['url'] = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}

// Iniciar sesión para el manejo de usuarios
session_start();

// Autoloading básico de clases requeridas
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/controllers/Controller.php';

// Sistema de enrutamiento básico
$url = !empty($_GET['url']) ? rtrim($_GET['url'], '/') : 'dashboard/index';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

// Archivo del controlador
$controllerFile = __DIR__ . '/../src/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        // Llamar al método pasando los parámetros restantes de la URL
        $params = array_slice($urlParts, 2);
        call_user_func_array([$controller, $methodName], $params);
    } else {
        echo "<h1>Error 404</h1><p>Método no encontrado.</p>";
    }
} else {
    echo "<h1>Error 404</h1><p>Página no encontrada.</p>";
}
