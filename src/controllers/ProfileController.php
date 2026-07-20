<?php

class ProfileController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->getById($usuario_id);

        $this->view('profile/index', [
            'title' => 'Mi Perfil',
            'usuario' => $usuario
        ]);
    }

    public function enable2fa() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $usuarioModel = $this->model('Usuario');
        
        // Simulación de generación de secreto
        $secret = bin2hex(random_bytes(10));
        
        $usuarioModel->update2FA($usuario_id, $secret, true);
        
        $this->redirect('profile/index');
    }

    public function disable2fa() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $usuarioModel = $this->model('Usuario');
        
        $usuarioModel->update2FA($usuario_id, null, false);
        
        $this->redirect('profile/index');
    }
}
