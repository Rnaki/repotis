<?php
    require_once '../../Model/registrarDatos.php';
    require_once '../../Model/sql.php';
    include '../../Model/conexion.php';
    header('Content-Type: application/json');





    // Tu lógica de la capa Model va aquí
// Tu lógica de la capa Model va aquí
class Administrador {
    private $tempData = []; // Almacena temporalmente los datos
    public function verDatos($data) {
        // Lógica para obtener la sentencia SQL desde el archivo (puedes definir una función específica para esto)
        

        // Prepara la sentencia SQL
        $conexion = establishConnection();

// Asegúrate de que $conexion sea una instancia válida de PDO antes de continuar
        if ($conexion instanceof PDO) {
            // Tu lógica aquí
            
                // Asigna los valores y ejecuta la sentencia
                // Puedes ajustar esto según tu implementación y los datos proporcionados en $data
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
                // Retorna el JSON después del bucle
                //return json_encode($this->datos);
                // Envia la respuesta al cliente
            } else {
                // Manejo de error si prepare no fue exitoso
                $response = ['status' => 'error', 'message' => 'Error en la preparación de la sentencia'];
                echo json_encode($response);
            }
    }
    }
    
    public function borrarUsuario($data) {
        $sql = borrarUsuarioSQL();
        
        $stmt = $conexion->prepare($sql);
    
        if ($stmt) {
            $rut_usuario = $data["rut_usuario"];
    
            // Vincula el parámetro correctamente
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
            // Procesar los datos recibidos desde JavaScript
            // Puedes acceder a los datos como $data['rutUsuario'], $data['nombreUsuario'], etc.
            $this->tempData[] = $data;
            // Aquí deberías tener lógica para interactuar con la base de datos, validar datos, etc.
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

            // Ejemplo: Guardar en la base de datos
            // $this->guardarEnBaseDeDatos($data);
    
            // Devolver una respuesta (puedes ajustar según tus necesidades)
            $response = ['status' => 'success', 'message' => 'Registro exitoso'];
            //echo json_encode($response);
        }
    public function procesarRegistroNfc() {
            // Aquí deberías preparar y ejecutar la sentencia SQL para insertar los datos en la base de datos
            // Puedes utilizar transacciones para garantizar que todas las operaciones sean exitosas o ninguna lo sea
            // Ejemplo: $this->ejecutarSentenciaSQL();
    
            // Después de la inserción, puedes restablecer los datos temporales
            $this->tempData = [];
    
            // Devolver una respuesta (puedes ajustar según tus necesidades)
            $response = ['status' => 'success', 'message' => 'Datos insertados en la base de datos'];
            echo json_encode($response);
        }
    }

    
    // Instanciar la clase y procesar el registro
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
        }
    } else {
        // Devolver una respuesta de error si los datos no son válidos
        $response = ['status' => 'error', 'message' => 'Datos no válidos'];
        echo json_encode($response);
    }
?>
    