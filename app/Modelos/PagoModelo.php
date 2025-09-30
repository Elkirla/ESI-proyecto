<?php
require_once __DIR__ . '/../Config/database.php';

class PagoModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

public function registrarPago(pago $pago) {
    $sql = "INSERT INTO pagos_mensuales 
            (usuario_id, mes, monto, fecha, archivo_url, estado, entrega) 
            VALUES (:usuario_id, :mes, :monto, :fecha, :archivo_url, :estado, :entrega)";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':usuario_id' => $pago->getUsuarioId(),
        ':mes'        => $pago->getMes(),
        ':monto'      => $pago->getMonto(),   
        ':fecha'      => $pago->getFecha(),
        ':archivo_url'=> $pago->getArchivoUrl(),
        ':estado'     => $pago->getEstado(),
        ':entrega'    => $pago->getEntrega()
    ]);
}
public function getFechaLimitePago() {
    $sql = "SELECT valor FROM configuracion WHERE clave = 'fecha_limite_pago' LIMIT 1";
    $stmt = $this->db->query($sql);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado ? $resultado['valor'] : null;
}

public function setFechaLimitePago($nuevaFecha) {
    $sql = "UPDATE configuracion SET valor = :fecha WHERE clave = 'fecha_limite_pago'";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':fecha' => $nuevaFecha]);
}


}