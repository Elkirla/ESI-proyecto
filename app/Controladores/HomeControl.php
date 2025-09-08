<?php
class HomeControl {
    public function index() {
        include __DIR__ . "/../Vistas/landing.php";
    }

    public function normas() {
        include __DIR__ . "/../Vistas/normas.php";
    }
}
