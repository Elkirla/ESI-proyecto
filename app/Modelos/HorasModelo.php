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

    public function guardarDeudasHorasCompletas($usuario_id, $deudas_semanales, $horas_totales_deuda, $primera_semana_pendiente) {
        try {
            $this->db->beginTransaction();

            // 1️⃣ Eliminar deudas semanales existentes
            $sql_delete = "DELETE FROM Semana_deudas WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql_delete);
            $stmt->execute([':usuario_id' => $usuario_id]);

            // 2️⃣ Insertar nuevas deudas semanales
            $sql_insert = "INSERT INTO Semana_deudas
                (usuario_id, fecha_inicio, fecha_fin, horas_trabajadas, horas_faltantes,
                horas_justificadas, horas_compensadas, motivo_justificacion, pago_compensatorio_id)
                VALUES
                (:usuario_id, :fecha_inicio, :fecha_fin, :horas_trabajadas, :horas_faltantes,
                :horas_justificadas, :horas_compensadas, :motivo_justificacion, :pago_compensatorio_id)";
            $stmt = $this->db->prepare($sql_insert);

            foreach ($deudas_semanales as $d) {
                $stmt->execute([
                    ':usuario_id' => $usuario_id,
                    ':fecha_inicio' => $d['fecha_inicio'],
                    ':fecha_fin' => $d['fecha_fin'],
                    ':horas_trabajadas' => $d['horas_trabajadas'],
                    ':horas_faltantes' => $d['horas_faltantes'],
                    ':horas_justificadas' => $d['horas_justificadas'],
                    ':horas_compensadas' => $d['horas_compensadas'],
                    ':motivo_justificacion' => $d['motivo_justificacion'],
                    ':pago_compensatorio_id' => $d['pago_compensatorio_id']
                ]);
            }

            // 3️⃣ Actualizar tabla Horas_deuda
            $sql_upsert = "INSERT INTO Horas_deuda
                (usuario_id, horas_acumuladas, horas_deuda_total, fecha_ultimo_calculo, primera_semana_pendiente)
                VALUES (:usuario_id, :horas_acumuladas, :horas_deuda_total, CURDATE(), :primera_semana_pendiente)
                ON DUPLICATE KEY UPDATE
                    horas_acumuladas = VALUES(horas_acumuladas),
                    horas_deuda_total = VALUES(horas_deuda_total),
                    fecha_ultimo_calculo = VALUES(fecha_ultimo_calculo),
                    primera_semana_pendiente = VALUES(primera_semana_pendiente)";
            $stmt = $this->db->prepare($sql_upsert);
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':horas_acumuladas' => $horas_totales_deuda,
                ':horas_deuda_total' => $horas_totales_deuda,
                ':primera_semana_pendiente' => $primera_semana_pendiente
            ]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("[MODELO_GUARDAR_DEUDAS_HORAS_ERROR] " . $e->getMessage());
            return false;
        }
    }


}
