<?php
require_once __DIR__ . '/../Config/database.php';

class HorasModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function registrarHoras(Hora $hora) {
        $sql = "INSERT INTO horas_trabajadas (usuario_id, fecha, horas) 
                VALUES (:usuario_id, :fecha, :horas)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $hora->getUsuarioId(),
            ':fecha' => $hora->getFecha(),
            ':horas' => $hora->getHoras()
        ]);
    }
public function tieneHorasRegistradas($usuario_id, $fecha) {
    $sql = "SELECT 1 FROM horas_trabajadas 
            WHERE usuario_id = :usuario_id AND fecha = :fecha LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':fecha'      => $fecha
    ]);
    return $stmt->fetchColumn() !== false;
    }

  public function registrarPagoCompensatorio(Pago $pago) {
        try {
            $sql = "INSERT INTO pagos_compensatorios (usuario_id, monto, fecha, archivo_url, estado) 
                    VALUES (:usuario_id, :monto, :fecha, :archivo_url, :estado)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':usuario_id', $pago->getUsuarioId(), PDO::PARAM_INT);
            $stmt->bindValue(':monto', $pago->getMonto());
            $stmt->bindValue(':fecha', $pago->getFecha());
            $stmt->bindValue(':archivo_url', $pago->getArchivoUrl());
            $stmt->bindValue(':estado', $pago->getEstado());

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[DB_ERROR] " . $e->getMessage());
            return false;
        }
    }
}
