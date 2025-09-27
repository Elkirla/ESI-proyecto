<?php
require_once __DIR__ . '/../Config/database.php';

class PagoModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

 public function registrarPago(pago $pago) {
    $sql = "INSERT INTO pagos_mensuales 
            (usuario_id, mes, monto, fecha, archivo_url, estado) 
            VALUES (:usuario_id, :mes, :monto, :fecha, :archivo_url, :estado)";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':usuario_id' => $pago->getUsuarioId(),
        ':mes'        => $pago->getMes(),
        ':monto'      => $pago->getMonto(),   
        ':fecha'      => $pago->getFecha(),
        ':archivo_url'=> $pago->getArchivoUrl(),
        ':estado'     => $pago->getEstado()
    ]);
}

}