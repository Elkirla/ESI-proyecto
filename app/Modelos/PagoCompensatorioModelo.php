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

public function aprobarPagoCompensatorio($pagoId) { 
    try {
        // Primero obtener los datos del pago
        $sql = "SELECT usuario_id FROM pagos_compensatorios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $pagoId]);
        $pago = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pago) {
            error_log("❌ No se encontró registro en pagos_compensatorios con ID: $pagoId");
            return false;
        }

        // Luego actualizar el estado
        $sql2 = "UPDATE pagos_compensatorios SET estado = 'aprobado' WHERE id = :id";
        $stmt2 = $this->db->prepare($sql2);

        if (!$stmt2->execute([':id' => $pagoId])) {
            $errorInfo = $stmt2->errorInfo();
            error_log("❌ Error SQL al actualizar pago compensatorio $pagoId: " . implode(" | ", $errorInfo));
            return false;
        }

        if ($stmt2->rowCount() === 0) {
            error_log("⚠ UPDATE ejecutado pero sin cambios en DB para pagoCompensatorio $pagoId");
            return false; // Esto es importante - si no hay filas afectadas, retornar false
        }

        error_log("✅ Pago compensatorio $pagoId aprobado correctamente");
        
        // Retornar TODOS los datos necesarios, igual que en pagos mensuales
        return [
            'usuario_id' => $pago['usuario_id'],
            'status' => 'aprobado'
        ];

    } catch (Exception $e) {
        error_log("🔥 Excepción al aprobar pago compensatorio: " . $e->getMessage());
        return false;
    }
}

public function rechazarPagoCompensatorio($pagoId) {
    try {
        $sql = "SELECT usuario_id FROM pagos_compensatorios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $pagoId]);
        $pago = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pago) {
            error_log("❌ No se encontró registro en pagos_compensatorios con ID: $pagoId");
            return false;
        }

        $sql2 = "UPDATE pagos_compensatorios SET estado = 'rechazado' WHERE id = :id";
        $stmt2 = $this->db->prepare($sql2);

        if (!$stmt2->execute([':id' => $pagoId])) {
            $errorInfo = $stmt2->errorInfo();
            error_log("❌ Error SQL al rechazar pago compensatorio $pagoId: " . implode(" | ", $errorInfo));
            return false;
        }

        if ($stmt2->rowCount() === 0) {
            error_log("⚠ UPDATE sin cambios para pagoCompensatorio $pagoId");
            return false; // Importante: retornar false si no hay cambios
        }

        error_log("✅ Pago compensatorio $pagoId rechazado correctamente");

        return [
            'usuario_id' => $pago['usuario_id'],
            'status' => 'rechazado'
        ];

    } catch (Exception $e) {
        error_log("🔥 Excepción al rechazar pago compensatorio: " . $e->getMessage());
        return false;
    }
}
}
