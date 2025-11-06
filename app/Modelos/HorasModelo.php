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
        // Asegúrate de que $this->db existe y está conectado
        if (!$this->db) {
            throw new Exception("Conexión a la base de datos no inicializada.");
        }
        
        $this->db->beginTransaction();

        $ultima_semana = end($deudas_semanales);
        if ($ultima_semana && $ultima_semana['fecha_fin'] >= date('Y-m-d', strtotime('monday this week'))) {
            array_pop($deudas_semanales);
        }

        if (!empty($deudas_semanales)) {
            // ... (sql_insert es el mismo)
            $sql_insert = "INSERT INTO Semana_deudas 
                (usuario_id, fecha_inicio, fecha_fin, horas_trabajadas, horas_faltantes,
                horas_justificadas, horas_compensadas, motivo_justificacion, pago_compensatorio_id, procesado_en)
                VALUES
                (:usuario_id, :fecha_inicio, :fecha_fin, :horas_trabajadas, :horas_faltantes,
                :horas_justificadas, :horas_compensadas, :motivo_justificacion, :pago_compensatorio_id, NOW())
                ON DUPLICATE KEY UPDATE
                    fecha_fin = VALUES(fecha_fin),
                    horas_trabajadas = VALUES(horas_trabajadas),
                    horas_faltantes = VALUES(horas_faltantes),
                    horas_justificadas = VALUES(horas_justificadas),
                    horas_compensadas = VALUES(horas_compensadas),
                    motivo_justificacion = VALUES(motivo_justificacion),
                    pago_compensatorio_id = VALUES(pago_compensatorio_id),
                    procesado_en = VALUES(procesado_en)";

            $stmt = $this->db->prepare($sql_insert);

            foreach ($deudas_semanales as $d) {
                // Validación para ver los datos que se están intentando insertar
                error_log("[DEBUG_DEUDA] Intentando insertar/actualizar: " . print_r($d, true));

                // Ejecuta la sentencia
                $success = $stmt->execute([
                    ':usuario_id' => $usuario_id,
                    ':fecha_inicio' => $d['fecha_inicio'],
                    ':fecha_fin' => $d['fecha_fin'],
                    ':horas_trabajadas' => $d['horas_trabajadas'] ?? 0, // Añadir valor por defecto si pueden ser NULL
                    ':horas_faltantes' => $d['horas_faltantes'] ?? 0,
                    // ... (resto de parámetros)
                    ':horas_justificadas' => $d['horas_justificadas'] ?? null,
                    ':horas_compensadas' => $d['horas_compensadas'] ?? null,
                    ':motivo_justificacion' => $d['motivo_justificacion'] ?? null,
                    ':pago_compensatorio_id' => $d['pago_compensatorio_id'] ?? null
                ]);

                // Si PDO no lanza excepciones, esta verificación es CRUCIAL.
                if (!$success) {
                    error_log("[GUARDAR_DEUDAS_FALLO_EXECUTE] Detalles: " . print_r($stmt->errorInfo(), true));
                    throw new Exception("Fallo en la inserción/actualización de Semana_deudas.");
                }
            }
        }
        
        // ... (sql_upsert para Horas_deuda es el mismo)
        $sql_upsert = "INSERT INTO Horas_deuda 
            (usuario_id, horas_acumuladas, horas_deuda_total, fecha_ultimo_calculo, primera_semana_pendiente)
            VALUES (:usuario_id, 0, :horas_deuda_total, CURDATE(), :primera_semana_pendiente)
            ON DUPLICATE KEY UPDATE
                horas_acumuladas = 0,
                horas_deuda_total = VALUES(horas_deuda_total),
                fecha_ultimo_calculo = VALUES(fecha_ultimo_calculo),
                primera_semana_pendiente = VALUES(primera_semana_pendiente)";

        $stmt = $this->db->prepare($sql_upsert);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':horas_deuda_total' => $horas_totales_deuda,
            ':primera_semana_pendiente' => $primera_semana_pendiente
        ]);

        $this->db->commit();
        return true;

    } catch (Exception $e) {
        // El rollback se ejecuta si se lanza cualquier excepción.
        if ($this->db->inTransaction()) {
             $this->db->rollBack();
        }
        error_log("[GUARDAR_DEUDAS_ERROR] ".$e->getMessage());
        return false;
    }
}



}
