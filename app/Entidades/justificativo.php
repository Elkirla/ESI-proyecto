<?php
class justificativo{
    public $usuario_id;
    public $fecha;  
    public $fecha_final;
    public $horas;
    public $motivo;   
    public $archivo_url;
    public $estado;

    public function __construct($usuario_id = null, $fecha = null, $fecha_final = null, $horas = null, $motivo = null, $archivo_url = null, $estado = 'pendiente') {
        $this->usuario_id = $usuario_id;
        $this->fecha = $fecha;
        $this->fecha_final = $fecha_final;
        $this->horas = $horas;
        $this->motivo = $motivo;
        $this->archivo_url = $archivo_url;
        $this->estado = $estado;
    }

    public function getUsuarioId() { return $this->usuario_id; }
    public function getFecha() { return $this->fecha; }
    public function getFechaFinal() { return $this->fecha_final; }
    public function getHoras() { return $this->horas; }
    public function getMotivo() { return $this->motivo; }
    public function getArchivoUrl() { return $this->archivo_url; }
    public function getEstado() { return $this->estado; }
    
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setFechaFinal($fecha_final) { $this->fecha_final = $fecha_final; }
    public function setHoras($horas) { $this->horas = $horas; }
    public function setMotivo($motivo) { $this->motivo = $motivo; }
    public function setArchivoUrl($archivo_url) { $this->archivo_url = $archivo_url; }
    public function setEstado($estado) { $this->estado = $estado; }

}