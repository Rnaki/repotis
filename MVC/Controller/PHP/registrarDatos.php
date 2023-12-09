<?php
    require_once '../../Model/registrarDatos.php';
    require_once '../../Model/sql.php';
    include '../../Model/conexion.php';
    header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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


    public function verDatosEliminados($data) {
        $conexion = establishConnection();
        if ($conexion instanceof PDO) {
                $filas = 10;
                $pagina_actual = $data['pagina_actual']; // Ajusta según tu implementación
                $datos_pagina = ($pagina_actual - 1)*$filas;
                $sql = obtenerdatosEliminados(); // Ajusta esto según tu implementación
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
                        'rut_usuarioEliminado' => $fila['rut_usuario'],
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
    public function mostrarUpdateDatos($data) {
        $sql = mostrarUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
    
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->execute();
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($fila) {
                // En lugar de 'echo', utiliza 'return' para enviar la respuesta al cliente
                echo json_encode([
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

    public function updateDatos($data){
        $sql = sqlUpdateDatos(); // Asegúrate de tener la consulta SQL correcta
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
        $nombre_usuario = $data["nombre_usuario"];
        $apellido_usuario = $data["apellido_usuario"];
    
        try {
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':apellido_usuario', $apellido_usuario, PDO::PARAM_STR);
            echo $stmt->execute();

            // Construye la respuesta JSON
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    

    public function procesarRegistroDatos($data) {
        $this->tempData[] = $data;
        $sql = registrarDatos();
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
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

            
            echo $result;
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




    //Funcion que prepara el ingreso de la nfc según el usuario seleccionado//
    public function preparaNFC($data){
        session_start(); // Inicia la sesión

        // Accede a la variable de sesión
        $rut_administrador = isset($_SESSION['rut']) ? $_SESSION['rut'] : null;

        $rut_usuario = $data["rut_usuario"];
        $sql = preparaNFC();
        $sql2 = ControladorLogin(); 
        $conexion = establishConnection();
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
            $resultado =$stmt->execute();
            $stmt = $conexion->prepare($sql2);
            $stmt->bindParam(':rut_administrador', $rut_administrador, PDO::PARAM_STR);
            $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
            $stmt->execute();
            echo $conexion->commit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
    public function leerNfcAdministrador($data) {
        $sql = leerNfcAdm();  // Asegúrate de tener esta función definida
    
        $conexion = establishConnection();
        $stmt = $conexion->prepare($sql);
        $rut_usuario = $data["rut_usuario"];
        $time = 0;
    
        try {
            while ($time <= 3) {
                $stmt->bindParam(':rut_usuario', $rut_usuario, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                // Verificar el resultado de la consulta
                if ($result['resultado'] == 1) {
                    // Imprime el resultado y sale del bucle
                    echo $result['resultado'];
                    break;
                }
    
                sleep(1);
                $time++;
            }
    
            // Si el bucle termina sin encontrar un resultado, imprime 0
            if ($result['resultado'] != 1) {
                echo "0";
            }
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
    }elseif($data["funcion"] == "verDatosEliminados"){
        $Administrador->verDatosEliminados($data);
    }elseif($data["funcion"] == "mostrarUpdateDatos"){
        $Administrador->mostrarUpdateDatos($data);
    }elseif($data["funcion"] == "updateDatos"){
        $Administrador->updateDatos($data);
    }elseif($data["funcion"] == "registrarDatos"){
        $Administrador->procesarRegistroDatos($data);
    }elseif($data["funcion"] == "borrarUsuario"){
        $Administrador->borrarUsuario($data);
    }elseif($data["funcion"] == "recuperarUsuarioEliminado"){
        $Administrador->recuperarUsuarioEliminado($data);
    }elseif($data["funcion"] == "preparaNFC"){
        $Administrador->preparaNFC($data);
    }elseif($data["funcion"] == "loginAdministrador"){
        $Administrador->loginAdministrador($data);
    }elseif($data["funcion"] == "leerNfcAdministrador"){
        $Administrador->leerNfcAdministrador($data);
    }
}else {
        // Devolver una respuesta de error si los datos no son válidos
        $response = ['status' => 'error', 'message' => 'Datos no válidos'];
        echo json_encode($response);
}
?>
    