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
public function getFechaLimitePago() {
    $sql = "SELECT valor FROM configuracion WHERE clave = 'fecha_limite_pago'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? intval($result['valor']) : 10; // Valor por defecto
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

public function IngresarPagoDeuda($datos) {
    try {
        // 1. Eliminar cálculo anterior
        $sql_delete = "DELETE FROM Pagos_Deudas WHERE usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql_delete);
        $stmt->execute([':usuario_id' => $datos['usuario_id']]);

        // 2. Insertar nuevo cálculo
        $sql_insert = "INSERT INTO Pagos_Deudas (fecha, usuario_id, correo, meses, monto)
        VALUES (:fecha, :usuario_id, :correo, :meses, :monto)";
        $stmt = $this->db->prepare($sql_insert);
        return $stmt->execute([
            ':fecha'      => $datos['fecha'],
            ':usuario_id' => $datos['usuario_id'],
            ':correo'     => $datos['correo'],
            ':meses'      => $datos['meses'],
            ':monto'      => $datos['monto']
        ]);

    } catch (Exception $e) {
        error_log("[MODELO_DEUDA_ERROR] " . $e->getMessage());
        return false;
    }
}

}