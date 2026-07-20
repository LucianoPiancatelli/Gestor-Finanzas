<?php

class InvestmentController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $investmentModel = $this->model('Investment');
        
        $investments = $investmentModel->getAll($usuario_id);

        $this->view('investments/index', [
            'title' => 'Mis Inversiones',
            'investments' => $investments
        ]);
    }

    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $tipo = $_POST['tipo'];
            $monto_invertido = floatval($_POST['monto_invertido']);
            $fecha_inicio = trim($_POST['fecha_inicio']);

            if (empty($nombre) || empty($tipo) || $monto_invertido <= 0 || empty($fecha_inicio)) {
                $error = 'Por favor completa todos los campos requeridos correctamente.';
            } else {
                $investmentModel = $this->model('Investment');
                if ($investmentModel->create($_SESSION['usuario_id'], $nombre, $tipo, $monto_invertido, $fecha_inicio)) {
                    $this->redirect('investment/index');
                } else {
                    $error = 'Error al registrar la inversión.';
                }
            }
        }

        $this->view('investments/create', [
            'title' => 'Nueva Inversión',
            'error' => $error
        ]);
    }

    public function update() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nuevo_valor = floatval($_POST['nuevo_valor']);
            
            if ($nuevo_valor >= 0) {
                $investmentModel = $this->model('Investment');
                $investmentModel->updateValue($id, $_SESSION['usuario_id'], $nuevo_valor);
            }
        }
        $this->redirect('investment/index');
    }

    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $investmentModel = $this->model('Investment');
            $investmentModel->delete($id, $_SESSION['usuario_id']);
        }
        
        $this->redirect('investment/index');
    }
}
