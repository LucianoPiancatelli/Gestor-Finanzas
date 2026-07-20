<?php

class BudgetController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $mes = $_GET['mes'] ?? date('n');
        $anio = $_GET['anio'] ?? date('Y');

        $presupuestoModel = $this->model('Presupuesto');
        $categoriaModel = $this->model('Categoria');
        
        $presupuestos = $presupuestoModel->getAllWithStatus($usuario_id, $mes, $anio);
        $categorias = $categoriaModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save') {
            $categoria_id = $_POST['categoria_id'];
            $monto_limite = $_POST['monto_limite'];
            
            if (empty($categoria_id) || empty($monto_limite) || $monto_limite <= 0) {
                $error = "Debe seleccionar una categoría y un monto válido.";
            } else {
                $presupuestoModel->createOrUpdate($usuario_id, $categoria_id, $monto_limite, $mes, $anio);
                $this->redirect("budget/index?mes=$mes&anio=$anio");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
            $id = $_POST['id'];
            $presupuestoModel->delete($id, $usuario_id);
            $this->redirect("budget/index?mes=$mes&anio=$anio");
        }

        $this->view('budgets/index', [
            'title' => 'Mis Presupuestos',
            'presupuestos' => $presupuestos,
            'categorias' => $categorias,
            'mes' => $mes,
            'anio' => $anio,
            'error' => $error ?? null
        ]);
    }
}
