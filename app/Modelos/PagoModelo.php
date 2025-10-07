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

public function registrarPagoCompensatorio(Pago $pago) {
    $sql = "INSERT INTO pagos_compensatorios 
            (usuario_id, monto, fecha, archivo_url, estado) 
            VALUES (:usuario_id, :monto, :fecha, :archivo_url, :estado)";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':usuario_id'  => $pago->getUsuarioId(),
        ':monto'       => $pago->getMonto(),
        ':fecha'       => $pago->getFecha(),
        ':archivo_url' => $pago->getArchivoUrl(),
        ':estado'      => $pago->getEstado()
    ]);
}

public function setFechaLimitePago($nuevaFecha) {
    $sql = "UPDATE configuracion SET valor = :fecha WHERE clave = 'fecha_limite_pago'";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':fecha' => $nuevaFecha]);
}

public function aprobarPago($pagoId) {
    $sql = "UPDATE pagos_mensuales SET estado = 'aprobado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}

public function rechazarPago($pagoId) {
    $sql = "UPDATE pagos_mensuales SET estado = 'rechazado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}

public function aprobarPagoCompensatorio($pagoId) {
    $sql = "UPDATE pagos_compensatorios SET estado = 'aprobado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}

public function rechazarPagoCompensatorio($pagoId) {
    $sql = "UPDATE pagos_compensatorios SET estado = 'rechazado' WHERE id = :pagoId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':pagoId' => $pagoId]);
    return $stmt->rowCount() > 0;
}
}