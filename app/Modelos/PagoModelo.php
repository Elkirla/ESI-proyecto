<?php
require_once __DIR__ . '/../Config/database.php';

class PagoModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

public function registrarPago(pago $pago) {
    $sql = "INSERT INTO pagos_mensuales 
            (usuario_id, mes, monto, fecha, archivo_url, estado, entrega) 
            VALUES (:usuario_id, :mes, :monto, :fecha, :archivo_url, :estado, :entrega)";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':usuario_id' => $pago->getUsuarioId(),
        ':mes'        => $pago->getMes(),
        ':monto'      => $pago->getMonto(),   
        ':fecha'      => $pago->getFecha(),
        ':archivo_url'=> $pago->getArchivoUrl(),
        ':estado'     => $pago->getEstado(),
        ':entrega'    => $pago->getEntrega()
    ]);
}

public function registrarPagoCompensatorio(Pago $pago) {
    $sql = "INSERT INTO pagos_compensatorios 
            (usuario_id, monto, fecha, archivo_url, estado) 
            VALUES (:usuario_id, :monto, :fecha, :archivo_url, :estado)";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':usuario_id'  => $pago->getUsuarioId(),
        ':monto'       => $pago->getMonto(),
        ':fecha'       => $pago->getFecha(),
        ':archivo_url' => $pago->getArchivoUrl(),
        ':estado'      => $pago->getEstado()
    ]);
}

public function setFechaLimitePago($nuevaFecha) {
    $sql = "UPDATE configuracion SET valor = :fecha WHERE clave = 'fecha_limite_pago'";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':fecha' => $nuevaFecha]);
}

public function aprobarPago($pagoId) {
    $sql = "UPDATE pagos_mensuales SET estado = 'aprobado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}

public function rechazarPago($pagoId) {
    $sql = "UPDATE pagos_mensuales SET estado = 'rechazado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}

public function guardarDeudasMensualesCompletas($usuario_id, $deudas_mensuales, $meses_totales_deuda, $monto_total, $primer_mes_pendiente) {
    try {
        $this->db->beginTransaction();

        // Eliminar deudas mensuales anteriores
        $sql_delete = "DELETE FROM Deudas_Mensuales WHERE usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql_delete);
        $stmt->execute([':usuario_id' => $usuario_id]);

        $sql_insert = "INSERT INTO Deudas_Mensuales
            (usuario_id, correo, mes, fecha_inicio, fecha_fin, monto, adeudado, tiene_pago, procesado_en)
            VALUES
            (:usuario_id, :correo, :mes, :fecha_inicio, :fecha_fin, :monto, :adeudado, :tiene_pago, NOW())";

        $stmt = $this->db->prepare($sql_insert);

        foreach ($deudas_mensuales as $deuda) {
            $stmt->execute([
                ':usuario_id' => $deuda['usuario_id'],
                ':correo' => $deuda['correo'],
                ':mes' => $deuda['mes'],  
                ':fecha_inicio' => $deuda['fecha_inicio'],
                ':fecha_fin' => $deuda['fecha_fin'],
                ':monto' => $deuda['monto'],
                ':adeudado' => $deuda['adeudado'],
                ':tiene_pago' => $deuda['tiene_pago']
            ]);
        }

        // ctualizar tabla Pagos_Deudas
        $sql_upsert = "INSERT INTO Pagos_Deudas
            (fecha, usuario_id, correo, meses, monto, primer_mes_pendiente)
            VALUES (CURDATE(), :usuario_id, :correo, :meses, :monto, :primer_mes_pendiente)
            ON DUPLICATE KEY UPDATE
                fecha = VALUES(fecha),
                correo = VALUES(correo),
                meses = VALUES(meses),
                monto = VALUES(monto),
                primer_mes_pendiente = VALUES(primer_mes_pendiente)";

        $stmt = $this->db->prepare($sql_upsert);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':correo' => $deudas_mensuales[0]['correo'] ?? '',
            ':meses' => $meses_totales_deuda,
            ':monto' => $monto_total,
            ':primer_mes_pendiente' => $primer_mes_pendiente
        ]);

        $this->db->commit();
        return true;

    } catch (Exception $e) {
        $this->db->rollBack();
        error_log("[MODELO_GUARDAR_DEUDAS_MENSUALES_ERROR] " . $e->getMessage());
        return false;
    }
}
public function existePagoPendienteOAprobado($usuario_id, $mes) {
    $sql = "SELECT COUNT(*) FROM pagos_mensuales 
            WHERE usuario_id = :usuario_id 
            AND mes = :mes 
            AND estado IN ('pendiente', 'aprobado')";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':mes'        => $mes
    ]);
    return $stmt->fetchColumn() > 0;
}
}