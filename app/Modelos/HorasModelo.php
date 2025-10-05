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


}
