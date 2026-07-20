<?php

class TransactionController extends Controller {
    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $categoriaModel = $this->model('Categoria');
        $transaccionModel = $this->model('Transaccion');
        $cuentaModel = $this->model('Cuenta');
        $usuario_id = $_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoria_id = $_POST['categoria_id'] ?? '';
            $cuenta_id = $_POST['cuenta_id'] ?? '';
            $monto = $_POST['monto'] ?? '';
            $descripcion = trim($_POST['descripcion'] ?? '');
            $fecha = $_POST['fecha'] ?? '';
            $es_recurrente = isset($_POST['es_recurrente']) ? 1 : 0;
            $frecuencia = $_POST['frecuencia'] ?? null;

            // Validation
            if (empty($categoria_id) || empty($cuenta_id) || empty($monto) || empty($descripcion) || empty($fecha)) {
                $error = "Todos los campos obligatorios deben completarse.";
            } elseif (!is_numeric($monto) || $monto < 0) {
                $error = "El monto debe ser un número positivo válido.";
            } else {
                $descripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
                if ($transaccionModel->create($usuario_id, $categoria_id, $cuenta_id, $monto, $descripcion, $fecha, $es_recurrente, $frecuencia)) {
                    $this->redirect('dashboard/index');
                } else {
                    $error = "Error al crear la transacción.";
                }
            }
        }

        $categorias = $categoriaModel->getAll();
        $cuentas = $cuentaModel->getAllByUser($usuario_id);
        
        $this->view('transactions/create', [
            'title' => 'Nueva Transacción',
            'categorias' => $categorias,
            'cuentas' => $cuentas,
            'error' => $error ?? null
        ]);
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $transaccionModel = $this->model('Transaccion');
        $categoriaModel = $this->model('Categoria');
        
        // Paginación
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = $page > 0 ? $page : 1;
        $offset = ($page - 1) * $limit;

        // Filtros
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'tipo' => $_GET['tipo'] ?? ''
        ];
        
        $transacciones = $transaccionModel->getFiltered($usuario_id, $filtros, $limit, $offset);
        $totalItems = $transaccionModel->countFiltered($usuario_id, $filtros);
        $totalPages = ceil($totalItems / $limit);
        
        $categorias = $categoriaModel->getAll();

        $this->view('transactions/index', [
            'title' => 'Historial de Transacciones',
            'transacciones' => $transacciones,
            'categorias' => $categorias,
            'filtros' => $filtros,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function receipt($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $transaccionModel = $this->model('Transaccion');
        
        $transaccion = $transaccionModel->getById($id, $usuario_id);
        
        if (!$transaccion) {
            $this->redirect('transaction/index');
        }

        // Generar PDF usando FPDF
        require_once __DIR__ . '/../libs/fpdf/fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Colores y Fuentes
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor(37, 99, 235); // Blue 600
        $pdf->Cell(0, 15, mb_convert_encoding('FinanzasPro', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(100, 116, 139); // Slate 500
        $pdf->Cell(0, 10, mb_convert_encoding('Comprobante de Operación', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(10);
        
        // Marca de agua simplificada
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(241, 245, 249); // Slate 100
        $pdf->Cell(0, 10, mb_convert_encoding('*** OPERACIÓN EXITOSA ***', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(5);
        
        // Detalles de la operación
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(30, 41, 59); // Slate 800
        
        $monto_str = ($transaccion['categoria_tipo'] == 'ingreso' ? '+' : '-') . '$' . number_format($transaccion['monto'], 2);
        
        $pdf->Cell(50, 10, mb_convert_encoding('Número de Operación:', 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, '#FP-' . str_pad($transaccion['id'], 8, '0', STR_PAD_LEFT), 0, 1);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Fecha:', 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, date('d/m/Y H:i', strtotime($transaccion['creado_en'])), 0, 1);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, mb_convert_encoding('Descripción:', 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding($transaccion['descripcion'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, mb_convert_encoding('Categoría:', 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding($transaccion['categoria_nombre'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Cuenta asociada:', 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding($transaccion['cuenta_nombre'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        
        $pdf->Ln(15);
        
        // Monto grande
        $pdf->SetFont('Arial', 'B', 32);
        if ($transaccion['categoria_tipo'] == 'ingreso') {
            $pdf->SetTextColor(16, 185, 129); // Emerald 500
        } else {
            $pdf->SetTextColor(30, 41, 59); // Slate 800
        }
        $pdf->Cell(0, 20, $monto_str, 0, 1, 'C');
        
        $pdf->Output('I', 'Comprobante_FP' . $transaccion['id'] . '.pdf');
        exit;
    }

    public function transfer() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $cuentaModel = $this->model('Cuenta');
        $usuario_id = $_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $origen = $_POST['cuenta_origen_id'] ?? '';
            $destino = $_POST['cuenta_destino_id'] ?? '';
            $monto = $_POST['monto'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d');

            if (empty($origen) || empty($destino) || empty($monto)) {
                $error = "Todos los campos son requeridos.";
            } elseif ($origen == $destino) {
                $error = "La cuenta origen y destino no pueden ser la misma.";
            } elseif (!is_numeric($monto) || $monto <= 0) {
                $error = "El monto debe ser positivo.";
            } else {
                $transaccionModel = $this->model('Transaccion');
                if ($transaccionModel->transfer($usuario_id, $origen, $destino, $monto, $fecha)) {
                    $this->redirect('dashboard/index');
                } else {
                    $error = "Error al realizar la transferencia.";
                }
            }
        }

        $cuentas = $cuentaModel->getAllByUser($usuario_id);
        $this->view('transactions/transfer', [
            'title' => 'Transferir a Mis Cuentas',
            'cuentas' => $cuentas,
            'error' => $error ?? null
        ]);
    }

    public function transfer_p2p() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $usuario_id = $_SESSION['usuario_id'];
        $cuentaModel = $this->model('Cuenta');
        $usuarioModel = $this->model('Usuario');
        $transaccionModel = $this->model('Transaccion');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cuenta_origen_id = $_POST['cuenta_origen_id'] ?? '';
            $email_destinatario = trim($_POST['email_destinatario'] ?? '');
            $monto = $_POST['monto'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d');

            if (empty($cuenta_origen_id) || empty($email_destinatario) || empty($monto)) {
                $error = "Todos los campos son requeridos.";
            } elseif (!is_numeric($monto) || $monto <= 0) {
                $error = "El monto debe ser positivo.";
            } else {
                // Verificar email
                $destinatario = $usuarioModel->getByEmail($email_destinatario);
                $emisor = $usuarioModel->getById($usuario_id);

                if (!$destinatario) {
                    $error = "No existe un usuario con ese correo electrónico.";
                } elseif ($destinatario['id'] == $usuario_id) {
                    $error = "No puedes enviarte dinero a ti mismo a través de P2P. Usa 'Transferir a mis cuentas'.";
                } else {
                    // Verificar fondos en la cuenta origen
                    $cuenta_origen = $cuentaModel->getById($cuenta_origen_id, $usuario_id);
                    if ($cuenta_origen['saldo'] < $monto) {
                        $error = "Saldo insuficiente en la cuenta origen.";
                    } else {
                        // Realizar transferencia P2P
                        if ($transaccionModel->transferP2P($usuario_id, $cuenta_origen_id, $destinatario['id'], $emisor['nombre'], $destinatario['nombre'], $monto, $fecha)) {
                            $this->redirect('dashboard/index');
                        } else {
                            $error = "Error al procesar la transferencia P2P.";
                        }
                    }
                }
            }
        }

        $cuentas = $cuentaModel->getAllByUser($usuario_id);
        $this->view('transactions/transfer_p2p', [
            'title' => 'Enviar a Otro Usuario',
            'cuentas' => $cuentas,
            'error' => $error ?? null
        ]);
    }

    public function delete($id) {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = $_SESSION['usuario_id'];
            $transaccionModel = $this->model('Transaccion');
            $transaccionModel->delete($id, $usuario_id);
        }
        
        $this->redirect('dashboard/index');
    }
}
