<?php
class justificativo{
    public $usuario_id;
    public $fecha;  
    public $motivo;   
    public $archivo_url;
    public $estado;

    public function __construct($usuario_id = null, $fecha = null, $motivo = null, $archivo_url = null, $estado = 'pendiente') {
        $this->usuario_id = $usuario_id;
        $this->fecha = $fecha;
        $this->motivo = $motivo;
        $this->archivo_url = $archivo_url;
        $this->estado = $estado;
    }

    public function getUsuarioId() { return $this->usuario_id; }
    public function getFecha() { return $this->fecha; }
    public function getMotivo() { return $this->motivo; }
    public function getArchivoUrl() { return $this->archivo_url; }
    public function getEstado() { return $this->estado; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setMotivo($motivo) { $this->motivo = $motivo; }
    public function setArchivoUrl($archivo_url) { $this->archivo_url = $archivo_url; }
    public function setEstado($estado) { $this->estado = $estado; }

}