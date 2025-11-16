<?php
require_once __DIR__ . '/../Config/database.php';

class UnidadModelo {

    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
 
    public function existeCodigo($codigo) {
        $sql = "SELECT id FROM unidades_habitacionales WHERE codigo = :codigo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
 
    public function crearUnidad($codigo, $estado) {
        if ($this->existeCodigo($codigo)) {
            return "Ya existe una unidad con ese código";
        }

        $sql = "INSERT INTO unidades_habitacionales (codigo, estado)
                VALUES (:codigo, :estado)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':estado', $estado);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM unidades_habitacionales ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function actualizarEstado($unidadID, $estado) {
        $sql = "UPDATE unidades_habitacionales 
                SET estado = :estado 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $unidadID, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
 
    public function UnidadPorID($usuario_id) {
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
    public function tieneUsuariosAsignados($unidadID) {
    $sql = "SELECT COUNT(*) AS total 
            FROM usuarios_unidades 
            WHERE unidad_id = :unidad_id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(":unidad_id", $unidadID, PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['total'] > 0;
}

    public function eliminarUnidad($unidadID) {
    $sql = "DELETE FROM unidades_habitacionales WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $unidadID, PDO::PARAM_INT);

    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

}
