<?php

class Connection {
    private static $instanciaConexion;
    private $conexion;  // Definir la propiedad aquí

    private function __construct() {
        // Database credentials
        $servername = "190.114.253.43";
        $username = "unap";
        $password = "unap123!";
        $dbname = "sistemarobusto";

        try {
            $this->conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia() {
        if (!isset(self::$instanciaConexion)) {
            self::$instanciaConexion = new self();
        }
        return self::$instanciaConexion;
    }

    public function obtenerConexion() {
        return $this->conexion;
    }

    public function cerrarConexion() {
        $this->conexion = null;
    }
}



?>
