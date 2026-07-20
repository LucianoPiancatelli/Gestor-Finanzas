<?php

class GoalController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $goalModel = $this->model('Goal');
        
        $goals = $goalModel->getAll($usuario_id);

        $this->view('goals/index', [
            'title' => 'Mis Objetivos de Ahorro',
            'goals' => $goals
        ]);
    }

    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $monto_objetivo = floatval($_POST['monto_objetivo']);
            $fecha_limite = trim($_POST['fecha_limite']);

            if (empty($nombre) || $monto_objetivo <= 0) {
                $error = 'Por favor ingresa un nombre y un monto válido.';
            } else {
                $goalModel = $this->model('Goal');
                if ($goalModel->create($_SESSION['usuario_id'], $nombre, $monto_objetivo, $fecha_limite)) {
                    $this->redirect('goal/index');
                } else {
                    $error = 'Error al crear el objetivo.';
                }
            }
        }

        $this->view('goals/create', [
            'title' => 'Nuevo Objetivo',
            'error' => $error
        ]);
    }

    public function addFunds() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $monto = floatval($_POST['monto']);
            
            if ($monto > 0) {
                $goalModel = $this->model('Goal');
                $goalModel->addFunds($id, $_SESSION['usuario_id'], $monto);
            }
        }
        $this->redirect('goal/index');
    }

    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $goalModel = $this->model('Goal');
            $goalModel->delete($id, $_SESSION['usuario_id']);
        }
        
        $this->redirect('goal/index');
    }
}
