<?php
require_once __DIR__ . '/../Config/database.php';
class UnidadModelo{
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

public function UnidadPorID($usuario_id){
    $sql = "SELECT uh.id, uh.codigo, uh.estado 
            FROM usuarios_unidades uu
            INNER JOIN unidades_habitacionales uh ON uu.unidad_id = uh.id
            WHERE uu.usuario_id = :usuario_id
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}

 
