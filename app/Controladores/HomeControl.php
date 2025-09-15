<?php
class HomeControl {

    public function index() {
        include __DIR__ . "/../Vistas/landing.php";
    }

    public function normas() {
        include __DIR__ . "/../Vistas/normas.php";
    }

    public function exitoregistro() {
        include __DIR__ . "/../Vistas/exitoregistro.php";
    }

    public function dashboardAdmin(){
        $this->verificarSesion('admin');
        include __DIR__ . "/../Vistas/backoffice.php";
    }  

    public function dashboardUsuario(){
        $this->verificarSesion();
        include __DIR__ . "/../Vistas/dashboard.php";
    }  
        private function verificarSesion($rolRequerido = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Si se especifica un rol requerido, verificarlo
        if ($rolRequerido && (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rolRequerido)) {
            header('Location: /login');
            exit;
        }
        
        return true;
    }    
}
?>