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
        
}