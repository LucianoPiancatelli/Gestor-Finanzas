<?php

class AccountController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $cuentaModel = $this->model('Cuenta');
        $cuentas = $cuentaModel->getAllByUser($usuario_id);

        $this->view('accounts/index', [
            'title' => 'Mis Cuentas',
            'cuentas' => $cuentas
        ]);
    }

    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
            $tipo = $_POST['tipo'];
            $saldo = $_POST['saldo'] ?? 0;
            $usuario_id = $_SESSION['usuario_id'];

            if (empty($nombre) || empty($tipo)) {
                $error = "Nombre y tipo son requeridos.";
            } else {
                $cuentaModel = $this->model('Cuenta');
                if ($cuentaModel->create($usuario_id, $nombre, $tipo, $saldo)) {
                    $this->redirect('account/index');
                } else {
                    $error = "Error al crear la cuenta.";
                }
            }
        }

        $this->view('accounts/create', [
            'title' => 'Nueva Cuenta',
            'error' => $error ?? null
        ]);
    }

    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = $_SESSION['usuario_id'];
            $cuentaModel = $this->model('Cuenta');
            $cuentaModel->delete($id, $usuario_id);
        }
        
        $this->redirect('account/index');
    }
}
