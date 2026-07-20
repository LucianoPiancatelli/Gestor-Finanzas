<?php

class Transaccion {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAllByUser($usuario_id) {
        $query = "SELECT t.*, c.nombre as categoria_nombre, c.tipo as categoria_tipo 
                  FROM transacciones t 
                  JOIN categorias c ON t.categoria_id = c.id 
                  WHERE t.usuario_id = :usuario_id 
                  ORDER BY t.fecha DESC, t.creado_en DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id, $usuario_id) {
        $query = "SELECT t.*, c.nombre as categoria_nombre, c.tipo as categoria_tipo, cta.nombre as cuenta_nombre 
                  FROM transacciones t 
                  JOIN categorias c ON t.categoria_id = c.id 
                  JOIN cuentas cta ON t.cuenta_id = cta.id
                  WHERE t.id = :id AND t.usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
        return $stmt->fetch();
    }

    public function getRecentByUser($usuario_id, $limit = 5) {
        $query = "SELECT t.*, c.nombre as categoria_nombre, c.tipo as categoria_tipo 
                  FROM transacciones t 
                  JOIN categorias c ON t.categoria_id = c.id 
                  WHERE t.usuario_id = :usuario_id 
                  ORDER BY t.fecha DESC, t.creado_en DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBalance($usuario_id) {
        $query = "SELECT 
                    SUM(CASE WHEN c.tipo = 'ingreso' THEN t.monto ELSE 0 END) as total_ingresos,
                    SUM(CASE WHEN c.tipo = 'gasto' THEN t.monto ELSE 0 END) as total_gastos,
                    SUM(CASE WHEN c.tipo = 'ingreso' AND MONTH(t.fecha) = MONTH(CURRENT_DATE()) AND YEAR(t.fecha) = YEAR(CURRENT_DATE()) THEN t.monto ELSE 0 END) as ingresos_mes,
                    SUM(CASE WHEN c.tipo = 'gasto' AND MONTH(t.fecha) = MONTH(CURRENT_DATE()) AND YEAR(t.fecha) = YEAR(CURRENT_DATE()) THEN t.monto ELSE 0 END) as gastos_mes
                  FROM transacciones t
                  JOIN categorias c ON t.categoria_id = c.id
                  WHERE t.usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $result = $stmt->fetch();
        
        $ingresos = $result['total_ingresos'] ?? 0;
        $gastos = $result['total_gastos'] ?? 0;
        
        return [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'balance' => $ingresos - $gastos,
            'ingresos_mes' => $result['ingresos_mes'] ?? 0,
            'gastos_mes' => $result['gastos_mes'] ?? 0
        ];
    }

    public function getFiltered($usuario_id, $filtros, $limit, $offset) {
        $query = "SELECT t.*, c.nombre as categoria_nombre, c.tipo as categoria_tipo 
                  FROM transacciones t 
                  JOIN categorias c ON t.categoria_id = c.id 
                  WHERE t.usuario_id = :usuario_id";
        $params = [':usuario_id' => $usuario_id];

        if (!empty($filtros['fecha_inicio'])) {
            $query .= " AND t.fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $filtros['fecha_inicio'];
        }
        if (!empty($filtros['fecha_fin'])) {
            $query .= " AND t.fecha <= :fecha_fin";
            $params[':fecha_fin'] = $filtros['fecha_fin'];
        }
        if (!empty($filtros['categoria_id'])) {
            $query .= " AND t.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }
        if (!empty($filtros['tipo'])) {
            $query .= " AND c.tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }

        $query .= " ORDER BY t.fecha DESC, t.creado_en DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countFiltered($usuario_id, $filtros) {
        $query = "SELECT COUNT(*) as total 
                  FROM transacciones t 
                  JOIN categorias c ON t.categoria_id = c.id 
                  WHERE t.usuario_id = :usuario_id";
        $params = [':usuario_id' => $usuario_id];

        if (!empty($filtros['fecha_inicio'])) {
            $query .= " AND t.fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $filtros['fecha_inicio'];
        }
        if (!empty($filtros['fecha_fin'])) {
            $query .= " AND t.fecha <= :fecha_fin";
            $params[':fecha_fin'] = $filtros['fecha_fin'];
        }
        if (!empty($filtros['categoria_id'])) {
            $query .= " AND t.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }
        if (!empty($filtros['tipo'])) {
            $query .= " AND c.tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    public function getExpensesByCategory($usuario_id, $mes, $anio) {
        $query = "
            SELECT c.nombre as categoria_nombre, SUM(t.monto) as total
            FROM transacciones t
            JOIN categorias c ON t.categoria_id = c.id
            WHERE t.usuario_id = :usuario_id 
              AND c.tipo = 'gasto'
              AND MONTH(t.fecha) = :mes 
              AND YEAR(t.fecha) = :anio
            GROUP BY c.id
            ORDER BY total DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':usuario_id' => $usuario_id, ':mes' => $mes, ':anio' => $anio]);
        return $stmt->fetchAll();
    }

    public function create($usuario_id, $categoria_id, $cuenta_id, $monto, $descripcion, $fecha, $es_recurrente = 0, $frecuencia = null) {
        try {
            $this->db->beginTransaction();

            // 1. Insertar transacción
            $query = "INSERT INTO transacciones (usuario_id, categoria_id, cuenta_id, monto, descripcion, fecha, es_recurrente, frecuencia) 
                      VALUES (:usuario_id, :categoria_id, :cuenta_id, :monto, :descripcion, :fecha, :es_recurrente, :frecuencia)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':categoria_id', $categoria_id);
            $stmt->bindParam(':cuenta_id', $cuenta_id);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':es_recurrente', $es_recurrente, PDO::PARAM_INT);
            $stmt->bindParam(':frecuencia', $frecuencia);
            $stmt->execute();

            // 2. Actualizar saldo de la cuenta
            // Obtenemos el tipo de categoría para saber si suma o resta
            $stmtCat = $this->db->prepare("SELECT tipo FROM categorias WHERE id = :cat_id");
            $stmtCat->execute([':cat_id' => $categoria_id]);
            $tipoCat = $stmtCat->fetch()['tipo'];

            if ($tipoCat == 'ingreso') {
                $queryCuenta = "UPDATE cuentas SET saldo = saldo + :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id";
            } else {
                $queryCuenta = "UPDATE cuentas SET saldo = saldo - :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id";
            }
            $stmtUpdate = $this->db->prepare($queryCuenta);
            $stmtUpdate->bindParam(':monto', $monto);
            $stmtUpdate->bindParam(':cuenta_id', $cuenta_id);
            $stmtUpdate->bindParam(':usuario_id', $usuario_id);
            $stmtUpdate->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete($id, $usuario_id) {
        try {
            $this->db->beginTransaction();
            
            // 1. Obtener detalles para revertir saldo
            $stmt = $this->db->prepare("SELECT t.monto, t.cuenta_id, c.tipo FROM transacciones t JOIN categorias c ON t.categoria_id = c.id WHERE t.id = :id AND t.usuario_id = :usuario_id");
            $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
            $transaccion = $stmt->fetch();

            if (!$transaccion) {
                $this->db->rollBack();
                return false;
            }

            // 2. Revertir saldo
            if ($transaccion['tipo'] == 'ingreso') {
                $queryCuenta = "UPDATE cuentas SET saldo = saldo - :monto WHERE id = :cuenta_id";
            } else {
                $queryCuenta = "UPDATE cuentas SET saldo = saldo + :monto WHERE id = :cuenta_id";
            }
            $stmtUpd = $this->db->prepare($queryCuenta);
            $stmtUpd->execute([':monto' => $transaccion['monto'], ':cuenta_id' => $transaccion['cuenta_id']]);

            // 3. Eliminar transacción
            $stmtDel = $this->db->prepare("DELETE FROM transacciones WHERE id = :id");
            $stmtDel->execute([':id' => $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function transfer($usuario_id, $cuenta_origen_id, $cuenta_destino_id, $monto, $fecha) {
        try {
            $this->db->beginTransaction();

            $stmtCatGasto = $this->db->query("SELECT id FROM categorias WHERE tipo = 'gasto' LIMIT 1");
            $catGastoId = $stmtCatGasto->fetch()['id'];
            
            $stmtCatIngreso = $this->db->query("SELECT id FROM categorias WHERE tipo = 'ingreso' LIMIT 1");
            $catIngresoId = $stmtCatIngreso->fetch()['id'];

            // 1. Egreso cuenta origen
            $descOrigen = "Transferencia a otra cuenta propia";
            $stmt1 = $this->db->prepare("INSERT INTO transacciones (usuario_id, categoria_id, cuenta_id, monto, descripcion, fecha) VALUES (:u, :c, :cta, :m, :d, :f)");
            $stmt1->execute([':u' => $usuario_id, ':c' => $catGastoId, ':cta' => $cuenta_origen_id, ':m' => $monto, ':d' => $descOrigen, ':f' => $fecha]);
            
            $stmtUpd1 = $this->db->prepare("UPDATE cuentas SET saldo = saldo - :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id");
            $stmtUpd1->execute([':monto' => $monto, ':cuenta_id' => $cuenta_origen_id, ':usuario_id' => $usuario_id]);

            // 2. Ingreso cuenta destino
            $descDestino = "Transferencia desde otra cuenta propia";
            $stmt2 = $this->db->prepare("INSERT INTO transacciones (usuario_id, categoria_id, cuenta_id, monto, descripcion, fecha) VALUES (:u, :c, :cta, :m, :d, :f)");
            $stmt2->execute([':u' => $usuario_id, ':c' => $catIngresoId, ':cta' => $cuenta_destino_id, ':m' => $monto, ':d' => $descDestino, ':f' => $fecha]);
            
            $stmtUpd2 = $this->db->prepare("UPDATE cuentas SET saldo = saldo + :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id");
            $stmtUpd2->execute([':monto' => $monto, ':cuenta_id' => $cuenta_destino_id, ':usuario_id' => $usuario_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function transferP2P($usuario_origen_id, $cuenta_origen_id, $usuario_destino_id, $nombre_origen, $nombre_destino, $monto, $fecha) {
        try {
            $this->db->beginTransaction();

            $stmtCatGasto = $this->db->query("SELECT id FROM categorias WHERE tipo = 'gasto' LIMIT 1");
            $catGastoId = $stmtCatGasto->fetch()['id'];
            
            $stmtCatIngreso = $this->db->query("SELECT id FROM categorias WHERE tipo = 'ingreso' LIMIT 1");
            $catIngresoId = $stmtCatIngreso->fetch()['id'];

            // Obtener cuenta destino por defecto
            $stmtCtaDest = $this->db->prepare("SELECT id FROM cuentas WHERE usuario_id = :u ORDER BY id ASC LIMIT 1");
            $stmtCtaDest->execute([':u' => $usuario_destino_id]);
            $cuenta_destino_id = $stmtCtaDest->fetch()['id'];

            // 1. Egreso cuenta origen
            $descOrigen = "Transferencia enviada a " . $nombre_destino;
            $stmt1 = $this->db->prepare("INSERT INTO transacciones (usuario_id, categoria_id, cuenta_id, monto, descripcion, fecha) VALUES (:u, :c, :cta, :m, :d, :f)");
            $stmt1->execute([':u' => $usuario_origen_id, ':c' => $catGastoId, ':cta' => $cuenta_origen_id, ':m' => $monto, ':d' => $descOrigen, ':f' => $fecha]);
            
            $stmtUpd1 = $this->db->prepare("UPDATE cuentas SET saldo = saldo - :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id");
            $stmtUpd1->execute([':monto' => $monto, ':cuenta_id' => $cuenta_origen_id, ':usuario_id' => $usuario_origen_id]);

            // 2. Ingreso cuenta destino
            $descDestino = "Transferencia recibida de " . $nombre_origen;
            $stmt2 = $this->db->prepare("INSERT INTO transacciones (usuario_id, categoria_id, cuenta_id, monto, descripcion, fecha) VALUES (:u, :c, :cta, :m, :d, :f)");
            $stmt2->execute([':u' => $usuario_destino_id, ':c' => $catIngresoId, ':cta' => $cuenta_destino_id, ':m' => $monto, ':d' => $descDestino, ':f' => $fecha]);
            
            $stmtUpd2 = $this->db->prepare("UPDATE cuentas SET saldo = saldo + :monto WHERE id = :cuenta_id AND usuario_id = :usuario_id");
            $stmtUpd2->execute([':monto' => $monto, ':cuenta_id' => $cuenta_destino_id, ':usuario_id' => $usuario_destino_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function processRecurring($usuario_id) {
        $mes_actual = date('n');
        $anio_actual = date('Y');

        // Buscar todas las transacciones recurrentes base del usuario
        // Asumimos que la "base" es cualquier transacción recurrente.
        // Pero para no duplicar infinitamente, buscaremos una transacción recurrente
        // y verificaremos si YA existe una para el mes actual (o año actual si es anual).
        
        $queryBase = "
            SELECT * FROM transacciones 
            WHERE usuario_id = :usuario_id 
            AND es_recurrente = 1
        ";
        $stmtBase = $this->db->prepare($queryBase);
        $stmtBase->execute([':usuario_id' => $usuario_id]);
        $recurrentes = $stmtBase->fetchAll();

        // Para evitar procesar la misma transacción multiplicada, 
        // agrupamos conceptualmente por descripcion y monto.
        $procesadas = [];

        foreach ($recurrentes as $rec) {
            $key = $rec['descripcion'] . '_' . $rec['monto'] . '_' . $rec['categoria_id'] . '_' . $rec['cuenta_id'];
            
            if (in_array($key, $procesadas)) continue;
            
            // Verificar si ya existe en este mes/año (para mensual) o año (para anual)
            $queryCheck = "
                SELECT COUNT(*) as existe FROM transacciones 
                WHERE usuario_id = :usuario_id 
                AND descripcion = :descripcion 
                AND es_recurrente = 1
            ";
            
            if ($rec['frecuencia'] == 'mensual') {
                $queryCheck .= " AND MONTH(fecha) = :mes AND YEAR(fecha) = :anio";
                $params = [
                    ':usuario_id' => $usuario_id,
                    ':descripcion' => $rec['descripcion'],
                    ':mes' => $mes_actual,
                    ':anio' => $anio_actual
                ];
            } else { // anual
                $queryCheck .= " AND YEAR(fecha) = :anio";
                $params = [
                    ':usuario_id' => $usuario_id,
                    ':descripcion' => $rec['descripcion'],
                    ':anio' => $anio_actual
                ];
            }

            $stmtCheck = $this->db->prepare($queryCheck);
            $stmtCheck->execute($params);
            $existe = $stmtCheck->fetch()['existe'];

            if ($existe == 0) {
                // No existe para el periodo actual, la duplicamos!
                // Usamos la fecha de hoy.
                $fecha_nueva = date('Y-m-d');
                $this->create(
                    $usuario_id, 
                    $rec['categoria_id'], 
                    $rec['cuenta_id'], 
                    $rec['monto'], 
                    $rec['descripcion'], 
                    $fecha_nueva, 
                    1, 
                    $rec['frecuencia']
                );
            }
            
            $procesadas[] = $key;
        }
    }
}
