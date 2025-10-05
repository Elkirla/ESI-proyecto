<?php
require_once __DIR__ . '/../Config/database.php';

class NotiModelo{
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function NotiLeidasUsuario($usuario_id) {
    $sql = "UPDATE notificaciones SET leido = 1 WHERE usuario_id = :usuario_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':usuario_id' => $usuario_id]);
    }


    public function InsertarNoti($usuario_id, $mensaje) {
    $sql = "INSERT INTO notificaciones (usuario_id, mensaje) VALUES (:usuario_id, :mensaje)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':mensaje'    => $mensaje
    ]);
}

}