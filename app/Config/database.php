<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Valores hardcodeados temporalmente
            $host = "db";           // Nombre del servicio en docker-compose
            $database = "cooperativa";
            $username = "usuariodb";
            $password = "password";
            
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$database;charset=utf8mb4", 
                $username, 
                $password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            // Mensaje más amigable para producción
            error_log("Error de base de datos: " . $e->getMessage());
            die("Error de conexión con la base de datos. Por favor, contacte al administrador.");
        }
    }

    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
?>