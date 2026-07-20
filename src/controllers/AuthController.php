<?php

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['demo_login'])) {
            require_once __DIR__ . '/../models/DemoSeeder.php';
            $seeder = new DemoSeeder();
            $usuario = $seeder->createDemoUser();
            
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $this->redirect('dashboard/index');
                return;
            } else {
                $error = "Hubo un problema al generar tu entorno de prueba. Intenta de nuevo.";
                $this->view('auth/login', ['error' => $error]);
                return;
            }
        }
        $this->view('auth/login');
    }
    public function verify2fa() {
        if (!isset($_SESSION['pending_2fa_user_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = trim($_POST['code']);
            // Simulación: cualquier código de 6 dígitos es válido por simplicidad,
            // en un caso real se verificaría contra $usuario['two_factor_secret'] (TOTP)
            if (strlen($code) == 6 && is_numeric($code)) {
                $_SESSION['usuario_id'] = $_SESSION['pending_2fa_user_id'];
                $_SESSION['usuario_nombre'] = $_SESSION['pending_2fa_user_nombre'];
                unset($_SESSION['pending_2fa_user_id']);
                unset($_SESSION['pending_2fa_user_nombre']);
                $this->redirect('dashboard/index');
            } else {
                $error = "Código inválido. Usa cualquier código de 6 dígitos.";
                $this->view('auth/verify2fa', ['error' => $error]);
                return;
            }
        }
        $this->view('auth/verify2fa');
    }



    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
