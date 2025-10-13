<?php
require_once __DIR__ . '/../Config/database.php';
class PagoCompensatorioModelo {
    private $db;

    public function __construct() { 
        $this->db = Database::getConnection();
    }

    public function insertar(PagoCompensatorio $pago) {
        $sql = "INSERT INTO pagos_compensatorios (usuario_id, monto, fecha, horas, archivo_url, estado)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $pago->usuario_id,
            $pago->monto,
            $pago->fecha,
            $pago->horas,
            $pago->archivo_url,
            $pago->estado
        ]);
    }

    public function obtenerPorUsuarioYSemana($usuario_id, $fecha_inicio, $fecha_fin) {
        $sql = "SELECT * FROM pagos_compensatorios 
                WHERE usuario_id = ? AND fecha BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $fecha_inicio, $fecha_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
