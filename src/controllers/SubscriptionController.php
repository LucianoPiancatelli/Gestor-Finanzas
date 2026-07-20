<?php

class SubscriptionController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $subscriptionModel = $this->model('Subscription');
        
        $subscriptions = $subscriptionModel->getAll($usuario_id);

        $this->view('subscriptions/index', [
            'title' => 'Mis Suscripciones',
            'subscriptions' => $subscriptions
        ]);
    }

    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $cuentaModel = $this->model('Cuenta');
        $categoriaModel = $this->model('Categoria');
        
        $cuentas = $cuentaModel->getAll($usuario_id);
        $categorias = $categoriaModel->getAll();

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $monto = floatval($_POST['monto']);
            $cuenta_id = $_POST['cuenta_id'];
            $categoria_id = $_POST['categoria_id'];
            $frecuencia = $_POST['frecuencia'];
            $proximo_cobro = $_POST['proximo_cobro'];

            if (empty($nombre) || $monto <= 0 || empty($cuenta_id) || empty($categoria_id) || empty($proximo_cobro)) {
                $error = 'Por favor completa todos los campos requeridos correctamente.';
            } else {
                $subscriptionModel = $this->model('Subscription');
                if ($subscriptionModel->create($usuario_id, $cuenta_id, $categoria_id, $nombre, $monto, $frecuencia, $proximo_cobro)) {
                    $this->redirect('subscription/index');
                } else {
                    $error = 'Error al registrar la suscripción.';
                }
            }
        }

        $this->view('subscriptions/create', [
            'title' => 'Nueva Suscripción',
            'cuentas' => $cuentas,
            'categorias' => $categorias,
            'error' => $error
        ]);
    }

    public function toggle($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subscriptionModel = $this->model('Subscription');
            $subscriptionModel->toggleActive($id, $_SESSION['usuario_id']);
        }
        
        $this->redirect('subscription/index');
    }

    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subscriptionModel = $this->model('Subscription');
            $subscriptionModel->delete($id, $_SESSION['usuario_id']);
        }
        
        $this->redirect('subscription/index');
    }
}
