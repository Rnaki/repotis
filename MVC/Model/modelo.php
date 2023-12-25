<?php

require_once 'sql.php';
require_once 'conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class AdministradorModel {
    private $datos;
    private $conexion;
    private $sentenciasSQLManager;

    public function __construct() {
        $this->conexion = Connection::obtenerInstancia()->obtenerConexion();
        $this->sentenciasSQLManager = new SentenciasSQLManager();
    }



    public function verDatos($data) {
        try {
            if ($this->conexion instanceof PDO) {
                $filas = 10;
                $pagina_actual = $data['pagina_actual'];
                $datos_pagina = ($pagina_actual - 1) * $filas;
                $sql = $this->sentenciasSQLManager->verDatos(); // Ajusta esto según tu implementación
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
                $sql = $this->sentenciasSQLManager->obtenerdatosEliminados(); // Ajusta esto según tu implementación
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
        $sql = $this->sentenciasSQLManager->mostrarUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
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
        $sql = $this->sentenciasSQLManager->sqlUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
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
        

        $this->conexion->beginTransaction(); 
        try {
            $sql = $this->sentenciasSQLManager->sqlregistrarDatos();
            $stmt = $this->conexion->prepare($sql);
            $rut_usuario = $data["rut_usuario"];
            $nombre_usuario = $data["nombre_usuario"];
            $apellido_usuario = $data["apellido_usuario"];
            $password_usuario = $data["password_usuario"];
            $confirmacion_password_usuario = $data["confirmacion_password_usuario"];
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':apellido_usuario', $apellido_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':password_usuario', $password_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':confirmacion_password_usuario', $confirmacion_password_usuario, PDO::PARAM_STR);
            $result = $stmt->execute();
    
            $rowCount = $stmt->rowCount();

        if ($rowCount === 0) {
            // Rollback the transaction in case of duplicate key error
            $this->conexion->rollBack();
            return json_encode(['error' => 2, 'Error' => 'Duplicate key violation']);
        } else {
            // Commit the transaction on success
            $this->conexion->commit();
            return json_encode(['success' => 1, 'Estado' => 'Ingresado Correctamente']);
        }
    } catch (PDOException $e) {
        // Rollback the transaction on any other error
        $this->conexion->rollBack();
        return json_encode(['error' => 2, 'motivo: ' . $e->getMessage()]);
    }
}
    //funcion que elimina el usuario seteando el valor de la columna en 0
    public function borrarUsuario($data) {
        $sql = $this->sentenciasSQLManager->borrarUsuarioSQL();
        $stmt = $this->conexion->prepare($sql);
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
        $sql = $this->sentenciasSQLManager->recuperarUsuarioEliminadoSql();
        $stmt = $this->conexion->prepare($sql);
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
        $sql = $this->sentenciasSQLManager->loginAdministrador();
        $stmt = $this->conexion->prepare($sql);
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

        //Funcion que prepara el ingreso de la nfc según el usuario seleccionado//
        public function preparaNFC($data){
            session_start(); // Inicia la sesión
    
            // Accede a la variable de sesión
            $rut_administrador = isset($_SESSION['rut']) ? $_SESSION['rut'] : null;
    
            $rut_usuario = $data["rut_usuario"];
            $sql = $this->sentenciasSQLManager->preparaNFC();
            $sql2 = $this->sentenciasSQLManager->ControladorLogin(); 
            try {
                $this->conexion->beginTransaction();
                $stmt = $this->conexion->prepare($sql);
                $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                $stmt->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
                $resultado =$stmt->execute();
                $stmt = $this->conexion->prepare($sql2);
                $stmt->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
                $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                $stmt->execute();
                echo $this->conexion->commit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        public function leerNfcAdministrador($data) {
            $sql = $this->sentenciasSQLManager->leerNfcAdm();  // Asegúrate de tener esta función definida
        
            $stmt = $this->conexion->prepare($sql);
            $rut_usuario = $data["rut_usuario"];
            $time = 0;
            $bell = NULL;
            try {
                while ($time <= 30) {
                    $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Verificar el resultado de la consulta
                    if ($result['resultado'] == 1) {
                        $bell = $result['resultado'];
                        // Imprime el resultado y sale del bucle
                        echo $result['resultado'];
                        break;
                    }else if ($result['resultado'] == 2){
                        $bell = $result['resultado'];
                        echo $result['resultado'];
                        break;
                    }
                    sleep(1);
                    $time++;
                }
                if ($bell != 1 && $bell != 2){
                    echo 0;
                }
                // Si el bucle termina sin encontrar un resultado, imprime 0
                
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        public function leerNfcNueva($data){
            
            $sql = $this->sentenciasSQLManager->leerNfcNuevaIngresada();  // Make sure this function is defined
            $rut_usuario = $data["rut_usuario"];
            $time = 0;
            $bell = 0; // Initialize the variable
            $resultValue = 0; // Initialize a variable to store the result
        
            $stmt = $this->conexion->prepare($sql);
        
            try {
                while ($time <= 30) {
                    $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                    // Check the result of the query
                    if ($result['resultado'] == 1) {
                        $bell = $result['resultado'];
                        echo $resultValue = $result['resultado'];
                        break;
                    }
        
                    sleep(1);
                    $time++;
                }
        
                // If the loop finishes without finding a result, set the result value to 0
                if ($bell != 1){
                    echo 0;
                }
                // Si el bucle termina sin encontrar un resultado, imprime 0
                
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        
        }

        public function leerNfcNuevaUpdate($data){
            session_start();
            $sql0 = $this->sentenciasSQLManager->obtenerTimeNfc();
            $rut_usuario = $data["rut_usuario"];
            $rut_administrador = $_SESSION["rut"];
            $stmt0 = $this->conexion->prepare($sql0);
            $stmt0->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt0->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
            $stmt0->execute();
            $result0 = $stmt0->fetch(PDO::FETCH_ASSOC);
            $recorded_time = $result0['updated_at_usuario'];
            $new_time = $recorded_time;
            // Output the result of the first query
        
            $sql1 = $this->sentenciasSQLManager->leerNfcNuevaIngresadaUpdate();
        
            $stmt = $this->conexion->prepare($sql1);
        
            $time = 0; // Initialize $time
        
            try {
                while ($time <= 30 && $recorded_time == $new_time) {
                    $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                    $stmt->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
                    $stmt->execute();
                
                    // Check if fetch was successful
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    if ($result !== false) {
                        $new_time = $result['updated_at_usuario'];
                
                        // If $recorded_time is still equal to $new_time, sleep and increment $time
                        if ($recorded_time == $new_time) {
                            sleep(3);
                            $time = $time + 1;
                        } else {
                            // If $recorded_time is different from $new_time, exit the loop
                            echo 1;
                            break;
                        }
                    } else {
                        // Handle the case when fetch fails
                        echo "Fetch failed!";
                        break;  // Exit the loop or handle accordingly
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }