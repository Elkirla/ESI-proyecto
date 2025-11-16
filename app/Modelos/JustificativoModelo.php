<?php
require_once __DIR__ . '/../Config/database.php';

class JustificativoModelo{
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

        public function registrarJustificativo(justificativo $justificativo) {
        $sql = "INSERT INTO justificativos (usuario_id, fecha, fecha_final, motivo, archivo_url, estado) 
                VALUES (:usuario_id, :fecha, :fecha_final, :motivo, :archivo_url, :estado)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $justificativo->getUsuarioId(),
            ':fecha' => $justificativo->getFecha(),
            ':fecha_final' => $justificativo->getFechaFinal(),
            ':motivo' => $justificativo->getMotivo(),
            ':archivo_url' => $justificativo->getArchivoUrl(),
            ':estado' => $justificativo->getEstado()
        ]);
    }
    public function aceptarJustificativo($id) {
        $sql = "UPDATE justificativos SET estado = 'aceptado' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function rechazarJustificativo($id) {
        $sql = "UPDATE justificativos SET estado = 'rechazado' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    } 
}