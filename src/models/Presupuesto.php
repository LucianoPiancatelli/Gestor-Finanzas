<?php

class Presupuesto {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }

    public function getAllWithStatus($usuario_id, $mes, $anio) {
        // Obtenemos los presupuestos y calculamos cuánto se ha gastado de esa categoría en ese mes.
        $query = "
            SELECT 
                p.id, 
                p.categoria_id, 
                c.nombre as categoria_nombre, 
                p.monto_limite, 
                COALESCE(SUM(t.monto), 0) as gastado
            FROM presupuestos p
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN transacciones t ON t.categoria_id = p.categoria_id 
                AND t.usuario_id = p.usuario_id 
                AND MONTH(t.fecha) = :mes1 
                AND YEAR(t.fecha) = :anio1
            WHERE p.usuario_id = :usuario_id AND p.mes = :mes2 AND p.anio = :anio2
            GROUP BY p.id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':usuario_id' => $usuario_id, 
            ':mes1' => $mes, 
            ':anio1' => $anio,
            ':mes2' => $mes, 
            ':anio2' => $anio
        ]);
        return $stmt->fetchAll();
    }

    public function createOrUpdate($usuario_id, $categoria_id, $monto_limite, $mes, $anio) {
        $query = "
            INSERT INTO presupuestos (usuario_id, categoria_id, monto_limite, mes, anio)
            VALUES (:usuario_id, :categoria_id, :monto_limite, :mes, :anio)
            ON DUPLICATE KEY UPDATE monto_limite = :monto_limite2
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':categoria_id' => $categoria_id,
            ':monto_limite' => $monto_limite,
            ':mes' => $mes,
            ':anio' => $anio,
            ':monto_limite2' => $monto_limite
        ]);
    }

    public function delete($id, $usuario_id) {
        $stmt = $this->db->prepare("DELETE FROM presupuestos WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    }
}
