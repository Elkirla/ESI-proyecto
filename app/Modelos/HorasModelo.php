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
        // Verificar conexión a la base de datos
        if (!$this->db) {
            throw new Exception("Conexión a la base de datos no inicializada.");
        }
        
        $this->db->beginTransaction();

        // DEBUG: Verificar datos de entrada
        error_log("[DEBUG_MODELO] Usuario: $usuario_id, Total de semanas a guardar: " . count($deudas_semanales));
        error_log("[DEBUG_MODELO] Horas totales deuda: $horas_totales_deuda, Primera semana pendiente: $primera_semana_pendiente");
        
        foreach ($deudas_semanales as $index => $deuda) {
            error_log("[DEBUG_MODELO] Deuda $index - Inicio: {$deuda['fecha_inicio']}, Fin: {$deuda['fecha_fin']}, Horas faltantes: {$deuda['horas_faltantes']}");
        }

        // ELIMINAR deudas existentes para este usuario (enfoque más robusto)
        $sql_delete = "DELETE FROM Semana_deudas WHERE usuario_id = :usuario_id";
        $stmt_delete = $this->db->prepare($sql_delete);
        $stmt_delete->execute([':usuario_id' => $usuario_id]);
        
        error_log("[DEBUG_MODELO] Deudas anteriores eliminadas para usuario: $usuario_id");

        // INSERTAR nuevas deudas (sin eliminar semana actual)
        if (!empty($deudas_semanales)) {
            $sql_insert = "INSERT INTO Semana_deudas 
                (usuario_id, fecha_inicio, fecha_fin, horas_trabajadas, horas_faltantes,
                horas_justificadas, horas_compensadas, motivo_justificacion, pago_compensatorio_id, procesado_en)
                VALUES
                (:usuario_id, :fecha_inicio, :fecha_fin, :horas_trabajadas, :horas_faltantes,
                :horas_justificadas, :horas_compensadas, :motivo_justificacion, :pago_compensatorio_id, NOW())";

            $stmt_insert = $this->db->prepare($sql_insert);

            foreach ($deudas_semanales as $d) {
                error_log("[DEBUG_MODELO] Intentando INSERT para semana: " . $d['fecha_inicio']);

                $success = $stmt_insert->execute([
                    ':usuario_id' => $usuario_id,
                    ':fecha_inicio' => $d['fecha_inicio'],
                    ':fecha_fin' => $d['fecha_fin'],
                    ':horas_trabajadas' => $d['horas_trabajadas'] ?? 0,
                    ':horas_faltantes' => $d['horas_faltantes'] ?? 0,
                    ':horas_justificadas' => $d['horas_justificadas'] ?? 0,
                    ':horas_compensadas' => $d['horas_compensadas'] ?? 0,
                    ':motivo_justificacion' => $d['motivo_justificacion'] ?? null,
                    ':pago_compensatorio_id' => $d['pago_compensatorio_id'] ?? null
                ]);

                if (!$success) {
                    $errorInfo = $stmt_insert->errorInfo();
                    error_log("[ERROR_INSERT_SEMANA] Detalles: " . print_r($errorInfo, true));
                    throw new Exception("Fallo en la inserción de Semana_deudas para fecha {$d['fecha_inicio']}: {$errorInfo[2]}");
                }
                
                error_log("[DEBUG_MODELO] Semana {$d['fecha_inicio']} guardada exitosamente. Filas afectadas: " . $stmt_insert->rowCount());
            }
            
            error_log("[DEBUG_MODELO] Total de semanas insertadas: " . count($deudas_semanales));
        } else {
            error_log("[DEBUG_MODELO] No hay deudas semanales para insertar");
        }

        // UPSERT en Horas_deuda
        $sql_upsert = "INSERT INTO Horas_deuda 
            (usuario_id, horas_acumuladas, horas_deuda_total, fecha_ultimo_calculo, primera_semana_pendiente)
            VALUES (:usuario_id, 0, :horas_deuda_total, CURDATE(), :primera_semana_pendiente)
            ON DUPLICATE KEY UPDATE
                horas_acumuladas = 0,
                horas_deuda_total = VALUES(horas_deuda_total),
                fecha_ultimo_calculo = VALUES(fecha_ultimo_calculo),
                primera_semana_pendiente = VALUES(primera_semana_pendiente)";

        $stmt_upsert = $this->db->prepare($sql_upsert);
        $stmt_upsert->execute([
            ':usuario_id' => $usuario_id,
            ':horas_deuda_total' => $horas_totales_deuda,
            ':primera_semana_pendiente' => $primera_semana_pendiente
        ]);
        
        error_log("[DEBUG_MODELO] Horas_deuda actualizada para usuario: $usuario_id");
        
        // Confirmar transacción
        $this->db->commit();
        error_log("[DEBUG_MODELO] Transacción COMMIT exitosa para usuario: $usuario_id"); 
        
        return true;

    } catch (Exception $e) {
        // Rollback en caso de error
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
            error_log("[DEBUG_MODELO] Transacción ROLLBACK por error");
        }
        error_log("[GUARDAR_DEUDAS_HORAS_ERROR] ".$e->getMessage());
        return false;
    }
}

public function obtenerHorasFaltantesSemana($usuario_id, $fecha_inicio, $fecha_fin){
$sql = "SELECT horas_faltantes
        FROM Semana_deudas
        WHERE usuario_id = :usuario_id
        AND fecha_inicio >= :fecha_inicio
        AND fecha_fin <= :fecha_fin
        ORDER BY id DESC
        LIMIT 1";


    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_fin' => $fecha_fin
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado ? (float)$resultado['horas_faltantes'] : 0;
}


}
