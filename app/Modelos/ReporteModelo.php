<?php
require_once __DIR__ . '/../Config/database.php';
class ReporteModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerHorasPorUsuario($usuario_id) {
        $stmt = $this->db->prepare("SELECT fecha, horas FROM horas_trabajadas WHERE usuario_id = :usuario_id ORDER BY fecha DESC");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>