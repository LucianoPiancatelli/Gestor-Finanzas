<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/models/Usuario.php';
require_once __DIR__ . '/src/models/Cuenta.php';
require_once __DIR__ . '/src/models/Categoria.php';
require_once __DIR__ . '/src/models/Transaccion.php';
require_once __DIR__ . '/src/models/Presupuesto.php';

echo "Iniciando análisis de funcionalidades...\n\n";

$db = (new Database())->getConexion();
$db->beginTransaction();

try {
    echo "[TEST 1] Modelo Usuario\n";
    $usuarioModel = new Usuario();
    $email_test = "test_" . time() . "@test.com";
    $usuarioModel->registrar("Usuario Test", $email_test, "password123");
    $usuario = $usuarioModel->getByEmail($email_test);
    if (!$usuario) throw new Exception("Error al crear o buscar usuario.");
    echo "✔ Usuario creado correctamente. ID: {$usuario['id']}\n";

    echo "\n[TEST 2] Modelo Cuenta\n";
    $cuentaModel = new Cuenta();
    $cuentaModel->create($usuario['id'], "Cuenta Test 1", "Banco", 1000.50);
    $cuentaModel->create($usuario['id'], "Cuenta Test 2", "Efectivo", 500.00);
    $cuentas = $cuentaModel->getAllByUser($usuario['id']);
    if (count($cuentas) < 2) throw new Exception("Error al crear cuentas.");
    $cuenta1 = $cuentas[0]['id'];
    $cuenta2 = $cuentas[1]['id'];
    echo "✔ Cuentas creadas correctamente.\n";

    echo "\n[TEST 3] Modelo Categoria\n";
    $categoriaModel = new Categoria();
    // Assuming some default categories exist
    $categorias = $categoriaModel->getAll();
    if (count($categorias) == 0) throw new Exception("No hay categorías en la BD.");
    $catGasto = null;
    $catIngreso = null;
    foreach ($categorias as $c) {
        if ($c['tipo'] == 'gasto' && !$catGasto) $catGasto = $c['id'];
        if ($c['tipo'] == 'ingreso' && !$catIngreso) $catIngreso = $c['id'];
    }
    if (!$catGasto || !$catIngreso) throw new Exception("Faltan categorías por defecto de ingreso o gasto.");
    echo "✔ Categorías obtenidas correctamente.\n";

    echo "\n[TEST 4] Modelo Transaccion - Crear Gasto e Ingreso\n";
    $transaccionModel = new Transaccion();
    $transaccionModel->create($usuario['id'], $catGasto, $cuenta1, 200, "Gasto Test", date('Y-m-d'));
    $transaccionModel->create($usuario['id'], $catIngreso, $cuenta1, 500, "Ingreso Test", date('Y-m-d'));
    $balance = $transaccionModel->getBalance($usuario['id']);
    if ($balance['gastos'] != 200 || $balance['ingresos'] != 500) throw new Exception("Error en el cálculo de balance.");
    echo "✔ Transacciones y balance calculados correctamente.\n";

    echo "\n[TEST 5] Modelo Transaccion - Transferencia entre cuentas propias\n";
    $transaccionModel->transfer($usuario['id'], $cuenta1, $cuenta2, 100, date('Y-m-d'));
    // Verify saldo
    $cuenta_origen = $cuentaModel->getById($cuenta1, $usuario['id']);
    $cuenta_destino = $cuentaModel->getById($cuenta2, $usuario['id']);
    // Initial: C1: 1000.50 - 200 + 500 = 1300.50. After transfer 100: C1: 1200.50
    // C2: 500 + 100 = 600
    if (abs($cuenta_origen['saldo'] - 1200.50) > 0.01) throw new Exception("Saldo de cuenta origen incorrecto en transferencia.");
    if (abs($cuenta_destino['saldo'] - 600.00) > 0.01) throw new Exception("Saldo de cuenta destino incorrecto en transferencia.");
    echo "✔ Transferencia entre cuentas verificada.\n";

    echo "\n[TEST 6] Modelo Transaccion - Transferencia P2P\n";
    // Crear usuario 2
    $email_test2 = "test2_" . time() . "@test.com";
    $usuarioModel->registrar("Usuario Destino", $email_test2, "password123");
    $usuario2 = $usuarioModel->getByEmail($email_test2);
    $cuentaModel->create($usuario2['id'], "Cuenta Destino P2P", "Efectivo", 0);
    
    $transaccionModel->transferP2P($usuario['id'], $cuenta1, $usuario2['id'], "Emisor", "Receptor", 300, date('Y-m-d'));
    $cuenta_origen_p2p = $cuentaModel->getById($cuenta1, $usuario['id']);
    if (abs($cuenta_origen_p2p['saldo'] - 900.50) > 0.01) throw new Exception("Saldo de origen P2P incorrecto.");
    echo "✔ Transferencia P2P ejecutada correctamente.\n";

    echo "\n[TEST 7] Modelo Presupuesto - Crear y Actualizar\n";
    $presupuestoModel = new Presupuesto();
    $presupuestoModel->createOrUpdate($usuario['id'], $catGasto, 1000, date('n'), date('Y'));
    $presupuestos = $presupuestoModel->getAllWithStatus($usuario['id'], date('n'), date('Y'));
    if (count($presupuestos) == 0 || $presupuestos[0]['monto_limite'] != 1000) throw new Exception("Error al crear presupuesto.");
    
    // Update
    $presupuestoModel->createOrUpdate($usuario['id'], $catGasto, 1500, date('n'), date('Y'));
    $presupuestos_upd = $presupuestoModel->getAllWithStatus($usuario['id'], date('n'), date('Y'));
    if ($presupuestos_upd[0]['monto_limite'] != 1500) throw new Exception("Error al actualizar presupuesto.");
    echo "✔ Presupuestos creados y actualizados correctamente.\n";

    echo "\n[TEST 8] Obtener detalles de Transacción para PDF\n";
    $transacciones = $transaccionModel->getRecentByUser($usuario['id']);
    if (empty($transacciones)) throw new Exception("No hay transacciones recientes.");
    $detalle = $transaccionModel->getById($transacciones[0]['id'], $usuario['id']);
    if (!isset($detalle['cuenta_nombre']) || !isset($detalle['categoria_nombre'])) throw new Exception("El detalle de la transacción no contiene Joins requeridos para el PDF.");
    echo "✔ Detalle de transacción obtenido correctamente.\n";

    echo "\n*** TODAS LAS FUNCIONALIDADES ESTÁN OPERATIVAS Y PASARON EL ANÁLISIS ***\n";

} catch (Exception $e) {
    echo "\n[X] ERROR ENCONTRADO: " . $e->getMessage() . "\n";
} finally {
    // Revertimos todos los cambios para no ensuciar la base de datos real
    $db->rollBack();
    echo "\nAnálisis finalizado. Los datos de prueba han sido limpiados.\n";
}
