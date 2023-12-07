<?php
    require_once '../../Model/registrarDatos.php';
    require_once '../../Model/sql.php';
    include '../../Model/conexion.php';
    header('Content-Type: application/json');

class Administrador {
    private $tempData = []; // Almacena temporalmente los datos
    
    //Funcion que muestra los datos de la tabla
    public function verDatos($data) {
        $conexion = establishConnection();
        if ($conexion instanceof PDO) {
                $filas = 10;
                $pagina_actual = $data['pagina_actual']; // Ajusta según tu implementación
                $datos_pagina = ($pagina_actual - 1)*$filas;
                $sql = obtenerSentenciaSQL(); // Ajusta esto según tu implementación
                $stmt = $conexion->prepare($sql);
                if ($stmt) {
                $stmt->bindParam(':filas', $filas, PDO::PARAM_INT);
                $stmt->bindParam(':datos_pagina', $datos_pagina, PDO::PARAM_INT);
                
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $this->datos = [];

                foreach ($resultados as $fila) {
                    // Agrega cada fila al array $this->datos
                    $this->datos[] = [
                        'rut_usuario' => $fila['rut_usuario'],
                        'nombre_usuario' => $fila['nombre_usuario'],
                        'apellido_usuario' => $fila['apellido_usuario'],
                        'password_usuario' => $fila['password_usuario'],
                        'codigo_nfc_usuario' => $fila['codigo_nfc_usuario'],
                    ];
                }
                $jsonString = json_encode($this->datos);
                echo($jsonString); // O utiliza 'echo' si estás en un entorno web
                return $jsonString;     
            } else {
                // Manejo de error si prepare no fue exitoso
                $response = ['status' => 'error', 'message' => 'Error en la preparación de la sentencia'];
                echo json_encode($response);
            }
    }
    }
    //Funcion de login del Administrador
    public function loginAdministrador($data){

    }
    //funcion que elimina el usuario seteando el valor de la columna en 0
    public function borrarUsuario($data) {
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

    public function procesarRegistroDatos($data) {
            $this->tempData[] = $data;
            $sql = activarModoCreate();
            $conexion = establishConnection();
            $stmt = $conexion->prepare($sql);
            try {
                $stmt->execute();
                $response = ['status' => 'success', 'message' => 'Registro exitoso'];
                $this->tempData['status'] = 'success';
                echo json_encode($this->tempData);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $response = ['status' => 'success', 'message' => 'Registro exitoso'];
        }

    public function procesarRegistroNfc() {
            $this->tempData = [];
            $response = ['status' => 'success', 'message' => 'Datos insertados en la base de datos'];
            echo json_encode($response);
        }

    //Funcion que prepara el ingreso de la nfc según el usuario seleccionado//
    public function preparaNFC($data){

        $rut_usuario = $data["rut_usuario"];
        $sql = preparaNFC();
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $resultado =$stmt->execute();
            echo json_encode($resultado);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$Administrador = new Administrador();
$data = json_decode(file_get_contents("php://input"), true);
// Verificar si se recibieron datos JSON válidos
if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    if($data["funcion"] == "verDatos"){
        $Administrador->verDatos($data);
    }elseif($data["funcion"] == "registrarDatos"){
        $Administrador->procesarRegistroDatos($data);
    }elseif($data["funcion"] == "borrarUsuario"){
        $Administrador->borrarUsuario($data);
    }elseif($data["funcion"] == "lecturaNfc"){
        $Administrador->leerNfc($data);
    }elseif($data["funcion"] == "preparaNFC"){
        $Administrador->preparaNFC($data);
    }elseif($data["funcion"] == "loginAdministrador"){
        $Administrador->loginAdministrador($data);
    }
}else {
        // Devolver una respuesta de error si los datos no son válidos
        $response = ['status' => 'error', 'message' => 'Datos no válidos'];
        echo json_encode($response);
}
?>
    