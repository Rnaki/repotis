<?php

require_once 'sql.php';
require_once 'conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class AdministradorModel {
    private $conexion;
    private $datos;

    public function __construct() {
        $this->conexion = $this->establishConnection();
    }

    private function establishConnection() {
        return establishConnection(); // Ajusta según la implementación real
    }

    public function verDatos($data) {
        try {
            if ($this->conexion instanceof PDO) {
                $filas = 10;
                $pagina_actual = $data['pagina_actual'];
                $datos_pagina = ($pagina_actual - 1) * $filas;
                $sql = obtenerSentenciaSQL(); // Ajusta esto según tu implementación
                $stmt = $this->conexion->prepare($sql);

                if ($stmt) {
                    $stmt->bindParam(':filas', $filas, PDO::PARAM_INT);
                    $stmt->bindParam(':datos_pagina', $datos_pagina, PDO::PARAM_INT);
                    
                    $stmt->execute();
                    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $this->datos = [];

                    foreach ($resultados as $fila) {
                        $this->datos[] = [
                            'rut_usuario' => $fila['rut_usuario'],
                            'nombre_usuario' => $fila['nombre_usuario'],
                            'apellido_usuario' => $fila['apellido_usuario'],
                            'password_usuario' => $fila['password_usuario'],
                            'codigo_nfc_usuario' => $fila['codigo_nfc_usuario'],
                        ];
                    }

                    $jsonString = json_encode($this->datos);
                    return $jsonString;
                } 
            } else {
                throw new Exception('Error de conexión a la base de datos');
            }
        } catch (Exception $e) {
            $response = ['status' => 'error', 'message' => $e->getMessage()];
            return json_encode($response);
        }
    }

    
    public function verDatosEliminados($data) {
        try {
            if ($this->conexion instanceof PDO) {
                $filas = 10;
                $pagina_actual = $data['pagina_actual'];
                $datos_pagina = ($pagina_actual - 1) * $filas;
                $sql = obtenerdatosEliminados(); // Ajusta esto según tu implementación
                $stmt = $this->conexion->prepare($sql);
    
                if ($stmt) {
                    $stmt->bindParam(':filas', $filas, PDO::PARAM_INT);
                    $stmt->bindParam(':datos_pagina', $datos_pagina, PDO::PARAM_INT);
    
                    $stmt->execute();
                    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $this->datos = [];
    
                    foreach ($resultados as $fila) {
                        $this->datos[] = [
                            'rut_usuarioEliminado' => $fila['rut_usuario'],
                            'nombre_usuario' => $fila['nombre_usuario'],
                            'apellido_usuario' => $fila['apellido_usuario'],
                            'password_usuario' => $fila['password_usuario'],
                            'codigo_nfc_usuario' => $fila['codigo_nfc_usuario'],
                        ];
                    }
    
                    $jsonString = json_encode($this->datos);
                    return $jsonString;
                } else {
                    throw new Exception('Error en la preparación de la sentencia');
                }
            } else {
                throw new Exception('Error de conexión a la base de datos');
            }
        } catch (Exception $e) {
            $response = ['status' => 'error', 'message' => $e->getMessage()];
            return json_encode($response);
        }
    }

    public function mostrarUpdateDatos($data) {
        $sql = mostrarUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
        $stmt = $this->conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
    
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->execute();
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($fila) {
                // En lugar de 'echo', utiliza 'return' para enviar la respuesta al cliente
                return json_encode([
                    'success' => 1,
                    'rut_usuario' => $fila['rut_usuario'],
                    'nombre_usuario' => $fila['nombre_usuario'],
                    'apellido_usuario' => $fila['apellido_usuario']
                ]);
            } else {
                return json_encode(['success' => 0, 'error' => 'No se encontraron datos para el usuario']);
            }
        } catch (PDOException $e) {
            // En lugar de 'echo', utiliza 'return' para enviar la respuesta al cliente
            return json_encode(['success' => 0, 'error' => $e->getMessage()]);
        }
    }
    // Otros métodos del modelo

    public function updateDatos($data){
        $sql = sqlUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
        $stmt = $this->conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
        $nombre_usuario = $data["nombre_usuario"];
        $apellido_usuario = $data["apellido_usuario"];
    
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':apellido_usuario', $apellido_usuario, PDO::PARAM_STR);
            return $stmt->execute();

            // Construye la respuesta JSON
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    public function registrarDatos($data) {
        $sql = sqlregistrarDatos();
        $stmt = $this->conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
        $nombre_usuario = $data["nombre_usuario"];
        $apellido_usuario = $data["apellido_usuario"];
        $password_usuario = $data["password_usuario"];
        $confirmacion_password_usuario = $data["confirmacion_password_usuario"];
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':apellido_usuario', $apellido_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':password_usuario', $password_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':confirmacion_password_usuario', $confirmacion_password_usuario, PDO::PARAM_STR);
            $result = $stmt->execute();
    
            // Return the result as a JSON-encoded string
            return json_encode(['success' => 1, 'Estado' => 'Ingresado Correctamente']);
        } catch (PDOException $e) {
            // Return an error message as a JSON-encoded string
            return json_encode(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    //funcion que elimina el usuario seteando el valor de la columna en 0
    public function borrarUsuario($data) {
        $conexion = establishConnection();
        $sql = borrarUsuarioSQL();
        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $rut_usuario = $data["rut_usuario"];
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            try {
                $stmt->execute();
                $response = ['status' => 'success', 'message' => 'Registro exitoso'];
                echo json_encode($response);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function recuperarUsuarioEliminado($data) {
        $conexion = establishConnection();
        $sql = recuperarUsuarioEliminadoSql();
        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $rut_usuario = $data["rut_usuarioEliminado"];
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            try {
                $stmt->execute();
                $response = ['status' => 'success', 'message' => 'Registro exitoso'];
                echo json_encode($response);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    //Funcion de login del Administrador
    public function loginAdministrador($data){
        $sql = loginAdministrador();
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
        $rutAdministrador = $data["rutAdministrador"];
        $passwordAdministrador = $data["passwordAdministrador"];
        try {
            $stmt->bindParam(':rutAdministrador', $rutAdministrador, PDO::PARAM_STR);
            $stmt->bindParam(':passwordAdministrador', $passwordAdministrador, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Imprime el resultado
            echo $result['resultado'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }
    
}