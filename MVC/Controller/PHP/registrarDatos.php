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
        $sql = obtenerSentenciaSQL(); // Ajusta esto según tu implementación

        // Prepara la sentencia SQL
        $conexion = establishConnection();

// Asegúrate de que $conexion sea una instancia válida de PDO antes de continuar
        if ($conexion instanceof PDO) {
            // Tu lógica aquí
            $stmt = $conexion->prepare($sql);

            if ($stmt) {
                // Asigna los valores y ejecuta la sentencia
                // Puedes ajustar esto según tu implementación y los datos proporcionados en $data
                $valor1 = $data['pagina_actual']; // Ajusta según tu implementación
                $stmt->bindParam(':pagina_actual', $valor1, PDO::PARAM_STR);
                
                $stmt->execute();
                // Envia la respuesta al cliente
                $response = ['status' => 'success', 'message' => 'Registro exitoso'];
                echo json_encode($response);
            } else {
                // Manejo de error si prepare no fue exitoso
                $response = ['status' => 'error', 'message' => 'Error en la preparación de la sentencia'];
                echo json_encode($response);
            }
    }
    }
    
    
    public function procesarRegistroDatos($data) {
            // Procesar los datos recibidos desde JavaScript
            // Puedes acceder a los datos como $data['rutUsuario'], $data['nombreUsuario'], etc.
            $this->tempData[] = $data;
            // Aquí deberías tener lógica para interactuar con la base de datos, validar datos, etc.
            
            // Ejemplo: Guardar en la base de datos
            // $this->guardarEnBaseDeDatos($data);
    
            // Devolver una respuesta (puedes ajustar según tus necesidades)
            $response = ['status' => 'success', 'message' => 'Registro exitoso'];
            echo json_encode($response);
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
        $Adminstrador->procesarRegistroDatos($data);
        }
    } else {
        // Devolver una respuesta de error si los datos no son válidos
        $response = ['status' => 'error', 'message' => 'Datos no válidos'];
        echo json_encode($response);
    }
?>
    