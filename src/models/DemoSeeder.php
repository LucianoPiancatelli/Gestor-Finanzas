<?php

require_once __DIR__ . '/../../config/db.php';

class DemoSeeder {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function createDemoUser() {
        // 1. Create User
        $demoId = uniqid('demo_');
        $email = $demoId . '@portfolio.demo';
        $nombre = 'Usuario Demo';
        $passwordHash = password_hash('demo1234', PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $passwordHash]);
        $usuario_id = $this->db->lastInsertId();

        // 2. Create Accounts
        $stmt = $this->db->prepare("INSERT INTO cuentas (usuario_id, nombre, tipo, saldo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario_id, 'Efectivo', 'efectivo', 15000.00]);
        $cuenta_efectivo_id = $this->db->lastInsertId();

        $stmt->execute([$usuario_id, 'Cuenta Bancaria Principal', 'banco', 145000.00]);
        $cuenta_banco_id = $this->db->lastInsertId();

        // 3. Obtener o crear categorías globales (sin usuario_id)
        $categorias = [
            ['Sueldo', 'ingreso', '#10b981'],
            ['Comida y Supermercado', 'gasto', '#ef4444'],
            ['Servicios', 'gasto', '#3b82f6'],
            ['Transporte', 'gasto', '#f59e0b'],
            ['Ocio y Entretenimiento', 'gasto', '#8b5cf6'],
            ['Inversiones', 'gasto', '#6366f1']
        ];
        $catIds = [];
        
        $stmtCheck = $this->db->prepare("SELECT id FROM categorias WHERE nombre = ? AND tipo = ?");
        $stmtCat = $this->db->prepare("INSERT INTO categorias (nombre, tipo) VALUES (?, ?)");
        
        foreach ($categorias as $cat) {
            $stmtCheck->execute([$cat[0], $cat[1]]);
            $existente = $stmtCheck->fetch();
            if ($existente) {
                $catIds[$cat[0]] = $existente['id'];
            } else {
                $stmtCat->execute([$cat[0], $cat[1]]);
                $catIds[$cat[0]] = $this->db->lastInsertId();
            }
        }

        // 4. Create Transactions (Current Month)
        $mesActual = date('Y-m');
        $transacciones = [
            [$cuenta_banco_id, $catIds['Sueldo'], 120000.00, "$mesActual-01", 'Sueldo mensual'],
            [$cuenta_banco_id, $catIds['Servicios'], 8500.00, "$mesActual-05", 'Internet y Luz'],
            [$cuenta_banco_id, $catIds['Comida y Supermercado'], 24000.00, "$mesActual-08", 'Supermercado mensual'],
            [$cuenta_efectivo_id, $catIds['Comida y Supermercado'], 4500.00, "$mesActual-12", 'Cena fuera'],
            [$cuenta_efectivo_id, $catIds['Transporte'], 1200.00, "$mesActual-15", 'Nafta / Transporte'],
            [$cuenta_banco_id, $catIds['Inversiones'], 15000.00, "$mesActual-16", 'Compra de CEDEARs'],
            [$cuenta_banco_id, $catIds['Ocio y Entretenimiento'], 3000.00, "$mesActual-20", 'Cine y entradas']
        ];
        
        $stmtTx = $this->db->prepare("INSERT INTO transacciones (usuario_id, cuenta_id, categoria_id, monto, fecha, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($transacciones as $tx) {
            $stmtTx->execute([$usuario_id, $tx[0], $tx[1], $tx[2], $tx[3], $tx[4]]);
        }

        // 5. Create Budgets
        $stmtBudget = $this->db->prepare("INSERT INTO presupuestos (usuario_id, categoria_id, monto_limite, mes, anio) VALUES (?, ?, ?, ?, ?)");
        $mesActualNum = date('m');
        $anioActualNum = date('Y');
        $stmtBudget->execute([$usuario_id, $catIds['Comida y Supermercado'], 30000.00, $mesActualNum, $anioActualNum]);
        $stmtBudget->execute([$usuario_id, $catIds['Ocio y Entretenimiento'], 10000.00, $mesActualNum, $anioActualNum]);

        // 6. Create Savings Goal
        $stmtGoal = $this->db->prepare("INSERT INTO savings_goals (usuario_id, nombre, monto_objetivo, monto_actual, fecha_limite) VALUES (?, ?, ?, ?, ?)");
        $fechaLimite = date('Y-m-d', strtotime('+6 months'));
        $stmtGoal->execute([$usuario_id, 'Vacaciones 2025', 500000.00, 150000.00, $fechaLimite]);

        // 7. Create Subscriptions
        $stmtSub = $this->db->prepare("INSERT INTO subscriptions (usuario_id, cuenta_id, categoria_id, nombre, monto, frecuencia, proximo_cobro, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $proximoCobro = date('Y-m-d', strtotime('+5 days'));
        $stmtSub->execute([$usuario_id, $cuenta_banco_id, $catIds['Ocio y Entretenimiento'], 'Netflix', 4500.00, 'mensual', $proximoCobro, 1]);

        // 8. Create Investments
        $stmtInv = $this->db->prepare("INSERT INTO investments (usuario_id, nombre, tipo, monto_invertido, valor_actual, fecha_inicio) VALUES (?, ?, ?, ?, ?, ?)");
        $fechaInicioInv = date('Y-m-d', strtotime('-3 months'));
        $stmtInv->execute([$usuario_id, 'Fondo Común de Inversión', 'fondo', 100000.00, 115000.00, $fechaInicioInv]);

        return [
            'id' => $usuario_id,
            'nombre' => $nombre,
            'email' => $email
        ];
    }
}
