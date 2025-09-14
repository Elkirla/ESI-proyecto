<?php
class pago {
    public $id;
    public $usuario_id;
    public $mes;         
    public $fecha;
    public $archivo_url;
    public $estado;       

    public function __construct($usuario_id = null, $mes = null, $fecha = null, $archivo_url = null, $estado = 'pendiente') {
        $this->usuario_id = $usuario_id;
        $this->mes = $mes;
        $this->fecha = $fecha;
        $this->archivo_url = $archivo_url;
        $this->estado = $estado;
    }

    // Getters y Setters
    public function getUsuarioId() { return $this->usuario_id; }
    public function getMes() { return $this->mes; }
    public function getFecha() { return $this->fecha; }
    public function getArchivoUrl() { return $this->archivo_url; }
    public function getEstado() { return $this->estado; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function setMes($mes) { $this->mes = $mes; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setArchivoUrl($archivo_url) { $this->archivo_url = $archivo_url; }
    public function setEstado($estado)  {$this->estado = $estado;}
    
}
