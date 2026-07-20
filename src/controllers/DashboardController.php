<?php

class DashboardController extends Controller {
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $usuarioModel = $this->model('Usuario');
        $transaccionModel = $this->model('Transaccion');
        $cuentaModel = $this->model('Cuenta');

        $usuario = $usuarioModel->getById($usuario_id);
        
        // Procesar transacciones recurrentes automáticamente
        $transaccionModel->processRecurring($usuario_id);

        $balance = $transaccionModel->getBalance($usuario_id);
        
        // El balance total ahora es la suma de los saldos de todas las cuentas
        $cuentas = $cuentaModel->getAllByUser($usuario_id);
        $balanceTotal = 0;
        foreach ($cuentas as $c) {
            $balanceTotal += $c['saldo'];
        }
        $balance['balance'] = $balanceTotal;

        $recentTransactions = $transaccionModel->getFiltered($usuario_id, [], 5, 0);
        
        $gastosPorCategoria = $transaccionModel->getExpensesByCategory($usuario_id, date('n'), date('Y'));
        
        // Calcular Tasa de Ahorro
        $tasaAhorro = 0;
        if ($balance['ingresos_mes'] > 0) {
            $tasaAhorro = (($balance['ingresos_mes'] - $balance['gastos_mes']) / $balance['ingresos_mes']) * 100;
        }
        
        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'nombre' => $usuario['nombre'],
            'balance' => $balance,
            'cuentas' => $cuentas,
            'recentTransactions' => $recentTransactions,
            'gastosPorCategoria' => $gastosPorCategoria,
            'tasaAhorro' => $tasaAhorro
        ]);
    }
}
