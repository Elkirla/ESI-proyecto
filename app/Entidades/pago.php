<?php
class pago {
    private $usuario_id;
    private $mes;
    private $monto;   
    private $fecha;
    private $archivo_url;
    private $estado;
    private $entrega;

    public function __construct($usuario_id, $mes, $monto, $fecha, $archivo_url, $estado, $entrega) {
        $this->usuario_id = $usuario_id;
        $this->mes = $mes;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->archivo_url = $archivo_url;
        $this->estado = $estado;
        $this->entrega = $entrega;
    }

    public function getUsuarioId() { return $this->usuario_id; }
    public function getMes() { return $this->mes; }
    public function getMonto() { return $this->monto; } 
    public function getFecha() { return $this->fecha; }
    public function getArchivoUrl() { return $this->archivo_url; }
    public function getEstado() { return $this->estado; }
    public function getEntrega() { return $this->entrega; }
}
