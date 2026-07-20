<?php

class Controller {
    /**
     * Carga un modelo
     */
    protected function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    /**
     * Carga una vista y le pasa datos
     */
    protected function view($view, $data = []) {
        // Hacemos que las claves del array $data estén disponibles como variables
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("La vista '$view' no existe.");
        }
    }

    /**
     * Redirige a otra URL
     */
    protected function redirect($url) {
        header("Location: /" . ltrim($url, '/'));
        exit();
    }
}
